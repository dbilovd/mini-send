<template>
    <div>
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-8" src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="MiniSend">
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <router-link to="/send-message"
                                	class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">
                                	Send a new message
                                </router-link>
								<router-link to="/messages"
									class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
									View Your Messages
								</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--
		      Mobile menu, toggle classes based on menu state.

		      Open: "block", closed: "hidden"
		    -->
            <div class="hidden md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <router-link to="/send-message"
                    	class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium">
                    	Send a new message
                    </router-link>
					<router-link to="/messages"
						class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
						View Your Messages
					</router-link>
                </div>
            </div>
        </nav>
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold leading-tight text-gray-900">
                    Dashboard
                </h1>
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
