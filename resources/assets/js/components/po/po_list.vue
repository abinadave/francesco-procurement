<template>
    <div>
        <div>
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">Purchase Orders
                   
                    </div>

                    <div class="panel-body" style="font-size: 12px">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">P.O DETAILS</th>
                                    <th class="text-center" width="">P.R DETAILS</th>
                                    <th>PR NO.</th>
                                    <th>SUPPLIER NAME</th>
                                    <th style="text-align: center">QUOTATION NO.</th>
                                    <th>DATE & TIME</th>
                                    <th>ITEMS</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="po in purchase_orders">
                                    <td class="text-center">
                                        <a style="cursor: pointer" @click="openPoReceipt(po)">
                                            P.O <i class="glyphicon glyphicon-folder-close"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a style="cursor: pointer" @click="openPr(po)" class="glyphicon glyphicon-folder-close"> 
                                            P.R
                                        </a>
                                    </td>
                                    <td>{{ po.pr_no }}</td>
                                    <td>{{ getSupplierName(po.supplier_id) }}</td>
                                    <td style="text-align: center">{{ po.quotation_form_id }}</td>
                                    <td>{{ formatDate(po.datetime) }}</td>
                                    <td>{{ getItems(po) }}</td>
                                    <th class="text-primary" style="font-weight: bolder; font-size: 14px">
                                        {{ getTotalAmount(po) }}
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <po-receipt></po-receipt>
        <modal-pr-items :items="prItems" :form="prForm"></modal-pr-items>
    </div>
</template>

<script>
    import accounting from 'accounting'
    import moment from 'moment'
    import PoModalReceipt from './po_modal_printable.vue'
    import ModalPrItems from '../engineer/requisition/modal_requested_items.vue'
    export default {
        mounted() {
            this.$store.commit('FETCH_PURCHASE_ORDERS');
            this.$store.commit('FETCH_REQUEST_FORMS_ITEMS');
            this.$store.commit('FETCH_SUPPLIERS');
            this.$store.commit('FETCH_HOUSE_MODELS');
            this.fetchSupplier();
        },
        data(){
            return {
                suppliers: [],
                prItems: [],
                prForm: {}
            }
        },
        components: {
            'po-receipt': PoModalReceipt,
            'modal-pr-items': ModalPrItems
        },
        computed: {
            
            purchase_orders(){
                return this.$store.getters.purchase_orders;
            },
            po_items(){
                return this.$store.getters.po_items;
            },
            request_forms(){
                return this.$store.getters.request_forms;
            },
            request_items(){
                return this.$store.getters.request_items;
            }
        },
        methods: {
            openPr(po){
                let self = this;
                $('#modal-requested-items').modal('show');
                let rsPrForms = _.filter(self.request_forms, {id: Number(po.pr_no)});
                self.prItems = self.request_items.filter(function(model){
                    return Number(model.request_form_id) === Number(po.pr_no);
                });
                if (rsPrForms.length) {
                    self.prForm = rsPrForms[0];
                };
            },
            getTotalAmount(purchase_order){
                let self = this;
                let item = {}, total = 0.0;
                let items = self.po_items.filter(function(model){
                    return Number(model.po_id) === Number(purchase_order.id);
                });
                for (var i = items.length - 1; i >= 0; i--) {
                    item = items[i];
                    total += Number(item.qty) * Number(item.unit_price);
                };
                return accounting.formatNumber(total, 2);
            },
            openPoReceipt(po){
                let self = this;
                $('#modal-po-receipt').modal('show');
                self.$store.commit({
                    type: 'SET_CURRENT_PO',
                    po: po
                });
            },
            getItems(po){
                let self = this;
                let rsItems = _.filter(self.po_items, {po_id: Number(po.id)});
                return _.map(rsItems, 'description').join(', ');
            },
            formatDate(datetime){
                let self = this;
                return moment(datetime).format('MMMM DD, YYYY HH:mm a');
            },
            getSupplierName(i){
                let self = this;
                let rsSuppliers = _.filter(self.suppliers, {id: Number(i)});
                if (rsSuppliers.length) {
                    let model = _.first(rsSuppliers);
                    return model.name.toUpperCase();
                }
            },
            fetchSupplier(){
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
            }
        }
    }
</script>
