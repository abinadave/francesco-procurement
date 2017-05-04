<template>
    <div id="supplier-list" class="col-md-8">
      	<!-- List of supplier {{ suppliers.length }} -->
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Supplier name</th>
                    <th>Supplier Address</th>
                    <th>Supplier Contact #</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(supplier, index) in suppliers">
                    <td>{{ supplier.name }}</td>
                    <td>{{ supplier.address }}</td>
                    <td>{{ supplier.contact }}</td>
                    <td><i class="glyphicon glyphicon-edit"></i></td>
                    <td><i @click="deleteSupplier(supplier, index)" class="glyphicon glyphicon-remove"></i></td>
                </tr>
                <tr v-show="suppliers.length === 0">
                   <td colspan="5">No data was found</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style type="text/css">
	#supplier-list {
		margin-top: 20px;
		padding: 20px;
	}
  #supplier-list table {
      font-size: 12px;
  }
  #supplier-list table th, td {
      padding: 2px;
  }
  #supplier-list table td i {
      cursor: pointer;
      text-align: center;
  }
</style>
<script>
    import alertify from 'alertify.js'
    export default {
        mounted() {
         	// console.log('Component mounted.')
        },
        props: {
            suppliers: {
                type: Array
            }
        },
        data(){
            return {
                // whileLoading: true
            }
        },  
        methods: {
            getContactNumber(contactNo){
                let self = this;
                if (contactNo == '') {
                    return 'No contact # specified';
                }else {
                    return contactNo;
                }
            },
            deleteSupplier(supplier, index){
                let self = this;
                alertify.confirm('are you sure, you want to delete <b class="text-primary">' + supplier.name.toUpperCase() + '</b> from the Supplier list ?', function(){
                    let resource = self.$resource('supplier{/id}');
                    resource.delete({
                        id: supplier.id
                    }).then((resp) => {
                        if (resp.status === 200) {
                            let json = resp.body;
                            if (json.deleted) {
                                alertify.log('Supplier successfully deleted');
                                // let index = _.findIndex(supplier);
                                self.$emit('removebyindex', index);
                            }
                        }
                    }, (resp) => {

                    });
                });
                
            }
        },
        watch: {
            'suppliers': function(newVal){
                let self = this;
                
            }
        }
    }
</script>
