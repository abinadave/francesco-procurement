
<template>
   <div class="modal fade" id="modal-create-quotation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create quotation</h4>
        </div>
        <div class="modal-body">  
            <div>
            	<div class="form-group col-md-4">
				  <label class="control-label">Supplier Name</label>
				  <select class="form-control" v-model="form.supplier_id">
				  		<option :value="0" disabled>Choose Supplier</option>
				  		<option :value="supplier.id" v-for="supplier in suppliers">
				  			{{ supplier.name }}
				  		</option>
				  </select>
				</div>

				<div class="form-group col-md-4">
				  <label class="control-label">Supplier Address</label>
				  <input v-model="form.supplier_address" type="text" class="form-control" disabled>
				</div>
				<div :class="{ 'form-group col-md-4': true, 'has-error': error.canvass_by }">
				  <label class="control-label">Canvass by</label>
				  <input v-model="form.canvass_by" type="text" class="form-control">
				</div>
				<div :class="{ 'form-group col-md-4': true, 'has-error': error.canvass_date }">
				  <label class="control-label">Canvass date</label>
				  <input v-model="form.canvass_date" type="date" class="form-control">
				</div>
				<div class="form-group col-md-4">
				  <label class="control-label">Purchase Request No.</label>
				  <input v-model="requestForm.id" type="text" class="form-control" disabled>
				</div>
				<div class="form-group col-md-4">
				  <label >Requester by</label>
				  <input :value="getRequestersName" type="text" class="form-control" disabled>
				</div>
				<div class="form-group col-md-4">
				  <label class="control-label">House Model</label>
				  <input :value="getHouseModel" type="text" class="form-control" disabled>
				</div>
            </div>
            <div>
                <div class="col-md-8">
                <a @click="addNewItemsToQuote" class="pull-right" style="cursor: pointer"><i class="glyphicon glyphicon-plus"></i> plus</a>
                </div>
                <table id="tbl-quotation-items" class="table table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="70">QTY</th>
                            <th class="text-center" width="90">UNIT</th>
                            <th>DESCRIPTION</th>
                            <th class="text-center" width="100" style="text-align: right">UNIT PRICE</th>
                            <th class="text-right" width="120" style="text-align: right">TOTAL</th>
                            <th width="10"></th>
                        </tr>
                    </thead>
                    <tbody>
                       	<tr v-for="(item, index) in items">
                       		<td>
                       			<input type="number" class="form-control input-item" v-model="item.qty">
                       		</td>
                       		<td>
                       			<input type="text" class="form-control input-item" v-model="item.unit">
                       		</td>
                       		<td>
                       			<input type="text" class="form-control input-item" v-model="item.description">
                       		</td>
                       		<td>
                       			<input type="number" class="form-control input-item" v-model="item.unit_price">
                       		</td>
                       		<td class="text-right">{{ calculateTotal(item) }}</td>
                            <td class="text-center">
                                <i @click="removeItem(index)" class="glyphicon glyphicon-remove" style="cursor: pointer"></i>
                            </td>
                       	</tr>
                    </tbody>
                   
                </table>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button @click="saveThisQuotation" type="button" class="btn btn-primary">Save Quotation</button>
        </div>
      </div>
    </div>
  </div>
</template>
<style type="text/css">
	#tbl-quotation-items {
		font-size: 12px;
	}
	#tbl-quotation-items td, th {
		padding: 2px;
	}
	.input-item {
		height: 25px;
		text-align: center;
	}
</style>
<script>
	import accounting from 'accounting'
	import moment from 'moment'
	import alertify from 'alertify.js'
    export default {
        mounted() {

        },
        props: {
            suppliers: {
            	type: Array
            },
            requestForm: {
        		type: Object
        	},
        	houseModels: {
        		type: Array
        	},
        	users: {
        		type: Array
        	}
        },
        data(){
            return {
                form: {
                	supplier_id: 0,
                	supplier_address: '',
                	canvass_by: '',
                	canvass_date: ''
                },
                error: {
                    canvass_by: '',
                    canvass_date: '',
                    supplier_id: ''
                },
                items: []
            }
        },
        computed: {
        	getRequestersName(){
        		let self = this;
        		let id = self.requestForm.requested_by;
            	let rs = _.filter(self.users, { id: Number(id)});
            	if (rs.length) {
            		return rs[0].name.toUpperCase();
            	}else {
            		return 'none';
            	}
        	},
            getHouseModel(){
            	let self = this;
            	let id = self.requestForm.house_model;
            	let rs = _.filter(self.houseModels, { id: Number(id)});
            	if (rs.length) {
            		return rs[0].model.toUpperCase();
            	}else {
            		return 'none';
            	}
            }
        },
        methods: {
            removeItem(index){
                this.items.splice(index, 1);
            },
            addNewItemsToQuote(){
                let self = this;
                self.items.unshift({qty: '', unit: '', description: '', unit_price: ''});
            },
            saveItems(quotationForm){
                let self = this;
                self.$http.post('/quotation_item', {
                    items: self.items,
                    quotation_form_id: quotationForm.id,
                    request_form_id: self.requestForm.id
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json =resp.body;
                        alertify.alert('Quotation successfully saved');
                        $('#modal-create-quotation').modal('hide');
                    }
                }, (resp) => {
                    console.log(resp);
                })
            },
            clearErrors(){
                let self = this;
                $.each(self.error, function(index, val) {
                    self.error[index] = '';
                });
            },
        	saveThisQuotation(){
        		let self = this;
        		let supplierId = self.form.supplier_id;
                self.clearErrors();
        		if (!Number(supplierId)) {
        			alertify.log('Please Choose a supplier');
        		}else {
        			self.$http.post('/quotation_form', {
	        			supplier_id: self.form.supplier_id,
	        			datetime: moment().format('MMMM DD, YYYY HH:mm:ss'),
	        			request_form_id: self.requestForm.id,
	        			canvass_by: self.form.canvass_by,
	        			canvass_date: self.form.canvass_date
	        		}).then((resp) => {
	        			if (resp.status === 200) {
	        				let json = resp.body;
	        				if (json.id) {
                                self.saveItems(json);
                            };
	        			};
	        		}, (resp) => {
                        if (resp.status === 422) {
                            let json = resp.body;
                            $.each(json, function(index, val) {
                                self.error[index] = val;
                            });
                        }
	        		});
        		}
        	},
          	calculateTotal(item){
          		let self = this;
          		let total = Number(item.qty) * Number(item.unit_price);
          		return accounting.formatNumber(total, 2);
          	}  
        },
        watch: {
            'form.supplier_id': function(newVal){
            	let self =this;
            	let supplierId = newVal;
            	let rs = _.filter(self.suppliers, {id: Number(supplierId)});
            	if (rs.length) {
            		self.form.supplier_address = rs[0].address;
            	}
            },
            'requestForm.id': function(newVal){
            	let self = this;
            	self.items = [];
            	self.$http.post('/get_request_items_by_form', {
            		request_form_id: newVal
            	}).then((resp) => {
            		if (resp.status === 200) {
            			let json = resp.body;
            			if (json.length) {
            				for (var i = json.length - 1; i >= 0; i--) {
            					self.items.push(json[i]);
            				};
            			}
            		}
            	}, (resp) => {
            		console.log(resp);
            	});
            }
        }
    }
</script>
