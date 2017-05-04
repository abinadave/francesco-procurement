import VueRouter from 'vue-router'
Vue.use(VueRouter);

import SupplierComponent from '../components/supplier/supplier.vue'
import UserAccountComponent from '../components/user/user.vue'
import HouseModelComponent from '../components/house-model/house_model.vue'

window.router = new VueRouter({
  routes: [
    { 
      path: '/', 
      redirect: '/supplier' 
    },
    { 
      path: '/supplier', 
      component: SupplierComponent
    },
    { 
      path: '/users', 
      component: UserAccountComponent
    },
    {
      path: '/house-model',
      component: HouseModelComponent
    }
    
  ]
});

const app = new Vue({
  	router
}).$mount('#app');