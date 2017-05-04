<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Users Management</div>

                    <div class="panel-body">
                    	<div>
							  <!-- Nav tabs -->
							  <ul class="nav nav-tabs" role="tablist">
							    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">User Accounts</a></li>
							    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Add User</a></li>
							  </ul>
							  <!-- Tab panes -->
							  <div class="tab-content">
							    <div role="tabpanel" class="tab-pane active" id="home">
								    <users-list :users="users"></users-list>
							    </div>
							    <div role="tabpanel" class="tab-pane" id="profile">
							    	<add-user @newuseradded="createNewUser"></add-user>
							    </div>
							  </div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import UserAccountListComponent from './users_list.vue'
	import AddUserComponent from './add_user.vue'
    export default {
        mounted() {
            this.fetch();
        },
        components: {
        	'add-user': AddUserComponent,
        	'users-list': UserAccountListComponent
        },
        data(){
        	return {
        		users: []
        	}
        },
        methods: {
        	createNewUser(json){
        		let self = this;
        		self.users.push(json);
        	},
        	fetch(){
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
        }
    }
</script>
