<template>
    <div>
        <portal to="header">
            <div class="flex flex-row justify-between items-center">
                <h1 class="text-3xl font-bold leading-tight text-gray-900">
                    Messages
                    <span v-if="recipient" class="ml-1">
                        To {{ recipient }}
                    </span>
                </h1>
                <div class="items-right">
                    <button type="button" @click.prevent="toggleFormForFiltering"
                    	class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
                		:class="{
                			'text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500': showingFilters,
                			'text-gray-800 bg-gray-200 hover:bg-gray-300 focus:ring-gray-300': !showingFilters
                		}">
                		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
						</svg>
                    </button>
                </div>
            </div>
        </portal>

        <div class="flex flex-col">

            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div v-if="showingFilters" 
                    	class="mb-10 shadow overflow-hidden bg-white border-b border-gray-200 rounded-lg">
		                <div class="flex flex-row items-end justify-start p-6">
                            <div class="w-1/4 flex flex-col pr-2"
                                :class="{ 'pointer-events-none': recipient }">
                                <label class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Recipient</label>
                                <input v-if="!recipient" 
                                    type="email" name="recipient" placeholder="recipient@gmail.com"
                                    v-model="filteringOptions.recipientEmail" 
                                    class="block form-input rounded border border-gray-200"/>
                                <h3 v-if="recipient" class="text-gray-800 font-bold text-xl py-2">
                                    {{ recipient }}
                                </h3>
                            </div>
                            <div class="w-1/4 flex flex-col pr-2">
                                <label class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Sender</label>
                                <input type="email" name="sender" placeholder="sender@gmail.com"
                                    v-model="filteringOptions.senderEmail" 
                                    class="block form-input rounded border border-gray-200" />
                            </div>
		                    <div class="w-1/4 flex flex-col pr-2">
		                        <label class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Subject</label>
		                        <input type="text" name="search" placeholder="Hello" 
		                        	v-model="filteringOptions.search"
		                        	class="block form-input rounded border border-gray-200" />
		                    </div>
		                    <div class="w-1/4 flex flex-col pr-2">
		                        <button  type="button" @click.prevent="filterMessages"
		                        	class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
		                    		:class="{ 'opacity-50': isLoading }"
		                    		:disabled="isLoading">
		                        	Search
		                        </button>
		                    </div>
		                </div>
		            </div>

                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    	<div v-if="messages.length <= 0" class="text-center py-24">
			                <h1 class="text-3xl font-bold leading-tight text-gray-700 mb-12">
			                    {{ isLoading ? "Fetching Messages ..." : "No Messages Found" }}
			                </h1>

	                        <router-link  to="/send-message"
	                        	class="inline-flex justify-center py-2 px-12 border border-transparent shadow-sm text-xl font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
	                        	Send a Message
	                        </router-link>
                    	</div>

                        <table v-if="messages.length > 0" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Addresses
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
                                                    <router-link :to="`/messages/to/${message.recipientEmail}`"
                                                        class="border-b border-indigo-600">
                                                        {{ message.recipientEmail }}
                                                    </router-link>
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
import MessageStatus from './MessageStatus';

const Messages = {
    components: {
        'message-status': MessageStatus
    },

    props: {
        recipient: {
            type: [String, Boolean],
            required: false,
            default: false
        }
    },

    data() {
        return {
            isLoading: false,
            showingFilters: false,
            filteringOptions: {
            	search: "",
            	recipientEmail: "",
            	senderEmail: ""
            },
            messages: []
        }
    },

    created() {
        if (this.recipient) {
            this.filteringOptions.recipientEmail = this.recipient;
            this.showingFilters = true;
        }
        this.fetchMessages(
            this.recipient ? true : false
        );
    },

    methods: {
        toggleFormForFiltering() {
        	this.showingFilters = !this.showingFilters;
        },

        messagePreview(message) {
            return message.bodyAsText ? message.bodyAsText :
                "HTML Message";
        },

        fetchMessages(filter) {
            filter = filter || false;

            this.isLoading = true;
            this.$api.fetchMessages(
                filter ? this.filteringOptions : {}
            )
                .then((messages) => {
                    this.isLoading = false;
                    this.messages = messages;
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.log("Ann error occurred:", err);
                });
        },

        filterMessages(filter) {
            this.fetchMessages(true);
        }
    },

    watch: {
        recipient (value) {
            if (value) {
                this.filteringOptions.recipientEmail = value;
                this.fetchMessages(true);
                return;
            }

            this.filteringOptions.recipientEmail = "";
            this.fetchMessages();
        }
    }
};

export default Messages;

</script>
