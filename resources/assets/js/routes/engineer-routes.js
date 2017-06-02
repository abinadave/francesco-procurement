import VueRouter from 'vue-router'
import Vuex from 'vuex'

Vue.use(VueRouter);
Vue.use(Vuex)

import { store } from '../store/store'  

import ExampleComponent from '../components/example.vue'
import RequisitionComponent from '../components/engineer/requisition/requisition.vue'

window.router = new VueRouter({
  routes: [
    { 
      path: '/', 
      redirect: '/requisition' 
    },
    { 
      path: '/requisition', 
      component: RequisitionComponent
    }
  ]
});

const app = new Vue({
  	router,
    store
}).$mount('#app');

