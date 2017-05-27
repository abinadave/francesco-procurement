<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">Purchase Orders</div>

                    <div class="panel-body">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>PR NO.</th>
                                    <th>SUPPLIER NAME</th>
                                    <th>QUOTATION NO.</th>
                                    <th>DATE & TIME</th>
                                    <th>ITEMS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="po in purchase_orders">
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
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        mounted() {
            // console.log('Component mounted.')
            this.fetchPos();
            this.fetchSupplier();
        },
        data(){
            return {
                po_items: [],
                purchase_orders: [],
                suppliers: []
            }
        },
        methods: {
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
            fetchPos(){
                let self = this;
                self.$http.get('/po')
                .then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.po_items.length - 1; i >= 0; i--) {
                            self.po_items.unshift(json.po_items[i]);
                        };
                        for (var i = json.purchase_orders.length - 1; i >= 0; i--) {
                            self.purchase_orders.unshift(json.purchase_orders[i]);
                        };
                    }
                }, (resp) => {
                    console.log(resp);
                });
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
