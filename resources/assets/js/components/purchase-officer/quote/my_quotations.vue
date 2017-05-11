<template>
    <div>
       <table id="tbl-my-quotes" class="table table-hover table-condensed table-striped">
           <thead>
               <tr>
                   <th class="text-center">P.R No.</th>
                   <th class="text-center">Supplier Name</th>
                   <th>Supplier Address</th>
                   <th class="text-center">Canvass by</th>
                   <th class="text-center">Canvass date</th>
                   <th>No of Items</th>
                   <th>Items</th>
               </tr>
           </thead>
           <tbody>
               <tr v-for="quotationForm in quotation_forms">
                   <td class="text-center">{{ quotationForm.request_form_id }}</td>
                   <td class="text-center">{{ getSupplierWhat(quotationForm.supplier_id, 'name') }}</td>
                   <td>{{ getSupplierWhat(quotationForm.supplier_id, 'address') }}</td>
                   <td class="text-center">{{ quotationForm.canvass_by }}</td>
                   <td class="text-center">{{ formatDate(quotationForm.canvass_date) }}</td>
                   <td>{{ getTotalNumberOfQuoteItems(quotationForm.id)}}</td>
                   <td>{{ getSampleItems(quotationForm.id) }}</td>
               </tr>
           </tbody>
       </table>
    </div>
</template>
<style type="text/css">
    #tbl-my-quotes {
        font-size: 12px;
    }
</style>
<script>
    import moment from 'moment'
    export default {
        mounted() {
            // console.log('Component mounted.')
            this.fetchMyQuotations();
        },
        props: {
            suppliers: {
                type: Array
            }
        },
        data(){
            return {
                quotation_forms: [],
                quotation_items: []
            }
        },
        methods: {
            getSampleItems(quotationFormId){
                let self = this;
                let rsItems = _.filter(self.quotation_items, {quotation_form_id: Number(quotationFormId)});
                return _.map(rsItems, 'description').join(', ');
            },
            getTotalNumberOfQuoteItems(quotationFormId){
                let self = this;
                return _.filter(self.quotation_items, {quotation_form_id: Number(quotationFormId)}).length;
            },
            formatDate(date){
                return moment(date).format('MMMM DD, YYYY, ddd');
            },
            getSupplierWhat(supplierId, key){
                let self = this;
                let rs = _.filter(self.suppliers, {id: Number(supplierId)});
                if (rs.length) {
                    return rs[0][key];
                }else { return 'not found: ' + key; };
            },
            fetchMyQuotations(){
                let self = this;
                self.$http.get('/my_quotation_forms').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.quotation_items.length - 1; i >= 0; i--) {
                            self.quotation_items.unshift(json.quotation_items[i]);
                        };
                        for (var i = json.quotation_forms.length - 1; i >= 0; i--) {
                            self.quotation_forms.unshift(json.quotation_forms[i]);
                        };
                    }
                }, (resp) => {
                    console.log(resp);
                });
            }
        }
    }
</script>
