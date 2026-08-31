<nav
    :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="fixed inset-y-0 left-0 z-50 bg-nblue-800 text-white
           shadow-lg transition-all duration-300 overflow-hidden">

    <!-- Logo + Toggle -->
    <div class="h-16 flex items-center border-b border-white/10">

        <div x-show="sidebarOpen"
             class="flex-1 flex justify-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo
                    class="block h-9 w-auto fill-current text-white"
                />
            </a>
        </div>

        <button
            @click="sidebarOpen = !sidebarOpen"
            class="p-3 mx-auto text-white hover:bg-white/10 rounded"
        >
            <svg class="w-6 h-6"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>

            </svg>
        </button>

    </div>


    <!-- Navigation -->
    <div class="px-3 py-5 space-y-1">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3 py-3 rounded-md
                  text-white hover:bg-white/10">

            <span class="w-5 text-center"><i class="fas fa-dashboard"></i></span>

            <span x-show="sidebarOpen"
                  class="ml-3 whitespace-nowrap">
                Dashboard
            </span>

        </a>


        @role('accountant')

            <div x-show="sidebarOpen"
                 class="px-3 pt-5 pb-2 text-xs
                        uppercase text-gray-300">
                Accountant
            </div>


            <!-- Hospital -->
            <a href="{{ route('hospital') }}"
               class="flex items-center px-3 py-3 rounded-md
                      text-white hover:bg-white/10">

                <span class="w-5 text-center"><i class="fas fa-hospital"></i></span>

                <span x-show="sidebarOpen"
                      class="ml-3 whitespace-nowrap">
                    Hospital
                </span>
            </a>


            <!-- Clients -->
            <a href="{{ route('clients') }}"
               class="flex items-center px-3 py-3 rounded-md
                      text-white hover:bg-white/10">

                <span class="w-5 text-center"><i class="fas fa-user-tie"></i></span>

                <span x-show="sidebarOpen"
                      class="ml-3 whitespace-nowrap">
                    Clients
                </span>
            </a>


            <!-- Services -->
            <a href="{{ route('services') }}"
               class="flex items-center px-3 py-3 rounded-md
                      text-white hover:bg-white/10">
                <span class="w-5 text-center"><i class="fas fa-hand-holding-medical"></i></span>
                <span x-show="sidebarOpen"
                      class="ml-3 whitespace-nowrap">
                    Services
                </span>
            </a>


            <!-- Invoices -->
            <a href="{{ route('invoices') }}"
               class="flex items-center px-3 py-3 rounded-md
                      text-white hover:bg-white/10">
                <span class="w-5 text-center"><i class="fas fa-file-invoice-dollar"></i></span>
                <span x-show="sidebarOpen"
                      class="ml-3 whitespace-nowrap">
                    Invoices
                </span>
            </a>


            <!-- Reports -->
            <div x-data="{ reportsOpen: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">

                <button type="button"
                        @click="reportsOpen = !reportsOpen"
                        class="w-full flex items-center px-3 py-3 rounded-md
                            text-white hover:bg-white/10">

                    <span class="w-5 text-center">
                        <i class="fas fa-chart-line"></i>
                    </span>

                    <span x-show="sidebarOpen"
                        class="ml-3 whitespace-nowrap flex-1 text-left">
                        Reports
                    </span>

                    <span x-show="sidebarOpen"
                        class="text-xs">
                        <i class="fas"
                        :class="reportsOpen ? 'fa-chevron-up' : 'fa-chevron-down'">
                        </i>
                    </span>

                </button>


                <!-- Reports Dropdown -->
                <div x-show="reportsOpen && sidebarOpen"
                    x-transition
                    x-cloak
                    class="ml-8 mt-1 space-y-1">

                    <!-- Workers Report -->
                    <a href="{{ route('reports.workers') }}"
                    class="flex items-center px-3 py-2 rounded-md
                            text-sm text-white/80
                            hover:bg-white/10 hover:text-white
                            {{ request()->routeIs('reports.workers')
                                    ? 'bg-white/10 text-white'
                                    : '' }}">

                        

                        <span class="ml-3 whitespace-nowrap">
                            Workers Report
                        </span>

                    </a>

                </div>

            </div>            
            

        @endrole


        @role('system admin')

            <div x-show="sidebarOpen"
                 class="px-3 pt-5 pb-2 text-xs
                        uppercase text-gray-300">
                Administration
            </div>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center px-3 py-3 rounded-md
                      text-white hover:bg-white/10">

                <span class="w-5 text-center"><i class="fas fa-user"></i></span>

                <span x-show="sidebarOpen"
                      class="ml-3 whitespace-nowrap">
                    Users
                </span>

            </a>

        @endrole

    </div>

</nav>