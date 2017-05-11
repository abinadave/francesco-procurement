
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
require('./bootstrap');
import VueResource from 'vue-resource'
import alertify from 'alertify.js'
Vue.use(VueResource);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#csrf-token').getAttribute('content');

// import
Vue.http.get('/user_details').then((resp) => {
    if (resp.status === 200) {
        let json = resp.body;
        if (json.usertype === 'engineer' && json.id > 0) {
        	alertify.success('Welcome back Engineer ' + json.name);
            require('./routes/engineer-routes');
        }else if(json.usertype === 'purchase-officer'){
        	alertify.success('Welcome back Purchase Officer: ' + json.name);
        	require('./routes/purchase-officer-routes');
        }else if(json.usertype === 'finance-officer'){
            alertify.success('Welcome back Finance Officer: ' + json.name);
            require('./routes/finance-officer-routes');
        }else {
            require('./routes/admin-routes');
        }
    }
}, (resp) => {

});


