<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Requisition Tab</div>

                    <div class="panel-body">
                        <div>

                          <!-- Nav tabs -->
                          <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Form</a></li>
                            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">House Model</a></li>
                            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
                            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
                          </ul>

                          <!-- Tab panes -->
                          <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="home">
                                <form-requisition :houseModels="house_models"></form-requisition>
                                <br>
                                
                            </div>
                            <div role="tabpanel" class="tab-pane" id="profile">
                                <add-house-model @newmodeladded="newHouseModelAdded"></add-house-model>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="messages">...</div>
                            <div role="tabpanel" class="tab-pane" id="settings">...</div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import RequisitionFormComponent from './requisition_form.vue'
    import AddHouseModelComponent from  '../../house-model/add_house_model.vue'
    export default {
        mounted() {
            this.fetchHouseModels();
        },
        components: {
            'form-requisition': RequisitionFormComponent,
            'add-house-model' : AddHouseModelComponent,
        },
        data(){
            return {
                house_models: []
            }
        },
        methods: {
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
