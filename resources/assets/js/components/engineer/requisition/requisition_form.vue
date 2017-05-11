<template>
    <div class="col-md-7">
        <form @submit.prevent="submitForm" style="padding: 20px">
            <div :class="{ 'form-group col-md-4': true }">
              <label  class="control-label">House Model</label>
              <select class="form-control" v-model="form.house_model">
                  <option :value="0">Choose House Model</option>
                  <option :value="house.id" v-for="house in houseModels">
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
                :items="items">
            </temporary-items>            
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import TemporaryItems from './temporary_request_items.vue'
    import alertify from 'alertify.js'
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
            getValidItems(){
                let self = this;
                return self.items.filter(function(item){
                    return Number(item.qty) > 0 &&
                           item.unit !== '' &&
                           item.description !== '' &&
                           Number(item.unit_price) > 0;
                }).length;
            },
            submitForm(){
                let self = this;
                self.form.datetime = moment().format('MMMM DD, YYYY MMMM HH:mm:ss');
                let result = self.validateItems();
                // alert(result);
                if (result === true) {
                    self.$http.post('/requisition', self.form)
                    .then((resp) => {
                        if (resp.status === 200) {
                            let json = resp.body;
                            if (json.id > 0) {
                                self.$emit('newrequestformadded', json);
                                self.saveItems(json);
                            }
                        }
                    }, (resp) => {
                        console.log(resp);
                    });
                }
            },
            clearForms(){
                let self = this;
                $.each(self.form, function(index, val) {
                     self.form[index] = '';
                });
                self.items = [];
                for (var i = 0; i < 3; i++) {
                    self.items.push(
                        { qty: 0, unit: '', description: '', unit_price: 0 }
                    );
                };
            },
            validateItems(){
                let self = this;
                let errors = 0;
                let validItems = self.getValidItems();
                // alert(validItems);
                if (validItems === 0) {
                    /* no valid items */
                    alertify.alert('Please input requested item on the table.');
                    return false;
                }else {
                    return true;
                }
            },
            saveItems(requestForm){
                let self = this;
                self.$http.post('/request_item', {
                    items: self.items,
                    request_form: requestForm
                })
                .then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        if (json.length === self.items.length) {
                            for (var i = json.length - 1; i >= 0; i--) {
                                self.$emit('newrequestitemadded', json[i]);
                            };
                            alertify.alert('Request Successfully sent');
                            self.clearForms();
                        }
                    }
                }, (resp) => {
                    console.log(resp);
                });
            }
        }
    }
</script>
