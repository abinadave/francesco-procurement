import VueRouter from 'vue-router'
Vue.use(VueRouter);

import ExampleComponent from '../components/example.vue'
import RequisitionComponent from '../components/engineer/requisition/requisition.vue'

window.router = new VueRouter({
  routes: [
    { 
      path: '/', 
      redirect: '/landing-page' 
    },
    { 
      path: '/requisition', 
      component: RequisitionComponent
    },
    { 
      path: '/landing-page', 
      component: ExampleComponent
    }
  ]
});

const app = new Vue({
  	router
}).$mount('#app');

