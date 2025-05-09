<div class="w-64 bg-white shadow-md">
   <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
      <span class="sr-only">Open sidebar</span>
      <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
         <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
      </svg>
   </button>



   <aside id="logo-sidebar" class="top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
      <div class="h-full px-3 py-4 overflow-y-auto bg-[#102447]">
         <div class="flex items-center justify-between ps-2.5 mb-5">
            <a href="#" class="flex items-center">
               <img src="{{ asset('images/ciputra_logo.png') }}" class="w-8 h-8 me-3 sm:h-8 bg-white p-1 rounded-full" alt="Ciputra Logo" />
               <span class="self-center text-xl font-semibold whitespace-nowrap text-white">Ciputra Patroli</span>
            </a>
            <div class="relative" x-data="{ open: false }">
               <button @click="open = !open" class="p-2 text-white hover:bg-[#1C3A6B] rounded-lg relative">
                  <i class="fas fa-bell text-xl"></i>
                  <span id="notification-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
               </button>
               
               <div x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    style="position: fixed; left: 16rem; top: 4rem;"
                    class="w-80 bg-white rounded-lg shadow-xl overflow-hidden z-[99999]">
                  
                  <div class="p-4 border-b border-gray-100">
                     <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                           <i class="fas fa-times"></i>
                        </button>
                     </div>
                  </div>
                  
                  <div id="notification-list" class="max-h-[400px] overflow-y-auto">
                     
                  </div>
                  
                  <div class="p-4 border-t border-gray-100">
                     <a href="{{ route('admin.kejadian.kejadian') }}" class="text-sm text-[#1C3A6B] hover:text-[#2a4f8a] font-medium">
                        View all notifications
                     </a>
                  </div>
               </div>
            </div>
         </div>
         <ul class="space-y-2 font-medium">
            <li>
               <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-home w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>
            <br>
            <li class="px-2 mt-5">
               <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Manajemen</h2>
            </li>
            <li>
               <a href="{{ route('admin.satpam.satpam') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-user-shield w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">Satpam</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.kepala_satpam.kepala_satpam') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-user-tie w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">Kepala Satpam</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.manajemen.manajemen') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-users-cog w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">Manajemen</span>
               </a>
            </li>

            <br>
            <li class="px-2 mt-5">
               <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Data Patroli</h2>
            </li>

            <li>
               <a href="{{ route('admin.jadwal_patroli.jadwal_patroli') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-calendar-alt w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">Jadwal Patroli</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.patroli.patroli') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-walking w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">Patroli</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.lokasi.lokasi') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-map-marker-alt w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">Lokasi</span>
               </a>
            </li>

            <br>
            <li class="px-2 mt-5">
               <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Data Kejadian</h2>
            </li>

            <li>
               <a href="{{ route('admin.kejadian.kejadian') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-exclamation-triangle w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">Kejadian</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.notifikasi.notifikasi') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-bell w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">Notifikasi</span>
               </a>
            </li>

            <br>
            <li class="px-2 mt-5">
               <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Account</h2>
            </li>

            <li>
               <a href="{{ route('admin.profile') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fas fa-user-circle w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
               </a>
            </li>

            <li>
               <form action="{{ route('logout') }}" method="POST" class="w-full">
                  @csrf
                  <button type="submit" class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                     <i class="fas fa-sign-out-alt w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                     <span class="ms-3 whitespace-nowrap">Sign Out</span>
                  </button>
               </form>
            </li>
         </ul>
      </div>
   </aside>



</div>