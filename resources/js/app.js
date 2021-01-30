require('./bootstrap');

import Vue from 'vue';
import VuePortal from 'portal-vue';
import DayJs from 'DayJs';
import router from './router';
import API from './services/api';

Vue.use(VuePortal, {
	selector: "#portal-header"
});

Vue.prototype.$api = API;
Vue.mixin({
	methods: {
		formatDate (date) {
    		return DayJs(date).format('MMM D, YYYY H:mm A');
  		}
	}
});

Vue.component("ms-home", require('./components/Home.vue').default);

const app = new Vue({
	el: "#app",

	router
});