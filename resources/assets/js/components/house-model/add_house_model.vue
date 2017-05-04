<template>
    <div class="col-md-10">
        <form @submit.prevent="submitForm" style="padding: 20px">
            <div :class="{ 'form-group col-md-6': true, 'has-warning': error.model }">
              <label  class="control-label" for="inputError1">HOUSE MODEL NAME</label>
              <input v-model="form.model" type="text" class="form-control" id="inputError1">
            </div><br>
            <div class="col-mod-6 form-group" style="margin-top: 5px">
	            <button class="btn btn-defauolt">SUBMIT</button>            	
            </div>
        </form>    
    </div>
</template>

<script>
	import alertify from 'alertify.js'
    export default {
        mounted() {
            // console.log('Component mounted.')
        },
        data(){
        	return {
        		form: {
        			model: ''
        		},
        		error: {
        			model: ''
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
        	submitForm(){
        		let self = this;
        		self.clearErrors();
        		self.$http.post('/house_model', self.form).then((resp) => {
        			if (resp.status === 200) {
        				let json = resp.body;
        				if (json.id > 0) {
        					self.form.model = '';
        					self.$emit('newmodeladded', json);
        					alertify.success('House Model successfully created');
        				}
        			}
        		}, (resp) => {
        			if (resp.status === 422) {
        				let json = resp.body;
        				$.each(json, function(index, val) {
        					self.error[index] = val;
        				});
        			}
        		})
        	}
        }
    }
</script>
