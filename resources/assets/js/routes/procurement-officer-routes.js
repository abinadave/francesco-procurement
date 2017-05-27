import VueRouter from 'vue-router'
Vue.use(VueRouter);

import PurchaseRequestComponent from '../components/purchase-officer/purchase_request.vue'
import POComponent from '../components/po/po_list.vue'
window.router = new VueRouter({
  routes: [
    { 
      path: '/', 
      redirect: '/purchase-request' 
    },
    { 
      path: '/purchase-request', 
      component: PurchaseRequestComponent
    },
    ,
    { 
      path: '/purchase-order', 
      component: POComponent
    }
  ]
});

const app = new Vue({
  	router
}).$mount('#app');

