<template>
    <div>
        <div>            
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>House Model</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="house in houseModels">
                        <td>{{ house.model }}</td>
                        <td>
                            <a style="cursor: pointer" @click="updateHouseModel(house)">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                        </td>
                        <td>
                            <a style="cursor: pointer" @click="deleteHouseModel(house)">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <panel-update-house 
                :form="currentHouseModel">
            </panel-update-house>
        </div>
    </div>
</template>

<script>
    import updateHouseModelComponent from './panel_update_house_model.vue'
    export default {
        mounted() {
            // console.log('Component mounted.')
            // this.fetch();
        },
        components: {
            'panel-update-house': updateHouseModelComponent
        },
        props: {
            houseModels: {
                type: Array
            }
        },
        data(){
            return {
                // house_models: [],
                currentHouseModel: { model: '' }
            }
        },
        methods: {
            deleteHouseModel(house){
                let self = this;
                console.log('deleting...');
            },
            updateHouseModel(house){
                let self = this;
                $('#modal-update-house').modal('show');
                self.currentHouseModel = house;  
            }
        }
    }
</script>
