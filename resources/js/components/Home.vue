<template>
    <div>
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="w-full flex flex-col md:flex-row justify-between items-center">
                        <router-link to="/">
                            <div class="flex-shrink-0 flex flex-row justify-start items-end text-gray-200">
                                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                                </svg>
                                <span class="ml-1 text-lg font-bold">MiniSend</span>
                            </div>
                        </router-link>
                        <div class="block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <router-link to="/send-message"
                                	active-class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium"
									class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                	New Message
                                </router-link>
								<router-link to="/messages"
                                	active-class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium"
									class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
									All Messages
								</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            	<portal-target name="header">
		            <h1 class="text-3xl font-bold leading-tight text-gray-900">
		                Dashboard
		            </h1>
		        </portal-target>
            </div>
        </header>

        <main class="bg-gray-100">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
        			<router-view></router-view>
                </div>
            </div>
        </main>
    </div>
</template>
<script type="text/javascript">
const Home = {

}

export default Home;

</script>
