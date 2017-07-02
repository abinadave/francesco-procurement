<template>
    <div>
        
        <table class="table table-hover table-condensed table-bordered">
            <thead>
                <tr>
                    <th width="80">P.R No.</th>
                    <th width="100">Status/Approved</th>
                    <th width="180">Date Approved</th>
                    <th class="text-center">Approved by</th>
                    <th width="100">Open P.R</th>
                    <th width="100">Open P.0</th>
                    <th>Supplier Name</th>
                    <th>Canvass By</th>
                    <th>Canvass Date</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody v-for="qf in quotation_forms">
                <tr :class="{ 'info': ifEven(qf.request_form_id), 'text-primary': qf.approved === 1 }">
                    <td style="font-weight: bolder">{{ qf.request_form_id }}</td>
                    <td>
                        <span v-if="qf.approved === 1">
                            <span class="glyphicon glyphicon-ok"></span>
                        </span>
                    </td>
                    <td>
                        <span v-if="qf.approved === 1">
                            <span>{{ formatDateApproval(qf.approval_date) }}</span>
                        </span>
                    </td>
                    <td>
                        <span v-if="qf.approved === 1">
                            <span>{{ approvedBy(qf.approved_by) }}</span>
                        </span>
                    </td>
                    <td>
                        <a @click="openPR(qf)" style="cursor: pointer" class="glyphicon glyphicon-folder-close"></a>
                    </td>
                     <td>
                        <a @click="openPO(qf)" style="cursor: pointer" class="glyphicon glyphicon-folder-close"></a>
                    </td>
                    <td>{{ getSupplierName(qf) }}</td>
                    <td style="font-weight: bolder">{{ qf.canvass_by }}</td>
                    <td>{{ formatDate(qf.canvass_date) }}</td>
                    <th style="font-size: 14px" class="text-danger">{{ getTotolAMountQuotation(qf) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style scoped>
    th, td {
        text-align: center;
    }
</style>
<script>
    import accounting from 'accounting'
    import moment from 'moment'
    export default {
        mounted() {

        },
        methods: {
            ifEven(n){
                return n % 2 == 0;
            },
            approvedBy(approved_by){
                let self = this;
                let rs = _.filter(self.users, {id: Number(approved_by)});
                if(rs.length){
                    return rs[0].name;
                }else {
                    return 'user not found';
                }
            },
            formatDateApproval(date){
                return moment(date).format('MMMM DD, YYYY hh:mm a');
            },
            getTotolAMountQuotation(qf){
                let self = this;
                let total = 0;
                let rsQuotationItems = _.filter(self.quotation_items, { quotation_form_id: Number(qf.id) });
                let item = {};
                for (var i = rsQuotationItems.length - 1; i >= 0; i--) {
                    item = rsQuotationItems[i];
                    total += Number(item.qty) * Number(item.unit_price);
                };
                return accounting.formatNumber(total, 2);
            },
            statusOfQuotation(qf){
                let self = this;
                if (qf.approved) {
                    return 'glyphicon glyphicon-ok';
                }else {
                    return 'glyphicon glyphicon-remove';
                }
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
            users(){
                return this.$store.getters.users;
            },
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
            'quotation_forms': function(newVal){
                let ids = _.map(newVal, 'request_form_id');
                let uniqueIds = _.uniq(ids);
                console.log(uniqueIds);
            }
        }
    }
</script>
