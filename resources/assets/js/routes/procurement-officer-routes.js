import VueRouter from 'vue-router'
Vue.use(VueRouter);

import { store } from '../store/store'
import PurchaseRequestComponent from '../components/purchase-officer/purchase_request.vue'
import POComponent from '../components/po/po_list.vue'
import QuotationComponent from '../components/quotation/quotation.vue'
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
    ,
    { 
      path: '/purchase-order', 
      component: POComponent
    },
    {
      path: '/quotation',
      component: QuotationComponent
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

