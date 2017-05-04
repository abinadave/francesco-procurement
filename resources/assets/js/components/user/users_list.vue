<template>
    <div class="col-md-10" style="margin-top: 10px">
        <table class="table table-hover table-condensed table-striped table-users">
        	<thead>
        		<tr>
        			<th>FULLNAME</th>
        			<th>EMAIL</th>
        			<th>USERTYPE/DESIGNATION</th>
        			<th>DATE CREATED</th>
        			<th colspan="2"></th>
        		</tr>
        	</thead>
        	<tbody>
        		<tr v-for="(user, index) in users">
        			<td>{{ user.name }}</td>
        			<td>{{ user.email }}</td>
        			<td>{{ user.usertype }}</td>
        			<td>{{ getDateCreated(user.date) }}</td>
        			<td><i class="glyphicon glyphicon-edit"></i></td>
        			<td><i @click="deleteUser(user, index)" class="glyphicon glyphicon-remove"></i></td>
        		</tr>
        	</tbody>
        </table>
    </div>
</template>
<style type="text/css">
	.table-users {
		font-size: 12px;
	}
	.table-users tbody i {
		cursor: pointer;
	}
</style>
<script>
	import moment from 'moment'
	import alertify from 'alertify.js'
    export default {
        mounted() {
            // console.log('Component mounted.')
        },
        props: {
        	users: {
        		type: Array
        	}
        },
        methods: {
        	deleteUser(user, index){
                let self = this;
                alertify.confirm('are you sure, you want to delete <b class="text-primary">' + user.name.toUpperCase() + '</b> from the Users list ?', function(){
                    let resource = self.$resource('user{/id}');
                    resource.delete({
                        id: user.id
                    }).then((resp) => {
                        if (resp.status === 200) {
                            let json = resp.body;
                            if (json.deleted) {
                                alertify.log('User Account successfully deleted');
                                // let index = _.findIndex(supplier);
                                // self.$emit('removebyindex', index);
                            }
                        }
                    }, (resp) => {

                    });
                });
            },
        	getDateCreated(date){
        		let self = this;
        		return moment(date).format('MMMM DD, YYYY');
        	}
        }
    }
</script>
