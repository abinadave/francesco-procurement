<template>
  <div>
    <div style="overflow: auto">
        <div v-if="user.usertype === 'finance-officer'">
            <button @click="approveRequest" class="btn btn-success btn-sm">Approve <i class="glyphicon glyphicon-thumbs-up"></i></button>
            <button @click="disapproveRequest" class="btn btn-danger btn-sm">Dis-approve <i class="glyphicon glyphicon-thumbs-down"></i></button>
        </div>
        House Model:<select v-model="cboHouseModel" class="form-control" style="width: 240px">
            <option value="0">All House Model</option>
            <option :value="hm.id" v-for="hm in houseModels">
                {{ hm.model }}
            </option>
        </select>
        <table :class="{ 'po-officer-table' : user.usertype === 'purchase-officer' }" id="tbl-requests" class="table table-hover table-condensed table-bordered">
            <thead>
                <tr>
                    <th width="230" style="text-align: center">DATE</th>
                    <th width="100" style="text-align: center">TIME</th>
                    <th width="30"></th>
                    <th class="text-center" width="70">APPROVED</th>
                    <th class="text-center" width="80">QUOTATIONS</th>
                    <th width="140" v-show="user.usertype === 'purchase-officer'"></th>
                    <th width="60" class="text-center">PR NO.</th>
                    <th>REQUESTED BY</th>
                    <th>HOUSE MODEL</th>
                    <th style="text-align: center">LOCATION</th>
                    <th width="90"style="text-align: center">BLOCK NO.</th>
                    <th style="text-align: center">CHARGING</th>
                    <th>Items</th>
                    <th>ESTIMATED COST</th>
                </tr>
            </thead>
            <tbody>
                <tr :class="{ 'font-bold': ifNotOpened(form) }" :value="form" @click="setCurrentForm(form)" v-for="form in requestForms">
                    <td style="text-align: center">{{ getDate(form.datetime) }} - {{ getTimeFromNow(form.datetime)}}</td>
                    <td style="text-align: center">{{ getTime(form.datetime) }}</td>
                    <td class="text-center">
                        <i @click="showItems(form)" style="cursor: pointer" :class="determineFolder(form)"></i>
                    </td>
                    <td class="text-center">
                        <span v-if="form.approved === 1" :title="loadTitleApprovedDate(form)">
                            <i class="text-primary glyphicon glyphicon-ok-sign"></i>
                        </span>
                    </td>
                    <td class="text-center"><a style="cursor: pointer" @click="showModalQuotations(form)">{{ getQuotations(form) }}</a></td>
                    <td class="text-center" v-show="user.usertype === 'purchase-officer'">
                        <span v-if="hasApprovedQuotations(form) === true">
                        <a v-if="form.approved === 1"@click="createInvitationToQuote(form)" style="cursor: pointer">Add Quotation</a>
                        </span>
                        <span v-else>
                            Quote Approved
                        </span>
                    </td>
                    <td style="text-align: center">{{ form.id }}</td>
                    <td>
                        {{ getRequestersName(form) }}
                    </td>
                    <td>{{ getHouseModel(form.house_model) }}</td>
                    <td style="text-align: center">{{ form.location }}</td>
                    <td style="text-align: center">{{ form.block_no }}</td>
                    <td style="text-align: center">{{ form.charging.replace('-',' ').toUpperCase() }}</td>
                    
                    <td>{{ getSampleItems(form) }}</td>
                    <td style="font-weight: bolder; font-size: 14px" class="text-primary">
                        {{ getEstimatedCost(form) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <modal-quotations
     :request-form="showQuotationRequestForm"
     :house-models="houseModels"
     :user="user"
     @reset-request-forms-quotations-modal="resetRequestForm"
    ></modal-quotations>   
  </div>
</template>
<style type="text/css">
    .font-bold {
        font-weight: bolder;
        font-size: 14px;
    }
    #tbl-requests {
        font-size: 12px;
        margin-top: 15px;
        width: 1800px;
    }
    #tbl-requests tr {
        cursor: pointer;
    }
    #tbl-requests td {
         padding: 2px;
    }
    .po-officer-table {
        width: 1700px;
    }
</style>
<script>
    import moment from 'moment'
    import alertify from 'alertify.js'
    import toastr from 'toastr'
    import QuotationModalListComponent from '../../quotation/modal_show_quotations.vue'
    import accounting from 'accounting' 
    export default {
        mounted() {
            this.fetchUsers();
            this.fetchQuotations();
            this.$store.commit('FETCH_OPENED_REQUESTS');
            this.$store.commit('FETCH_APPROVED_QUOTATIONS');
            this.$store.commit('FETCH_REQUISITION_ESTIMATED_COSTS');
        },
        components: {
            'modal-quotations': QuotationModalListComponent
        },
        props: {
            newQuotationForm: {
                type: Object
            },
            quotationForms: {
                type: Array
            },
            quotationItems: {
                type: Array
            },
            houseModels: {
                type: Array
            },
            requestItems: {
                type: Array
            },
            requestForms: {
                type: Array
            },
            user: {
                type: Object
            }
        },
        data(){
            return {
                cboHouseModel: 0,
                users: [],
                currentForm: {},
                /* This showQuotationRequestForm is for request_form watch fetching data */
                showQuotationRequestForm: {},
                quotation_forms: [], quotation_items: []
            }
        },
        computed: {
            opened_requests(){
                return this.$store.getters.opened_requests;
            },
            approved_prs(){
                /* actual approved_dates table in mysql_db */
                return this.$store.getters.approved_prs;
            },
            pr_estimated_costs(){
                return this.$store.getters.pr_estimated_costs;
            }
        },
        methods: {
            hasApprovedQuotations(rf){
                let self = this;
                let rs = _.filter(self.quotation_forms, {
                    request_form_id: Number(rf.id),
                    approved: 1
                });
                if (rs.length) {
                    return false
                }else {
                    return true;                    
                }
            },
            resetRequestForm(){
                this.showQuotationRequestForm = { id: 0 };
            },
            getEstimatedCost(pr){
                let self = this;
                let rs = _.filter(self.pr_estimated_costs, {request_form_id: Number(pr.id)});
                if (rs.length) {
                    let first = _.first(rs);
                    return accounting.formatNumber(first.ESTIMATED_COST, 2);
                }
            },
            loadTitleApprovedDate(form){
                return 'good';
            },
            determineFolder(form){
                let self = this;
                let result = self.ifNotOpened(form);
                return (result === true) ? 'text-success glyphicon glyphicon-folder-close' : 'text-warning glyphicon glyphicon-folder-open';
            },
            ifNotOpened(form){
                let self = this;
                let rs = _.filter(self.opened_requests, { pr_no: Number(form.id)});
                return (rs.length > 0 ) ? false : true;
            },
            getTimeFromNow(datetime){
                return moment(datetime).fromNow();
            },
            showModalQuotations(requestForm){
                let self = this;
                self.showQuotationRequestForm = requestForm;
                $('#modal-quotations').modal('show');
            },
            getQuotations(form){
                let self = this;
                let rs =self.quotation_forms.filter(function(index) {
                    return Number(index.request_form_id) === Number(form.id);
                });
                if (rs.length) {
                    return rs.length;
                };
            },
            fetchQuotations(){
                let self = this;
                self.$http.get('/quotations_all').then((resp) => {
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
                })
            },
            disapproveRequest(){
                let self = this;
                alertify.confirm('Are you sure you want to Dis-approve this request ?', function(){
                    self.approveOrDisapprove(0);
                }, function(){
                    console.log('you canceled the flight!');
                });
            },
            approveRequest(){
                let self = this;
                alertify.confirm('Are you sure you want to approve this request ?', function(){
                    self.approveOrDisapprove(1);
                }, function(){
                    console.log('you canceled the flight!');
                });
            },
            approveOrDisapprove(type){
                let self = this;
                self.$http.post('/approve_pr', {
                    request_form: self.currentForm,
                    type: type,
                    date: moment().format('MMMM DD, YYYY HH:mm:ss')
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        if (json.updated) {
                            self.$emit('change-approval-status', json);
                            let p = (type === 1) ? 'Approved' : 'Disapproved';
                            alertify.log('successfully '+ p);
                        }
                    };
                }, (resp) => {
                    console.log(resp);
                });
            },
            setCurrentForm(form){
                let self = this;
                self.currentForm = form;
            },
            /* createInvitation to quote*/
            /*  purchase officer event */
            createInvitationToQuote(form){
                let self = this;
                self.$emit('set-quotation-form', form);
                $('#modal-create-quotation').modal('show');
            },
            fetchUsers(){
                let self = this;
                self.$http.get('user').then((resp) => {
                    if (resp.status === 200) {
                        let users = resp.body;
                        for (var i = users.length - 1; i >= 0; i--) {
                            self.users.push(users[i]);
                        };
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            getRequestersName(form){
                let self = this;
                let rs = _.filter(self.users, {id: Number(form.requested_by)});
                if (rs.length) {
                    return rs[0].name;
                }else {
                    return 'Not found';
                }
            },
            showItems(form){
                let self = this;
                let rs = _.filter(self.requestItems, { request_form_id: Number(form.id)});
                self.$emit('setmodalitems', rs);
                self.$emit('setmodalform', form);
                $('#modal-requested-items').modal('show');
                self.$http.post('/opened_request', {
                    form: form
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        if (!json.duplicate) {
                            self.$store.commit({
                                'type': 'PUSH_OPENED_REQUESTS',
                                opened_request: json.or
                            });
                        }else {

                        }
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            getSampleItems(form){
                let self = this;
                let rs = _.filter(self.requestItems, { request_form_id: Number(form.id)});
                let namesWithQty = [];
                let item = {};
                rs = _.sortBy(rs, 'qty');
                for (var i = rs.length - 1; i >= 0; i--) {
                    item = rs[i];
                    namesWithQty.push(item.description + ' (' + item.qty + '), ');
                };
                if (namesWithQty.length > 2) {
                    return namesWithQty.slice(0,2).join(' ') + '....';  
                }else {
                    return namesWithQty.join(' ');                    
                }
            },
            getDate(datetime){
                return moment(datetime).format('MMMM DD, YYYY');
            },
            getTime(datetime){
                return moment(datetime).format('hh:mm a');
            },
            getHouseModel(i){
                let self = this;
                let rs = _.filter(self.houseModels, {id: Number(i)});
                if (rs.length) {
                    return rs[0].model;
                }else {
                    return 'not found';
                };
            },
            initJqueryFinance(){
                let self = this;
                let $tbody = $('#tbl-requests');
                $(document).ready(function() {
                    $tbody.find('tbody tr').click(function(event) {
                        $tbody.find('tbody tr').removeClass('text-primary');
                        $(this).addClass('text-primary');
                    });
                });
            }
        },
        watch: {
            'cboHouseModel': function(newVal){
                let self = this;
                if (Number(newVal) > 0) {
                    self.$http.post('/requisition_filter_by_house_model', {
                        house_model: newVal
                    }).then((resp) => {
                        if (resp.status === 200) {
                            let json = resp.body;
                            console.log(json)
                            self.$emit('refresh-request-forms-items', json);
                        }
                    }, (resp) => {
                        console.log(resp);
                    })
                }else {
                    self.$emit('fetch-all-forms-items');
                }
            },
            'newQuotationForm': function(quotationForm){
                let self = this;
                self.quotation_forms = [];
                self.fetchQuotations();
            },
            'requestForms': function(newVal){
                let self = this;
                if (self.user.usertype === 'finance-officer') {
                    self.initJqueryFinance();
                }
            },
            'currentForm': function(requestForm){
                let self = this;
                // alert(requestForm.approved);f
            }
        }
    }
</script>
