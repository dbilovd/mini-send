require('./bootstrap');

import Vue from 'vue';
import router from './router';

Vue.component("ms-home", require('./components/Home.vue').default);

const app = new Vue({
	el: "#app",

	router,

	data: {
		message: "Hello!"
	}
});