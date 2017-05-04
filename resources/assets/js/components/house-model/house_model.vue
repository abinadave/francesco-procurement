<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-info">
                    <div class="panel-heading">House Model</div>

                    <div class="panel-body">
                        <div>
                              <!-- Nav tabs -->
                              <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">List of House Model</a></li>
                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Add House Model</a></li>
                              </ul>
                              <!-- Tab panes -->
                              <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <house-model-list :house-models="house_models"></house-model-list>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="profile">
                                    <add-house-model @newmodeladded="createHouseModel"></add-house-model>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import AddHouseModelComponent from './add_house_model.vue'
    import HouseModelListComponent from './house_model_list.vue'
    export default {
        mounted() {
            this.fetch();
        },
        components: {
            'add-house-model': AddHouseModelComponent,
            'house-model-list': HouseModelListComponent
        },
        data(){
            return {
                house_models: []
            }
        },
        methods: {
            fetch(){
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
            createHouseModel(json){
                let self = this;
                self.house_models.push(json);
            }
        }
    }
</script>
