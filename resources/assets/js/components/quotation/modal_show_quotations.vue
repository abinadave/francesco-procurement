<template>
  <div class="modal fade" id="modal-quotations" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 80%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Quotations</h4>
        </div>
        <div class="modal-body" style="overflow: auto">
            quotation_forms: {{ quotation_forms.length }}
            quotation_items: {{ quotation_items.length }}
            <div v-for="quotationForm in quotation_forms">
                <div class="col-md-4">
                    <div class="form-group has-error">
                      <label class="control-label">Supplier Name</label>
                      <input type="text" class="form-control">
                    </div>
                    <div class="form-group has-error">
                      <label class="control-label">Supplier Address</label>
                      <input type="text" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                <table>
                    <thead>
                       <tr>
                          <th>Supplier </th>
                       </tr>
                    </thead>
                </table>
                </div>
            </div>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        mounted() {
            this.fetchUsers();
        },
        data(){
            return {
                users: [],
                quotation_forms: [],
                quotation_items: []
            }
        },
        props: {
            requestForm: {
                type: Object
            }
        },
        methods: {
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
            }
        },
        watch: {
            'requestForm': function(newVal){
                let self = this;
                self.$http.post('/get_quotation_forms_by_pr_no', {
                    request_form: newVal
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.quotation_forms.length - 1; i >= 0; i--) {
                            self.quotation_forms.unshift(json.quotation_forms[i]);
                        }
                        for (var i = json.quotation_items.length - 1; i >= 0; i--) {
                            self.quotation_items.unshift(json.quotation_items[i]);
                        }
                    }
                }, (resp) => {
                    console.log(resp);
                })
            }
        }
    }
</script>
