<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="text-center">
                        <h2 class="text-2xl font-semibold mb-4">Welcome to TMS!</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Your account has been created successfully, but you don't have a role assigned yet.
                        </p>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                            <p class="text-yellow-800 dark:text-yellow-200">
                                <strong>Note:</strong> Please contact an administrator to assign you a role (Driver, Dispatcher, or Admin) to access the full system features.
                            </p>
                        </div>
                        
                        <div class="space-y-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                To test the system immediately, you can log in with these demo accounts:
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                    <h3 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Admin</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">admin@example.com</p>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                    <h3 class="font-semibold text-green-800 dark:text-green-200 mb-2">Dispatcher</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">dispatcher@example.com</p>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                                    <h3 class="font-semibold text-purple-800 dark:text-purple-200 mb-2">Driver</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">driver@example.com</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                Password for all demo accounts: <code class="bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded">password</code>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
