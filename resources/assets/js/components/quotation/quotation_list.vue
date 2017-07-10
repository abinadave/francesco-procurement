<template>
    <div>
        <select style="width: 200px" v-model="cboApprovedQf" class="form-control">
            <option value="all">All</option>
            <option value="approved">Approved</option>
            <option value="not-approved">Not Approved</option>
        </select>
        <table class="table table-hover table-condensed table-bordered" style="margin-top: 10px">
            <thead>
                <tr>
                    <th width="80">P.R No.</th>
                    <th width="100">Status/Approved</th>
                    <th width="100">Open P.0</th>
                    <th width="180">Date Approved</th>
                    <th class="text-center">Approved by</th>
                    <th width="100">Open P.R</th>
                    <th>Supplier Name</th>
                    <th>Canvass By</th>
                    <th>Canvass Date</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody v-for="qf in quotation_forms">
                <tr :class="{ 'approved-class': qf.approved === 1 }">
                    <td style="font-weight: bolder">{{ qf.request_form_id }}</td>
                    <td>
                        <span v-if="qf.approved === 1">
                            <span class="glyphicon glyphicon-ok"></span>
                        </span>
                    </td>
                    <td>
                        <span v-show="qf.approved === 1">
                             <a @click="openPoReceipt(qf)" style="cursor: pointer" class="text-success glyphicon glyphicon-folder-close"></a>
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
                    <td>{{ getSupplierName(qf) }}</td>
                    <td style="font-weight: bolder">{{ qf.canvass_by }}</td>
                    <td>{{ formatDate(qf.canvass_date) }}</td>
                    <th style="font-size: 14px" class="text-danger">{{ getTotolAMountQuotation(qf) }}</th>
                </tr>
            </tbody>
        </table>
        <modal-pr-items :items="prItems" :form="prForm"></modal-pr-items>
    </div>
</template>
<style scoped>
    th, td {
        text-align: center;
    }
    .approved-class {
        font-weight: bolder;
        font-size: 14px;
    }
</style>
<script>
    import accounting from 'accounting'
    import moment from 'moment'
    import ModalPrItems from '../engineer/requisition/modal_requested_items.vue'
    export default {
        mounted() {

        },
        data(){
            return {
                prItems: [],
                prForm: {},
                cboApprovedQf: 'all'
            }
        },
        methods: {
            openPoReceipt(qf){
                let self = this;
                let rsPo = _.filter(self.purchase_orders, { quotation_form_id: Number(qf.id) });
                if (rsPo.length) {
                    let po = _.first(rsPo);
                    self.$store.commit({
                        type: 'SET_CURRENT_PO',
                        po: po
                    });
                    $('#modal-po-receipt').modal('show');
                }else {
                    alert('Purchase Order Not found from the database with quotaton_form_id: ' + qf.id);
                }
            },
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
                $('#modal-requested-items').modal('show');
                let rsPrForms = _.filter(self.request_forms, {id: Number(qf.request_form_id)});
                console.log(rsPrForms)
                self.prItems = self.request_items.filter(function(model){
                    return Number(model.request_form_id) === Number(qf.request_form_id);
                });
                if (rsPrForms.length) {
                    self.prForm = rsPrForms[0];
                };
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
        components: {
            'modal-pr-items': ModalPrItems
        },
        computed: {
            purchase_orders(){
                return this.$store.getters.purchase_orders;
            },
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
            'cboApprovedQf': function(selected){
                let self = this;
                let returned = self.$store.commit({
                    type: 'FILTER_APPROVED_QUOTATIONS',
                    selected: selected
                });
            },
            'quotation_forms': function(newVal){
                let ids = _.map(newVal, 'request_form_id');
                let uniqueIds = _.uniq(ids);
                console.log(uniqueIds);
            }
        }
    }
</script>
