<template>
	<div>
        <portal to="header">
	        <div class="flex flex-row justify-between items-center">
	            <h1 class="text-3xl font-bold leading-tight text-gray-900">
	                Dashboard
	            </h1>
	            <h3 class="text-gray-600 italic">
	            	Last Updated: <span class="text-lg">{{ formatDate(stats.updatedAt) }}</span>
	            </h3>
	        </div>
	    </portal>

	    <div class="flex flex-col md:flex-row justify-between items-center -mx-2 mt-6"
	    	:class="{'opacity-50': isFetchingStats }">
	        <div class="w-1/4 px-2">
	            <div class="bg-white shadow-lg rounded-lg p-6">
	            	<div class="flex flex-row justify-between items-between">
	                	<h3 class="text-md text-gray-600 font-medium">
	                	    Total Messages
	                	</h3>
	                </div>
                	<h1 class="mt-3 text-3xl text-gray-900 font-semibold">
                	    {{ stats.total }}
                	</h1>
	            </div>
	        </div>
	        <div class="w-1/4 px-2">
	            <div class="bg-white shadow-lg rounded-lg p-6">
	            	<div class="flex flex-row justify-between items-between">
	                	<h3 class="text-md text-gray-600 font-medium">
	                	    Successful
	                	</h3>
	                	<h3 v-if="!isFetchingStats"
	                		class="px-2 py-1 text-md leading-5 font-semibold rounded-full text-green-900 bg-green-200">
	                		{{ percentageOfTotal(stats.sent) }}%
	                	</h3>
	                </div>
                	<h1 class="mt-3 text-3xl text-gray-900 font-semibold">
                	    {{ stats.sent }}
                	</h1>
	            </div>
	        </div>
	        <div class="w-1/4 px-2">
	            <div class="bg-white shadow-lg rounded-lg p-6">
	            	<div class="flex flex-row justify-between items-between">
	                	<h3 class="text-md text-gray-600 font-medium">
	                	    Failed
	                	</h3>
	                	<h3 v-if="!isFetchingStats"
	                		class="px-2 py-1 text-md leading-5 font-semibold rounded-full text-red-900 bg-red-200">
	                		{{ percentageOfTotal(stats.failed) }}%
	                	</h3>
	                </div>
                	<h1 class="mt-3 text-3xl text-gray-900 font-semibold">
                	    {{ stats.failed }}
                	</h1>
	            </div>
	        </div>
	        <div class="w-1/4 px-2">
	            <div class="bg-white shadow-lg rounded-lg p-6">
	            	<div class="flex flex-row justify-between items-between">
	                	<h3 class="text-md text-gray-600 font-medium">
	                	    Pending
	                	</h3>
	                	<h3 v-if="!isFetchingStats"
	                		class="px-2 py-1 text-md leading-5 font-semibold rounded-full text-gray-900 bg-gray-200">
	                		{{ percentageOfTotal(stats.pending) }}%
	                	</h3>
	                </div>
                	<h1 class="mt-3 text-3xl text-gray-900 font-semibold">
                	    {{ stats.pending }}
                	</h1>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script type="text/javascript">
const Dashboard = {
    data() {
        return {
        	isFetchingStats: true,
        	stats: {
        		total: 0,
        		pending: 0,
        		sent: 0,
        		failed: 0,
        		updatedAt: new Date()
        	}
        }
    },

    created () {
    	this.fetchStats();
    },

    methods: {
    	percentageOfTotal (value) {
    		return ((value / this.stats.total) * 100).toFixed(2);
    	},

    	fetchStats () {
    		this.isFetchingStats = true;
    		this.$api.fetchStats()
    			.then(stats => {
    				this.isFetchingStats = false;
    				if (stats) {
    					this.stats.total = stats.total;
    					this.stats.pending = stats.pending;
    					this.stats.sent = stats.sent;
    					this.stats.failed = stats.failed;
    					this.stats.updatedAt = stats.updatedAt;
    				}
    			})
    			.catch((err) => {
    				this.isFetchingStats = false;
    				alert("An error occurred while fetching stats. Kindly refresh the page and try again.");
    				console.log(err);
    			})
    	}
    }
}

export default Dashboard;

</script>
