<template>
    <div>
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="w-full flex flex-col md:flex-row justify-between items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-8" src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="MiniSend">
                        </div>
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
