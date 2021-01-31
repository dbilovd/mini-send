<template>
	<div>
		<portal to="header">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">
                Message Details
            </h1>
		</portal> 

		<div v-if="isLoading" class="text-center py-5 font-medium text-xl">
			<h1>Loading...</h1>
		</div>

	    <div v-if="!isLoading" class="bg-white shadow overflow-hidden sm:rounded-lg">
	        <div class="px-4 py-5 sm:px-6 flex flex-row justify-between items-center">
	            <h3 class="text-lg leading-6 font-medium text-gray-900">
	                {{ message.subject }}
	            </h3>
	            <message-status :status="message.status"></message-status>
	        </div>
	        <div class="border-t border-gray-200">
	            <dl>
	                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
	                    <dt class="text-sm font-medium text-gray-500">
	                        Recipient
	                    </dt>
	                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
	                        {{ message.recipientEmail }}
	                    </dd>
	                </div>
	                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
	                    <dt class="text-sm font-medium text-gray-500">
	                        Sender
	                    </dt>
	                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
	                        {{ message.senderEmail || "You" }}
	                    </dd>
	                </div>
	                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
	                    <dt class="text-sm font-medium text-gray-500">
	                        Email address
	                    </dt>
	                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
	                        margotfoster@example.com
	                    </dd>
	                </div>
	                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
	                    <dt class="text-sm font-medium text-gray-500">
	                        Message (Text)
	                    </dt>
	                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
	                        {{ message.bodyAsText || "-" }}
	                    </dd>
	                </div>
	                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
	                    <dt class="text-sm font-medium text-gray-500">
	                        Message (HTML)
	                    </dt>
	                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
	                    	<span v-if="!message.bodyAsHtml">-</span>
	                    	<div v-else class="flex justify-between items-top">
		                        <div v-if="!previewingHtml" v-html="message.bodyAsHtml"></div>
		                        <div v-else>{{ message.bodyAsHtml }}</div>

		                        <button @click.prevent="toggleHtmlPreview"
		                        	class="bg-gray-200 px-2 py-1 shadow-sm text-gray-800 rounded-sm">
		                        	{{ previewingHtml ? 'Show Preview' : 'Show HTML' }} 
		                        </button>
	                    	</div>
	                    </dd>
	                </div>
	                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
	                    <dt class="text-sm font-medium text-gray-500">
	                        Attachments
	                    </dt>
	                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
	                    	<h3 v-if="message.attachments.length < 1"
	                    		class="border border-gray-200 rounded-md divide-y divide-gray-200 py-10 text-center">
	                    		No Attachments on this message.
	                    	</h3>
	                        <div v-else>
	                        	<attachment-list :attachments="message.attachments"></attachment-list>
	                        </div>
	                    </dd>
	                </div>
	                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
	                    <dt class="text-sm font-medium text-gray-500">
	                        Activities
	                    </dt>
	                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
	                        <div class="flex items-center justify-start">
	                        	<dt class="w-1/5 text-sm font-medium text-gray-500">
			                        Created At
			                    </dt>
			                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
			                        {{ formatDate(message.createdAt) }}
			                    </dd>
			                </div>
	                        <div class="flex items-center justify-start mt-6">
			                    <dt class="w-1/5 text-sm font-medium text-gray-500">
			                        Updated At
			                    </dt>
			                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
			                        {{ formatDate(message.updatedAt) }}
			                    </dd>
			                </div>
	                        <div class="flex items-center justify-start mt-6">
			                    <dt class="w-1/5 text-sm font-medium text-gray-500">
			                        Sent At
			                    </dt>
			                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
			                    	{{ message.sentAt ? formatDate(message.sentAt) : "-" }}
			                    </dd>
	                        </div>
	                    </dd>
	                </div>
	            </dl>
	        </div>
	    </div>
	</div>
</template>

<script type="text/javascript">
import AttachmentList from './AttachmentList';
import MessageStatus from './MessageStatus';

const MessageDetails = {
	components: {
		'attachment-list': AttachmentList,
		"message-status": MessageStatus
	},

    data() {
        return {
        	isLoading: true,
        	previewingHtml: false,
        	message: {}
        }
    },

	created () {
		this.fetchMessage();
	},

	methods: {
		toggleHtmlPreview () {
    		this.previewingHtml = !this.previewingHtml;
  		},

		fetchMessage () {
			this.isLoading = true;
			this.$api.fetchMessageDetails(this.$route.params.messageId)
				.then((message) => {
					this.isLoading = false;
					this.message = message;
				})
				.catch((err) => {
					this.isLoading = false;
					console.log("Ann error occurred:", err);
				});
		}
	}


}

export default MessageDetails;

</script>
