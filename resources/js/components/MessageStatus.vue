<template>
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="statusClasses">
        {{ status }}
    </span>
</template>

<script type="text/javascript">
const MessageStatus = {
	props: {
		status: {
			type: String,
			required: true
		}
	},

	data () {
		return {}
	},

	computed: {
		statusClasses () {
			switch (this.status) {
				case 'sent' :
					return ["bg-green-100", "text-green-800"];

				case 'failed':
					return ["bg-red-100", "text-red-800"];

				case 'pending':
				default:
					return ["bg-gray-100", "text-gray-800"];
			}
		}
	}
}

export default MessageStatus;
</script>