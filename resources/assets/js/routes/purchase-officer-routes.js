import VueRouter from 'vue-router'
Vue.use(VueRouter);

import PurchaseRequestComponent from '../components/purchase-officer/purchase_request.vue'
import QuotationListComponent from '../components/purchase-officer/quote/quotation_list.vue'
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
    {
      path: '/quotation-list',
      component: QuotationListComponent
    }
  ]
});

const app = new Vue({
  	router
}).$mount('#app');

