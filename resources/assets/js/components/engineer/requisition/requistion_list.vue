<template>
  <div>
    <div>
        <div v-if="user.usertype === 'finance-officer'">
            <button @click="approveRequest" class="btn btn-success btn-sm">Approve <i class="glyphicon glyphicon-thumbs-up"></i></button>
            <button @click="disapproveRequest" class="btn btn-danger btn-sm">Dis-approve <i class="glyphicon glyphicon-thumbs-down"></i></button>
        </div>
        quotation_forms: {{ quotation_forms.length }}
        quotation_items: {{ quotation_items.length }}
        <table :class="{ 'po-officer-table' : user.usertype === 'purchase-officer' }" id="tbl-requests" class="table table-hover table-condensed table-bordered">
            <thead>
                <tr>
                    <th width="30"></th>
                    <th class="text-center" width="70">APPROVED</th>
                    <th class="text-center" width="80">QUOTATIONS</th>
                    <th width="100" v-show="user.usertype === 'purchase-officer'"></th>
                    <th width="60" class="text-center">PR NO.</th>
                    <th v-show="user.usertype === 'purchase-officer'">REQUESTED BY</th>
                    <th>HOUSE MODEL</th>
                    <th style="text-align: center">LOCATION</th>
                    <th width="90"style="text-align: center">BLOCK NO.</th>
                    <th width="140" style="text-align: center">CHARGING</th>
                    <th width="130" style="text-align: center">DATE</th>
                    <th width="100" style="text-align: center">TIME</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                <tr :value="form" @click="setCurrentForm(form)" v-for="form in requestForms">
                    <td class="text-center">
                        <i @click="showItems(form)" style="cursor: pointer" class="glyphicon glyphicon-folder-open text-primary"></i>
                    </td>
                    <td class="text-center">
                        <span v-if="form.approved === 1">
                            <i class="text-primary glyphicon glyphicon-ok-sign"></i>
                        </span>
                    </td>
                    <td class="text-center">{{ getQuotations(form) }}</td>
                    <td class="text-center" v-show="user.usertype === 'purchase-officer'">
                        <a v-if="form.approved === 1"@click="createInvitationToQuote(form)" style="cursor: pointer">Add Quotation</a>
                    </td>
                    <td style="text-align: center">{{ form.id }}</td>
                    <td v-show="user.usertype === 'purchase-officer'">
                        {{ getRequestersName(form) }}
                    </td>
                    <td>{{ getHouseModel(form.house_model) }}</td>
                    <td style="text-align: center">{{ form.location }}</td>
                    <td style="text-align: center">{{ form.block_no }}</td>
                    <td style="text-align: center">{{ form.charging.replace('-',' ').toUpperCase() }}</td>
                    <td style="text-align: center">{{ getDate(form.datetime) }}</td>
                    <td style="text-align: center">{{ getTime(form.datetime) }}</td>
                    <td>{{ getSampleItems(form) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
       
  </div>
</template>
<style type="text/css">
    #tbl-requests {
        font-size: 12px;
        margin-top: 15px;
    }
    #tbl-requests tr {
        cursor: pointer;
    }
    #tbl-requests td {
         padding: 2px;
    }
    .po-officer-table {
        width: 1500px;
    }
</style>
<script>
    import moment from 'moment'
    import alertify from 'alertify.js'
    import toastr from 'toastr'
    export default {
        mounted() {
            this.fetchUsers();
            this.fetchQuotations();
        },
        props: {
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
                users: [],
                currentForm: {},
                quotation_forms: [], quotation_items: []
            }
        },
        methods: {
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
                return namesWithQty.join(' ');
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
            'requestForms': function(newVal){
                let self = this;
                if (self.user.usertype === 'finance-officer') {
                    self.initJqueryFinance();
                }
            },
            'currentForm': function(requestForm){
                let self = this;
                // alert(requestForm.approved);
            }
        }
    }
</script>
