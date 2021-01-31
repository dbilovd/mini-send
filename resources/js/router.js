import Vue from 'vue';
import VueRouter from "vue-router";

Vue.use(VueRouter);

const SendMessage = require('./components/SendMessage').default;
const Messages = require('./components/Messages').default;
const MessageDetails = require('./components/MessageDetails').default;
const Dashboard = require('./components/Dashboard').default;

const routes = [
	{
		path: '/',
		component: Dashboard
	},
	{
		path: '/messages',
		component: Messages
	},
	{
		path: '/messages/:messageId',
		component: MessageDetails
	},
	{
		path: '/messages/to/:recipient',
		component: Messages,
		props: route => route.params
	},
	{
		path: '/send-message',
		component: SendMessage
	}
];

const router = new VueRouter({
	routes
});

export default router;
