<template>
    <div>

        <form @submit.prevent="submitForm" class="col-md-8">
            <button type="submit" @click.prevent="submitForm" class="btn btn-success pull-right" style="margin-top: 30px">Add supplier <i class="glyphicon glyphicon-road"></i></button>
            <h4 class="page-header">Add new Supplier here</h4>
            <div :class="{ 'form-group col-md-5': true, 'has-error': error.name }">
              <label class="control-label" for="inputError1">Supplier Name</label>
              <input v-model="form.name" type="text" class="form-control" id="inputError1">
            </div>
             <div :class="{ 'form-group col-md-5': true, 'has-error': error.address }">
              <label class="control-label" for="inputError1">Supplier Address</label>
              <input v-model="form.address" type="text" class="form-control" id="inputError1">
            </div>
             <div class="form-group col-md-5">
              <label class="control-label" for="inputError1">Supplier Contact Number</label>
              <input v-model="form.contact" type="text" class="form-control" id="inputError1">
            </div>
            <div class="form-group col-md-5">
            </div>
            <br>
        </form>
    </div>
</template>

<script>
    import alertify from 'alertify.js'
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            suppliers: {
                type: Array
            }
        },
        data(){
            return {
                form: {
                    name: '',
                    address: '',
                    contact: ''
                },
                error: {
                    name: '',
                    address: '',
                    contact: ''
                }
            }
        },
        methods: {
            clearErrors(){
                let self = this;
                let keys =  _.keys(self.error);
                keys.forEach(function(index){
                    self.error[index] = '';
                });
            },
            showErrors(json){
                let self = this;
                $.each(json, function(index, val) {
                    self.error[index] = val; 
                }); 
            },
            submitForm(){
                let self = this;
                self.clearErrors();
                self.$http.post('/supplier', self.form).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        if (json.id > 0) {
                            alertify.success('Supplier successfully added from the supplier list');
                            self.$emit('newsupplieradded', json);
                            self.clearForm();
                        }
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                        let json = resp.body;
                        self.showErrors(json);
                        let keys = _.keys(json);
                        alertify.alert('Incomplete data for: ['+keys.join(', ')+'] please check the form and try again!');
                    }
                });
            },
            clearForm(){
                let self = this;
                $.each(self.form, function(index, val) {
                     self.form[index] = '';
                });
            }
        }
    }
</script>
