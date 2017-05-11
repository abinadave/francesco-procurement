<template>
    <div>
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">Purchase Request 
                </div>
                <div class="panel-body">
                    <div>
                        <div style="overflow: auto"> 
                            <request-list
                                @change-approval-status="changeApprovalStatus"
                                @setmodalitems="setModalRequestedItems"
                                @setmodalform="setModalRequesterForm"
                                @set-quotation-form="setQuotationForm"
                                :house-models="house_models"
                                :request-items="request_items"
                                :request-forms="request_forms"
                                :user="user"
                            ></request-list>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-requested-items
         :items="modalRequestedItems"
         :form="modalRequestersForm"
        ></modal-requested-items>
        <modal-create-quotation
            :house-models="house_models"
            :suppliers="suppliers"
            :request-form="modalRequestersForm"
            :users="users"
        ></modal-create-quotation>
    </div>        
</template>

<script>
    import PurchaseRequestListComponent from '../engineer/requisition/requistion_list.vue'
    import RequestItemsModal from '../engineer/requisition/modal_requested_items.vue'
    import CreateQuotationComponent from './quote/create_quotation.vue'
    export default {
        props: {
            user: {
                type: Object
            }
        },
        mounted() {
            this.fetchAllFormsAndItems();
            this.fetchHouseModels();
            this.fetchSuppliers();
            this.fetchUsers();
            // this.fetchQuotations();
        },
        components: {
            'request-list': PurchaseRequestListComponent,
            'modal-requested-items': RequestItemsModal,
            'modal-create-quotation': CreateQuotationComponent
        },
        data(){
            return {
                request_forms: [], request_items: [],
                house_models: [],
                modalRequestedItems: [],
                modalRequestersForm: {
                    house_model: '',
                    location: '',
                    block_no: '',
                    charging: '',
                    requested_by: '',
                    checked_by: '',
                    datetime: '',
                    approved: 0,
                    accepted: 0
                },
                suppliers: [], users: []
            }
        },
        methods: {
            changeApprovalStatus(json){
                let self = this;
                let rsPr = _.filter(self.request_forms, {id: Number(json.model.id)});
                if (rsPr.length) {
                    let model = rsPr[0];
                    model.approved = Number(json.type);
                };
            },
            fetchUsers(){
                let self = this;
                self.$http.get('/user').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.users.push(json[i]);
                        }
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },  
            setQuotationForm(form){
                let self = this;
                self.modalRequestersForm = form;
            },
            fetchSuppliers(){
                let self = this;
                self.$http.get('/supplier').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.suppliers.push(json[i]);
                        };
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            setModalRequesterForm(form){
                let self = this;
                self.modalRequestersForm = form;
            },
            setModalRequestedItems(rs){
                let self = this;
                self.modalRequestedItems = rs;
            },
            fetchHouseModels() {
                let self = this;
                self.$http.get('/house_model').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.house_models.push(json[i]);
                        };
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            fetchAllFormsAndItems(){
                let self = this;
                self.$http.get('/request_forms_and_items').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.request_items.length - 1; i >= 0; i--) {
                            self.request_items.push(json.request_items[i]);
                        };
                        for (var i = json.request_forms.length - 1; i >= 0; i--) {
                            self.request_forms.push(json.request_forms[i]);
                        };
                    }
                }, (resp) => {

                });
            }
        },
        watch: {
            
        }
    }
</script>
