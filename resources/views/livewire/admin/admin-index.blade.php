<div>
    @if(session('logout'))
        <div class="px-4 py-3 rounded mb-4">
            <div role="alert" class="alert alert-success">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6 shrink-0 stroke-current"
                  fill="none"
                  viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span> {{ session('logout') }}</span>
              </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:Admin.user-create/>
                    <livewire:Admin.user-edit/>
                    <livewire:Admin.user-delete/>
                    <livewire:Admin.user-table/>



                </div>
            </div>
        </div>
    </div>



</div>