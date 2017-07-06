<template>
  <div class="modal fade" id="modal-quotations" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 80%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Quotations for P.R NO: <b class="text-primary">{{ requestForm.id }}</b></h4>
        </div>
        <div class="modal-body" style="overflow: auto">
          <div v-show="whileCreatingPo">
             <create-po @create-po-fade="hideCreatePoForm"
                :quotation-form="currentQuotationForm"
                :house-models="houseModels"
             ></create-po>
          </div>
          <div v-show="!whileCreatingPo">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">

                <li role="presentation" class="active">
                      <a href="#pr" aria-controls="pr" role="tab" data-toggle="tab">
                          Purchase Reques here
                      </a>
                </li>

                <li :id="'quotation-'+ quotationForm.id" role="presentation" v-for="quotationForm in quotation_forms">
                      <a :href="'#'+ quotationForm.id" :aria-controls="quotationForm.id" role="tab" data-toggle="tab">
                      <div v-if="quotationForm.approved === 1">
                            <span class="glyphicon glyphicon-ok"></span> &nbsp;
                            <b style="font-size: 15px">

                                {{ getSupplierWhat(quotationForm, 'name') }}

                            </b>
                      </div>
                      <div v-else>
                            {{ getSupplierWhat(quotationForm, 'name') }}
                      </div>
                          
                      </a>
                </li>
                
              </ul>

              <!-- Tab panes -->
              <div class="tab-content">
                
                <div role="tabpanel" class="tab-pane active" id="pr">
                    <current-pr :form="requestForm" :items="requestItems"></current-pr>                
                </div>

                <div v-for="quotationForm in quotation_forms" role="tabpanel" class="tab-pane" :id="quotationForm.id">
                    <div class="panel panel-primary" style="padding: 20px">
                         <div v-show="user.usertype === 'procurement-officer' && quotationForm.approved !== 1">
                             <div v-if="hasApprovedQuotations(quotationForm) === true">
                                 <button class="btn btn-primary pull-right" @click="createPo(quotationForm)">
                                     Create P.O
                                 </button>
                             </div>
                         </div>
                         <div v-show="quotationForm.approved === 1" class="pull-right">
                             <i>Approved</i> | <b class="text-info">{{ getDateTimeFromNow(quotationForm.approval_date) }}</b>
                                             | <b class="text-primary">{{ formatDate(quotationForm.approval_date) }}</b>&nbsp;&nbsp;&nbsp;
                             <span class="text-info glyphicon glyphicon-check " style="font-size: 19px"></span>
                         </div>
                         <h3>Quotation</h3><br>
                         <label>Canvass by:
                             <input type="text" class="form-control" :value="quotationForm.canvass_by" disabled>
                         </label>
                         <label>Canvass date:
                             <input type="text" class="form-control" :value="quotationForm.canvass_by" disabled>
                         </label>
                         <label>Purchase Officer:
                             <input type="text" class="form-control" :value="getPurchaseOfficerName(quotationForm)" disabled>
                         </label>
                         <label>Date Quotation Created:
                             <input type="text" class="form-control" :value="formatDate(quotationForm.datetime)" disabled>
                         </label>
                         <hr>
                         <table class="table table-bordered table-hover table-condensed" id="tbl-quotations">
                            <thead>
                               <tr>
                                    <th class="text-center" width="60">Qty</th>
                                    <th class="text-center" width="70" >Unit </th>
                                    <th>Description</th>
                                    <th class="text-center" width="80">Unit price</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="quotationItem in findQuotationItems(quotationForm)">
                                    <td class="text-center">{{ quotationItem.qty }}</td>
                                    <td class="text-center">{{ quotationItem.unit }}</td>
                                    <td>{{ quotationItem.description }}</td>
                                    <td class="text-center">{{ quotationItem.unit_price }}</td>
                                    <td class="text-right">{{ getTotal(quotationItem) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-center" style="font-weight: bolder">TOTAL COST</th>
                                    <th class="text-right" style="font-size: 20px"><b class="text-danger">P {{ getTotalOfItemsInEachForm(quotationForm) }}</b></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
              </div>

            </div>
            
        </div>
       
      </div>
    </div>
  </div>
</template>
<style type="text/css">
    
</style>
<script>
    import accounting from 'accounting'
    import moment from 'moment'
    import CurrentPrComponent from './current_purchase_request.vue'
    import CreatePoComponent from '../po/create_po.vue'
    export default {
        mounted() {
            let self = this;
            self.fetchUsers();
            self.fetchSuppliers();
            jQuery(document).ready(function($) {
                $('#modal-quotations').on('hidden.bs.modal', function (e) {
                    self.$emit('reset-request-forms-quotations-modal');
                });
            });
        },
        data(){
            return {
                users: [], suppliers: [],
                quotation_forms: [],
                quotation_items: [],
                requestItems: [],
                whileCreatingPo: false,
                currentQuotationForm: {}
            }
        },
        components: {
            'current-pr': CurrentPrComponent,
            'create-po': CreatePoComponent
        },
        props: {
            requestForm: {
                type: Object
            },
            houseModels: {
                type: Array
            },
            user: {
                type: Object
            }
        },
        computed: {
            store_quotation_forms(){
                return this.$store.getters.quotation_forms;
            },
            store_quotation_items(){
                return this.$store.getters.quotation_items;
            }
        },
        methods: {
            getDateTimeFromNow(datetime){
                return moment(datetime).fromNow();
            },
            hasApprovedQuotations(qf){
                let self = this;
                let rs = _.filter(self.store_quotation_forms, {
                    request_form_id: Number(qf.request_form_id),
                    approved: 1
                });
                if (rs.length) {
                    return false
                }else {
                    return true;                    
                }
            },
            hideCreatePoForm(){
                let self = this;
                self.whileCreatingPo = false;
            },
            createPo(quotationForm){
                let self = this;
                self.whileCreatingPo = true;
                self.currentQuotationForm = quotationForm;
            },
            formatDate(date){
                return moment(date).format('MMMM DD, YYYY, hh:mm a');
            },
            getPurchaseOfficerName(quotationForm){
                let self = this;
                let poi = quotationForm.purchase_officer_id;
                let rs = _.filter(self.users, {id: Number(poi)});
                if (rs.length) {
                    let officer = _.first(rs);
                    return officer.name.toUpperCase();
                }
            },
            getSupplierWhat(form, index){
                let self = this;
                let rsSupplier = _.filter(self.suppliers, {id: Number(form.supplier_id)});
                if (rsSupplier.length) {
                    let supplier = _.first(rsSupplier);
                    return supplier[index];
                }
            },
            fetchSuppliers(){
                let self = this;
                self.$http.get('/supplier').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.suppliers.push(json[i]);
                        }
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            getTotal(item){
                let total = 0;
                total = Number(item.qty) * Number(item.unit_price);
                return accounting.formatNumber(total, 2);
            },
            findQuotationItems(form){
                let self = this;
                return self.quotation_items.filter(function(index) {
                    return Number(index.quotation_form_id) === Number(form.id);
                });
            },
            getTotalOfItemsInEachForm(form){
                let self = this;
                let items = self.findQuotationItems(form);
                let item = {}, total = 0.0;
                for (var i = items.length - 1; i >= 0; i--) {
                    item = items[i];
                    total += Number(item.qty) * Number(item.unit_price);
                };
                return accounting.formatNumber(total, 2);
            },
            fetchUsers(){
                let self = this;
                self.$http.get('/user').then((resp) => {
                  if (resp.status === 200) {
                    let json = resp.body;
                    for (var i = json.length - 1; i >= 0; i--) {
                      self.users.push(json[i]);
                    }
                  }
                }, (resp) => {
                  console.log(resp);
                });
            },
            activeFirstQuotation(){
               
            },
            getRequestedItems(){
                let self = this;
                self.requestItems = [];
                self.$http.post('/get_request_items_by_form', {
                    request_form_id: self.requestForm.id
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.requestItems.push(json[i]);
                        };
                    }
                }, (resp) => {
                    console.log(resp);
                })
            }
        },
        watch: {
            'requestForm': function(newVal){
                let self = this;
                self.getRequestedItems();
                self.quotation_forms = [];
                self.quotation_items = [];
                self.$http.post('/get_quotation_forms_by_pr_no', {
                    request_form: newVal
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.quotation_forms.length - 1; i >= 0; i--) {
                            self.quotation_forms.unshift(json.quotation_forms[i]);
                        }
                        for (var i = json.quotation_items.length - 1; i >= 0; i--) {
                            self.quotation_items.unshift(json.quotation_items[i]);
                        }
                    }
                }, (resp) => {
                    console.log(resp);
                })
            }
        }
    }
</script>
