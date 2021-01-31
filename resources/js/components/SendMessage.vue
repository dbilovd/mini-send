<template>
	<div>
		<portal to="header">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">
                Send a New Message
            </h1>
		</portal> 

		<div class="flex">
		    <div class="w-full md:w-3/5 mx-auto mt-5 md:mt-0">
		        <form action="#" method="POST" @submit.prevent="sendMessage"
		        	:disabled="isSendingMessage" :class="{'opacity-50': isSendingMessage}">
		            <div class="shadow sm:rounded-md sm:overflow-hidden">
		                <div class="px-10 py-12 bg-white space-y-10 sm:p-8">
		                    <div class="">
		                        <div class="col-span-3 sm:col-span-2">
		                            <label for="recipientEmail" class="block text-sm font-medium text-gray-700">
		                                To
		                            </label>
			                        <p class="mt-2 text-sm text-gray-500">
			                            Enter the recipients email address
			                        </p>
		                            <div class="mt-1">
		                                <input type="email" name="recipientEmail" id="recipientEmail" placeholder="receiver@gmail.com"
		                                	v-model="formData.recipientEmail"
		                                	class="form-input focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm shadow border border-gray-300">
		                            </div>
		                        </div>
		                    </div>
		                    <div>
		                        <label for="subject" class="block text-sm font-medium text-gray-700">
		                            Subject
		                        </label>
		                        <p class="mt-2 text-sm text-gray-500">
		                            The subject for your mail
		                        </p>
		                        <div class="mt-1">
	                                <input type="text" name="subject" id="subject" placeholder="Greetings"
	                                	v-model="formData.subject"
	                                	class="form-input focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm shadow border border-gray-300">
		                        </div>
		                    </div>
		                    <div>
		                        <label for="bodyAsText" class="block text-sm font-medium text-gray-700">
		                            Message
		                        </label>
		                        <p class="mt-2 text-sm text-gray-500">
		                            Provide the message as plain text
		                        </p>
		                        <div class="mt-1">
		                            <textarea id="bodyAsText" name="bodyAsText" rows="3" placeholder="Hello, thank you."
		                                v-model="formData.bodyAsText"
		                            	class="form-textarea shadow focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
		                        </div>
		                    </div>
		                    <div>
		                        <label for="bodyAsHtml" class="block text-sm font-medium text-gray-700">
		                            HTML Message
		                        </label>
		                        <p class="mt-2 text-sm text-gray-500">
		                            Provide the message with HTML
		                        </p>
		                        <div class="mt-1">
		                            <textarea id="bodyAsHtml" name="bodyAsHtml" rows="3" placeholder="<p><strong>Hello</strong>, thank you!</p>"
		                                v-model="formData.bodyAsHtml"
		                            	class="form-textarea shadow focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
		                        </div>
		                    </div>
		                    <div>
		                        <label class="block text-sm font-medium text-gray-700">
		                           Attachments
		                        </label>
		                        <div v-if="uploadedAttachments.length > 0" class="my-2">
		                        	<attachments-list :attachments="uploadedAttachments"></attachments-list>
		                        </div>
		                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
		                            <div class="space-y-1 text-center" 
		                    			:class="{ 'opacity-50': isUploadingAttachment }"
		                    			:disabled="isUploadingAttachment">
		                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
		                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
		                                </svg>
		                                <div class="flex text-sm text-gray-600">
		                                    <label for="attachment-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
		                                        <span>Upload file(s)</span>
		                                        <input id="attachment-upload" name="attachment-upload" ref="attachment"
		                                        	type="file" multiple class="sr-only"
		                                        	@change.prevent="uploadAttachment">
		                                    </label>
		                                    <p class="pl-1">or drag and drop</p>
		                                </div>
		                                <p class="text-xs text-gray-500">
		                                    PDF, PNG, JPG, GIF up to 10MB
		                                </p>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
		                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
		                    	:class="{ 'opacity-50': isSendingMessage || isUploadingAttachment }"
		                    	:disabled="isSendingMessage || isUploadingAttachment">
		                        {{ isSendingMessage ? 'Sending Message ...' : 'Send Message' }}
		                    </button>
		                </div>
		            </div>
		        </form>
		    </div>
		</div>
	</div>
</template>

<script type="text/javascript">
import AttachmentList from './AttachmentList';

const SendMessage = {
	components: {
		'attachments-list': AttachmentList
	},

	data () {
		return {
			isSendingMessage: false,
			isUploadingAttachment: false,
			uploadedAttachments: [],
			formData: {
				senderEmail: "",
				recipientEmail: "",
				subject: "",
				bodyAsText: "",
				bodyAsHtml: "",
				attachments: [],
			}
		}
	},

	mounted () {
		console.log(this);
	},

	methods: {
		sendMessage () {
			this.isSendingMessage = true;
			this.$api.sendMessage(this.formData)
				.then((message) => {
					this.isSendingMessage = false;
					window.alert("Sent!");
					console.log("Sent: ", message);
					this.$router.push(`/messages/${message.messageId}`);
				})
				.catch((err) => {
					this.isSendingMessage = false;
					window.alert("An error occurred while sending");
					console.log(err);
				})
		},

		uploadAttachment () {
			this.isUploadingAttachment = true;
			let filesToUpload = this.$refs.attachment.files;
			let formData = new FormData();

			for (let i = 0; i < filesToUpload.length; i++) {
				formData.append(`files[${i}]`, filesToUpload[i]);
			}

			this.$api.uploadAttachment(formData)
				.then((attachments) => {
					attachments.forEach((attachment) => {
						this.uploadedAttachments.push(attachment);
						this.formData.attachments.push(attachment.attachmentId);
					});
					this.isUploadingAttachment = false;
				})
				.catch((err) => {
					this.isUploadingAttachment = false;
					alert(err);
					console.log(err);
				})
		}
	}
};

export default SendMessage;

</script>
