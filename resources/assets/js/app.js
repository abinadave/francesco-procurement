
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
require('./bootstrap');
import VueResource from 'vue-resource'
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
            require('./routes/engineer-routes');
        }else {
            require('./routes/admin-routes');
        }
    }
}, (resp) => {

});


