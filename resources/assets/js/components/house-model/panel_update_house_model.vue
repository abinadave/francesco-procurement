<template>
    <div class="modal fade" id="modal-update-house" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
          </div>
          <div class="modal-body">
                <form @submit.prevent="submitForm" style="padding: 20px">
                    <div :class="{ 'form-group col-md-6': true, 'has-warning': error.model }">
                      <label  class="control-label" for="inputError1">HOUSE MODEL NAME</label>
                      <input v-model="form.model" type="text" class="form-control" id="inputError1">
                    </div><br>
                    <div class="col-mod-6 form-group" style="margin-top: 5px">
                        <button class="btn btn-default" type="submit">SUBMIT</button>                
                    </div>
                </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
        },
        props: {
            form: {
                type: Object
            }
        },
        data(){
            return {
                error: { model: ''}
            }
        },
        methods: {
            submitForm(){
                let self = this;
                self.$http.put('/house_model', self.form).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        console.log(json);
                        // console.log(resp.body);                        
                    }
                }, (resp) => {
                    console.log(resp);
                })
            }
        }
    }
</script>
