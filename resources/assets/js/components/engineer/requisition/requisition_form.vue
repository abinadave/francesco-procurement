<template>
    <div>
        <form @submit.prevent="submitForm" style="padding: 20px">
            <div :class="{ 'form-group col-md-4': true }">
              <label  class="control-label">House Model</label>
              <select class="form-control" v-model="form.house_model">
                  <option>Choose House Model</option>
                  <option v-for="house in houseModels">
                      {{ house.model }}
                  </option>
              </select>
            </div>
            <div :class="{ 'form-group col-md-4': true }">
              <label  class="control-label">Location</label>
              <input v-model="form.location" type="text" class="form-control">
            </div>
            <div :class="{ 'form-group col-md-4': true }">
              <label  class="control-label">Block No.</label>
              <input v-model="form.block_no" type="text" class="form-control">
            </div>
            <div :class="{ 'form-group col-md-4': true }">
              <label  class="control-label">Chargin.</label>
              <select v-model="form.charging" class="form-control">
                  <option value="direct-cost">Direct Cost</option>
                  <option value="indirect-cost">Indirect Cost</option>
                  <option value="tools-equipment">Tools & Equipment</option>
              </select>
            </div>
            <div :class="{ 'form-group col-md-4': true }">
              <label  class="control-label">Checked by</label>
              <input v-model="form.checked_by" type="text" class="form-control">
            </div>
            <div :class="{ 'form-group col-md-10': true }">
                <button @click.prevent="submitForm" type="submit" class="btn btn-primary">Proceed</button>
            </div>
        </form>
        <div class="col-md-12">
            <temporary-items 
             @additem="createTemporaryItem"
             @removeitem="removeTemporaryItem" 
             :items="items"></temporary-items>            
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import TemporaryItems from './temporary_request_items.vue'

    export default {
        mounted() {
            console.log('Component mounted.');
        },
        components: {
            'temporary-items' : TemporaryItems 
        },
        props: {
            houseModels: {
                type: Array
            }
        },
        data(){
            return {
                items: [
                    { qty: 0, unit: '', description: '', unit_price: 0 },
                    { qty: 0, unit: '', description: '', unit_price: 0 },
                    { qty: 0, unit: '', description: '', unit_price: 0 }
                ],
                form: {
                    location: '',
                    block_no: '',
                    charging: '',
                    house_model: '',
                    datetime: '' ,
                    checked_by: ''
                }
            }
        },
        methods: {
            createTemporaryItem(){
                let self = this;
                self.items.push({ qty: 0, unit: '', description: '', unit_price: 0 });
            },
            removeTemporaryItem(i){
                let self = this;
                self.items.splice(i, 1);
            },
            submitForm(){
                let self = this;
                self.form.datetime = moment().format('MMMM DD, YYYY MMMM HH:mm:ss');
                self.$http.post('/requisition', self.form)
                .then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        console.log(json);
                    }
                }, (resp) => {
                    console.log(resp);
                });
            }
        }
    }
</script>
