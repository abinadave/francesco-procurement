<template>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Requisition Tab</div>

                    <div class="panel-body">
                        <div>

                          <!-- Nav tabs -->
                          <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Requisitions</a></li>
                            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Create Requisition</a></li>
                            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">House Model</a></li>
                            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
                          </ul>

                          <!-- Tab panes -->
                          <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="home">
                                <requisition-list
                                :user="user"
                                @setmodalitems="setModalRequestedItems"
                                @setmodalform="setModalRequesterForm"
                                :request-forms="request_forms"
                                :request-items="request_items"
                                :house-models="house_models"
                                ></requisition-list>
                                
                            </div>
                            <div role="tabpanel" class="tab-pane" id="profile">
                                <form-requisition
                                 @newrequestformadded="createNewRequestForm"
                                 @newrequestitemadded="createNewRequestItem"
                                 :houseModels="house_models"></form-requisition>
                                <br>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="messages">
                                <div class="col-md-8">
                                    <add-house-model @newmodeladded="newHouseModelAdded"></add-house-model>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="settings">...</div>
                          </div>
                        </div>
                    </div>
                </div>
                <modal-requested-items
                 :items="modalRequestedItems"
                 :form="modalRequestersForm"
                ></modal-requested-items>
            </div>
</template>

<script>
    import RequisitionFormComponent from './requisition_form.vue'
    import RequisitionListComponent from './requistion_list.vue'
    import AddHouseModelComponent from  '../../house-model/add_house_model.vue'
    import RequestItemsModal from './modal_requested_items.vue'
    export default {
        mounted() {
            this.fetchRequisitions();
            this.fetchHouseModels();
        },
        props: {
            user: {
                type: Object
            }
        },
        components: {
            'form-requisition': RequisitionFormComponent,
            'add-house-model' : AddHouseModelComponent,
            'requisition-list': RequisitionListComponent,
            'modal-requested-items': RequestItemsModal
        },
        data(){
            return {
                house_models: [],
                request_forms: [],
                request_items: [],
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
                }
            }
        },
        methods: {
            setModalRequesterForm(form){
                let self = this;
                self.modalRequestersForm = form;
            },
            setModalRequestedItems(rs){
                let self = this;
                self.modalRequestedItems = rs;
            },
            createNewRequestItem(model){
                this.request_items.push(model);
            },
            createNewRequestForm(json){
                let self = this;
                self.request_forms.unshift(json);
            },
            fetchRequisitions(){
                let self = this;
                self.$http.get('get_my_requisitions').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.request_forms.push(json[i]);
                        };
                    }
                    self.fetchRequestItems();
                }, (resp) => {
                    console.log(resp);
                });
            },
            fetchRequestItems(){
                let self = this;
                let ids = _.map(self.request_forms, 'id');
                self.$http.post('get_request_items', {
                    ids: ids
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.request_items.push(json[i]);
                        };
                    };
                }, (resp) => {
                    console.log(resp);
                });
            },
            newHouseModelAdded(json){
                let self = this;
                self.house_models.push(json);
            },
            fetchHouseModels(){
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
        },

    }
</script>
