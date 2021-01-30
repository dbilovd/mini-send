<template>
	<div>
	    <div class="flex flex-col">
	        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
	            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
	                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
	                    <table class="min-w-full divide-y divide-gray-200">
	                        <thead class="bg-gray-50">
	                            <tr>
	                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
	                                    Address
	                                </th>
	                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
	                                    Subject
	                                </th>
	                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
	                                    Status
	                                </th>
	                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
	                                    Updated
	                                </th>
	                                <th scope="col" class="relative px-6 py-3">
	                                    <span class="sr-only">...</span>
	                                </th>
	                            </tr>
	                        </thead>
	                        <tbody class="bg-white divide-y divide-gray-200">
	                            <tr v-for="message in messages" :key="message.messageId">
	                                <td class="w-1/5 px-6 py-4 whitespace-nowrap">
	                                    <div class="flex items-center">
	                                        <div class="">
	                                            <div class="text-sm font-medium text-gray-900">
	                                                {{ message.recipientEmail }}
	                                            </div>
	                                            <div class="text-sm text-gray-500">
	                                            	From: {{ message.senderEmail || "You" }}
	                                            </div>
	                                        </div>
	                                    </div>
	                                </td>
	                                <td class="w-2/5 px-6 py-4 whitespace-nowrap">
	                                    <div class="text-sm text-gray-900 font-medium">
	                                    	{{ message.subject || "No Subject" }}
	                                    </div>
	                                    <div class="text-sm text-gray-500">
	                                    	{{ messagePreview(message) }}
	                                    </div>
	                                </td>
	                                <td class="w-1/10 px-6 py-4 whitespace-nowrap">
	                                	<message-status :status="message.status"></message-status>
	                                </td>
	                                <td class="w-2/10 px-6 py-4 whitespace-nowrap text-sm text-gray-500">
	                                    {{ formatDate(message.updatedAt) }}
	                                </td>
	                                <td class="w-1/10 px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
	                                    <router-link :to="`/messages/${message.messageId}`" class="text-indigo-600 hover:text-indigo-900">
	                                    	View
	                                    </router-link>
	                                </td>
	                            </tr>
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script type="text/javascript">
import DayJs from 'dayjs';
import API from './../services/api';
import MessageStatus from './MessageStatus';

const Messages = {
	components: {
		'message-status': MessageStatus
	},

	data () {
		return {
			isLoading: false,
			messages: []
		}
	},

	created () {
		this.fetchMessages();
	},

	methods: {
		formatDate (date) {
    		return DayJs(date).format('MMM D, YYYY H:mm A');
  		},

		messagePreview (message) {
    		return message.bodyAsText ? message.bodyAsText
    			: "HTML Message";
  		},

		fetchMessages () {
			this.isLoading = true;
			API.fetchMessages()
				.then((messages) => {
					this.isLoading = false;
					this.messages = messages;
				})
				.catch((err) => {
					this.isLoading = false;
					console.log("Ann error occurred:", err);
				});
		}
	}
};

export default Messages;

</script>
