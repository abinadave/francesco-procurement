<template>
    <div class="col-md-10">
        <form @submit.prevent="submitForm" style="padding: 20px">
            <div :class="{ 'form-group col-md-6': true, 'has-warning': error.name }">
              <label  class="control-label" for="inputError1">FULLNAME</label>
              <input v-model="form.name" type="text" class="form-control" id="inputError1">
            </div>
            <div :class="{ 'form-group col-md-6': true, 'has-warning': error.email}">
              <label class="control-label" for="inputError1">EMAIL</label>
              <input v-model="form.email" type="text" class="form-control" id="inputError1">
            </div>
            <div :class="{ 'form-group col-md-6': true, 'has-warning': error.password}">
              <label class="control-label" for="inputError1">PASSWORD</label>
              <input v-model="form.password" type="text" class="form-control" id="inputError1">
            </div>
            <div :class="{ 'form-group col-md-6': true, 'has-warning': error.password_confirmation}">
              <label class="control-label" for="inputError1">CONFIRM PASSWORD</label>
              <input v-model="form.password_confirmation" type="text" class="form-control" id="inputError1">
            </div>
            <div :class="{ 'form-group col-md-6': true, 'has-warning': error.usertype }">
              <label class="control-label" for="inputError1">ROLE</label>
              <select v-model="form.usertype" class="form-control col-md-6">
                  <option value="">Select role</option>
                  <option value="finance-officer">Finance Officer</option>
                  <option value="engineer">Site Engineer</option>
                  <option value="procurement-officer">Procurement Officer</option>
                  <option value="purchase-officer">Purchase Officer</option>
              </select>
            </div>
            <div class="form-group col-md-12"><hr>
                  <button @click.prevent="submitForm" type="submit" class="btn btn-block btn-primary">Submit</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log('Component mounted.')
        },
        data(){
            return {
                form: {
                    fullname: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    usertype: ''
                },
                error: {
                    fullname: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    usertype: ''
                }
            }
        },
        methods: {
            clearErrors(){
                let self = this;
                $.each(self.error, function(index, val) {
                     self.error[index] = '';
                });
            },
            clearForm(){
                let self = this;
                $.each(self.form, function(index, val) {
                    self.form[index] = '';
                });
            },
            submitForm(){
                let self = this;
                self.clearErrors();
                self.$http.post('/create_user', self.form).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        if (json.id > 0) {
                            self.$emit('newuseradded', json);
                            self.clearForm();
                        }
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                        let json = resp.body;
                        console.log(json);
                        $.each(json, function(index, val) {
                            self.error[index] = val;
                        });
                    }
                });
            }
        }
    }
</script>
