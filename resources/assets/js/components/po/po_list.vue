<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">Purchase Orders
                    po_items: {{ po_items.length }}
                    </div>

                    <div class="panel-body">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th width="20"></th>
                                    <th>PR NO.</th>
                                    <th>SUPPLIER NAME</th>
                                    <th>QUOTATION NO.</th>
                                    <th>DATE & TIME</th>
                                    <th>ITEMS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="po in purchase_orders">
                                    <td><a style="cursor: pointer" @click="openPoReceipt(po)"><i class="glyphicon glyphicon-folder-close"></i></a></td>
                                    <td>{{ po.pr_no }}</td>
                                    <td>{{ getSupplierName(po.supplier_id) }}</td>
                                    <td>{{ po.quotation_form_id }}</td>
                                    <td>{{ formatDate(po.datetime) }}</td>
                                    <td>{{ getItems(po) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <po-receipt></po-receipt>
    </div>
</template>

<script>
    import moment from 'moment'
    import PoModalReceipt from './po_modal_printable.vue'
    export default {
        mounted() {
            this.$store.commit('FETCH_PURCHASE_ORDERS');
            this.$store.commit('FETCH_REQUEST_FORMS_ITEMS');
            this.$store.commit('FETCH_SUPPLIERS');
            this.fetchSupplier();
        },
        data(){
            return {
                suppliers: []
            }
        },
        components: {
            'po-receipt': PoModalReceipt
        },
        computed: {
            purchase_orders(){
                return this.$store.getters.purchase_orders;
            },
            po_items(){
                return this.$store.getters.po_items;
            }
        },
        methods: {
            openPoReceipt(po){
                let self = this;
                $('#modal-po-receipt').modal('show');
                self.$store.commit({
                    type: 'CURRENT_PO',
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
