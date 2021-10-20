// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store';
import VueCookies from 'vue-cookies';
import HighchartsVue from "highcharts-vue";

Vue.use(HighchartsVue);
Vue.use(VueCookies);
Vue.config.productionTip = false

/* eslint-disable no-new */


new Vue({
  store,
  router,
  render: h => h(App)
}).$mount('#app')

/* new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
 */