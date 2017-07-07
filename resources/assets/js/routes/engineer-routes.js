import VueRouter from 'vue-router'
import Vuex from 'vuex'

Vue.use(VueRouter);

import { store } from '../store/store'  

import ExampleComponent from '../components/example.vue'
import RequisitionComponent from '../components/engineer/requisition/requisition.vue'
import NotificationComponent from '../components/notification/notification.vue'

window.router = new VueRouter({
  routes: [
    { 
      path: '/', 
      redirect: '/requisition' 
    },
    { 
      path: '/requisition', 
      component: RequisitionComponent
    },
    {
      path: '/notification',
      component: NotificationComponent
    }
  ]
});

const app = new Vue({
  	router,
    store
}).$mount('#app');

