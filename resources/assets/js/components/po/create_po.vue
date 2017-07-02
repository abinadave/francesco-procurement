<template>
    <div>
        <a class="btn btn-sm btn-default" style="cursor: pointer;" @click="$emit('create-po-fade')">Back</a>
        <div class="col-md-8">
            <div class="form-group col-md-4 has-error">
              <label class="control-label">Supplier name</label>
              <input type="text" :value="getSupplierWhat('name')" class="form-control input-sm">
            </div>
            <div class="form-group col-md-4 has-error">
              <label class="control-label">Supplier Address</label>
              <input type="text" :value="getSupplierWhat('address')" class="form-control input-sm">
            </div>
            <div class="form-group col-md-4 has-error">
              <label class="control-label">Supplier Contact #</label>
              <input type="text" :value="getSupplierWhat('contact')" class="form-control input-sm">
            </div>
            <div class="form-group col-md-4 has-error">
              <label class="control-label">P.O Number</label>
              <input type="text" class="form-control input-sm">
            </div>
        </div>
        <div style="width: 90%; margin-left: 20px">
            <div class="col-md-9"></div><br><br><br><br>
            <h4 class="page-header">Description of Materials</h4>
            <div class="pull-right" style="margin-top: -10px">
                <a style="cursor: pointer" @click="fetchOriginalQitems"><i class="glyphicon glyphicon-list"></i>&nbsp;Original Items</a>
                <a style="cursor: pointer" @click="addNewQitem"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add</a>
            </div><br>
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th width="350" class="text-center">Description/Specification</th>
                        <th width="150" class="text-center">Quantity</th>
                        <th width="100" class="text-center">Unit</th>
                        <th width="200">Unit Price</th>
                        <th>Total Amount</th>
                        <th width="1"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(qitem, index) in quotation_items">
                        <td>
                            <input style="height: 27px" type="text" class="form-control text-center" v-model="qitem.description">
                        </td>    
                        <td>
                            <input style="height: 27px" type="number" class="form-control text-center" v-model="qitem.qty">
                        </td>
                        <td>
                            <input style="height: 27px" type="text" class="form-control text-center" v-model="qitem.unit">
                        </td>
                        <td>
                            <input style="height: 27px" type="number" class="form-control text-center" v-model="qitem.unit_price">
                        </td>
                        <td class="text-right">
                            {{ getTotal(qitem) }}
                        </td>
                        <td><a style="cursor: pointer" @click="removeQitem(index)"><i class="glyphicon glyphicon-remove"></i></a></td>
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th class="text-center" colspan="4">Total Cost</th>
                        <th colspan="2" class="text-center text-danger" style="font-size: 20px">P {{ getTotalCost }}</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div>
            <button :disabled="whileSaving" @click="proceedToPo" type="button" class="btn btn-block btn-success" style="width: 90%; margin-left: 20px">PROCEED P.O</button>
        </div>
        <div class="col-md-10">
            <hr/>
            <p>More information [<b class="text-primary">Purchase Request</b>]</p>
            <additional-info-pr 
            :house-models="houseModels"
            :request-form="request_form"></additional-info-pr>
        </div>
    </div>
</template>
<style type="text/css">
    input {
        height: 20px;
    }
</style>
<script>
    import accounting from 'accounting'
    import additionalInfoComponent from '../pr/additional_info_pr.vue'
    import alertify from 'alertify.js'
    import moment from 'moment'
    export default {
        mounted() {
            // console.log('Component mounted.')
            this.fetchSuppliers();
        },
        components: {
            'additional-info-pr': additionalInfoComponent
        },
        props: {
            quotationForm: {
                type: Object
            },
            houseModels: {
                type: Array
            }
        },
        data(){
            return {
                request_form: {},
                request_items: [],
                suppliers: [],
                quotation_items: [],
                whileSaving: false
            }
        },
        computed: {
            getTotalCost(){
                let self = this;
                let total = 0.0;
                let item = {};
                for (var i = self.quotation_items.length - 1; i >= 0; i--) {
                    item = self.quotation_items[i];
                    total += Number(item.qty) * Number(item.unit_price);
                };
                return accounting.formatNumber(total, 2);
            }
        },
        methods: {
            proceedToPo(){
                let self = this;
                self.whileSaving = true;
                self.$http.post('/purchase_order', {
                    quotation_form: self.quotationForm,
                    items: self.quotation_items,
                    approval_date: moment().format('MMMM DD, YYYY HH:mm:ss')
                }).then((resp) => {
                    $("#modal-quotations").modal('hide');
                    alertify.alert('[<b class="text-primary">Purchase Order</b>] successfully created');
                    setTimeout(function(){
                        self.whileSaving = false;
                    }, 2000);
                }, (resp) => {
                    console.log(resp);
                    setTimeout(function(){
                        self.whileSaving = false;
                    }, 2000);
                })
            },
            fetchOriginalQitems(){
                let self = this;
                self.getPrForm();
            },  
            removeQitem(index){
                let self = this;
                let confirmation = confirm('Are you sure ?');
                if (confirmation) {
                    self.quotation_items.splice(index, 1);
                    alertify.log('quotation item: removed');
                };
            },
            addNewQitem(){
                let self = this;
                let model = {
                    qty: 0,
                    unit: '',
                    unit_price: 0,
                    description: '',
                    quotation_form_id: self.quotationForm.id,
                    request_form_id: self.quotationForm.request_form_id
                };
                self.quotation_items.push(model);
            },
            getTotal(qitem){
                let self = this;
                let total = Number(qitem.qty) * Number(qitem.unit_price);
                return accounting.formatNumber(total, 2);
            },
            getSupplierWhat(key){
                let self = this;
                let rsSuppliers = _.filter(self.suppliers, { id: Number(self.quotationForm.supplier_id)});
                if (rsSuppliers.length) {
                    let supplier = _.first(rsSuppliers);
                    return supplier[key];
                }
            },
            fetchSuppliers(){
                let self = this;
                self.$http.get('/supplier').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.suppliers.push(json[i]);
                        };
                    };
                }, (resp) => {
                    console.log(resp);
                });
            },
            getPrForm(){
                let self = this;
                self.$http.post('/get_pr_form_by_id', {
                    quotation_form: self.quotationForm
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.request_items = json.request_items;
                        self.quotation_items = json.quotation_items;
                        self.request_form = json.request_form;
                    }
                }, (resp) => {
                    console.log(resp);
                });
            }
        },
        watch: {
            'quotationForm': function(qf){
                let self = this;
                self.getPrForm();
            },
            'request_form': function(){
                console.log('change in request form');
            },
            'request_items': function(){
                console.log('change in request items');
            }
        }
    }
</script>
