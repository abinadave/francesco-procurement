import VueRouter from 'vue-router'
import Vuex from 'vuex'

Vue.use(VueRouter);

import { store } from '../store/store' 

import PurchaseRequestComponent from '../components/purchase-officer/purchase_request.vue'
import QuotationListComponent from '../components/purchase-officer/quote/quotation_list.vue'
import SupplierComponent from '../components/supplier/supplier.vue'
import NotificationComponent from '../components/notification/notification.vue'

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
    },
    {
      path: '/supplier',
      component: SupplierComponent
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

