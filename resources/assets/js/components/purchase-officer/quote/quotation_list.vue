<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">Quotation Component</div>
                    <div class="panel-body">
                        <div>
                          <!-- Nav tabs -->
                          <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">My Quotations </a></li>
                            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">List of all Quotations</a></li>
                          </ul>
                          <!-- Tab panes -->
                          <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="home">
                                <my-quotations :suppliers="suppliers"></my-quotations>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="profile">...</div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import myQuotationsComponent from './my_quotations.vue'
    export default {
        mounted() {
            // console.log('Component mounted.');
            this.fetchSuppliers();
        },
        components: {
            'my-quotations': myQuotationsComponent
        },
        data(){
            return {
                suppliers: []
            }
        },
        methods: {
            fetchSuppliers(){
                let self = this;
                self.$http.get('/supplier').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.suppliers.push(json[i]);
                        }

                    }
                }, (resp) => {
                    console.log(resp);
                });
            }
        }
    }
</script>
