<template>
    <div class="container">
        <div>

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Supplier List</a>
            </li>
            <li role="presentation">
                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Add supplier</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <supplier-list @removebyindex="deleteSupplierFromChild" :suppliers="suppliers"></supplier-list>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
                <supplier-form @newsupplieradded="createNewSupplier"></supplier-form>
            </div>
           
          </div>

        </div>
    </div>
</template>

<script>
    import supplierListComponent from './supplier_list.vue'
    import supplierAddComponent  from './create_supplier.vue'
    export default {
        mounted() {
            this.fetchSupplier();
        },
        data(){
            return {
                suppliers: []
            }
        },
        components: {
            'supplier-list': supplierListComponent,
            'supplier-form': supplierAddComponent
        },
        methods: {
            deleteSupplierFromChild(index){
                let self = this;
                self.suppliers.splice(index, 1);
            },
            createNewSupplier(json){
                let self = this;
                self.suppliers.unshift(json);
            },
            fetchSupplier(){
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
