<template>
    <div>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>P.R No.</th>
                    <th width="100">Open P.R</th>
                    <th width="100">Open P.0</th>
                    <th>Supplier Name</th>
                    <th>Canvass By</th>
                    <th>Canvass Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody v-for="qf in quotation_forms">
                <tr>
                    <td>{{ qf.request_form_id }}</td>
                    <td>
                        <a @click="openPR(qf)" style="cursor: pointer" class="glyphicon glyphicon-folder-close"></a>
                    </td>
                     <td>
                        <a @click="openPO(qf)" style="cursor: pointer" class="glyphicon glyphicon-folder-close"></a>
                    </td>
                    <td>{{ getSupplierName(qf) }}</td>
                    <td style="font-weight: bolder">{{ qf.canvass_by }}</td>
                    <td>{{ formatDate(qf.canvass_date) }}</td>
                    <td>{{ statusOfQuotation(qf) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        mounted() {

        },
        methods: {
            statusOfQuotation(qf){
                let self = this;
                return 'loading...';
            },
            openPO(qf){
                let self = this;
                console.log(qf);
            },
            openPR(qf){
                let self = this;
                console.log(qf);
            },
            formatDate(date){
                return moment(date).format('MMMM DD, YYYY') + ' (' + moment(date).fromNow() + ')';
            },
            getSupplierName(qf){
                let self = this;
                let rs = _.filter(self.suppliers, { id: qf.supplier_id});
                if (rs.length) {
                    return rs[0].name.toUpperCase();
                }else { return 'not-found'; };
            }
        },
        computed: {
            request_forms(){
                return this.$store.getters.request_forms;
            },
            request_items(){
                return this.$store.getters.request_items;
            },
            quotation_forms(){
                return this.$store.getters.quotation_forms;
            },
            quotation_items(){
                return this.$store.getters.quotation_items;
            },
            suppliers(){
                return this.$store.getters.suppliers;
            }
        },
        watch: {
            
        }
    }
</script>
