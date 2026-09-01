<div
    x-data="notificationBell()"
    x-init="init()"
    class="relative"
>

    <!-- Bell -->
    <button
        type="button"
        @click="open = !open"
        class="relative p-2 text-gray-600 hover:text-gray-900"
    >
        <div class="w-9">            
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
            </svg>
            <span
                    x-show="count > 0"
                    x-text="count"
                    class="text-s text-bold absolute top-1"></span>
        </div>

        <!-- Count -->
        <span
            x-show="count > 0"
            x-text="count"
            class="absolute -top-1 -right-2 z-10
                   min-w-5 h-5
                   flex items-center justify-center
                   rounded-full bg-red-500
                   px-1 text-xs font-bold text-white"
        ></span>
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute right-0 z-50 mt-2 w-[400px]
               overflow-hidden rounded-lg bg-white
               shadow-lg ring-1 ring-black/5"
        style="display: none;"
    >

        <div class="items-center justify-between border-b px-4 py-3">
            <h3 class="font-semibold text-gray-800">
                Notifications
            </h3>            
        </div>

        <div class="border-b px-4  hover:bg-gray-50 " style="max-height:400px; width:500px; overflow-y:auto;">

            <template x-for="notification in notifications"
                      :key="notification.id">

                <div class="border-b px-4 py-3 hover:bg-gray-50">

                    <div
                        class="font-medium text-gray-800"
                        x-text="notification.title"
                    ></div>

                    <div
                        class="mt-1 text-sm text-gray-600"
                        x-text="notification.message"
                    ></div>

                    <template x-if="notification.export_id">
                        <a
                            :href="'{{ route('reports.workers.download', ['id' => '__EXPORT_ID__', 'notification' => '__NOTIFICATION_ID__']) }}'
                                .replace('__NOTIFICATION_ID__', notification.id)
                                .replace('__EXPORT_ID__', notification.export_id)"
                            class="mt-2 inline-block text-sm
                                   font-medium text-blue-600
                                   hover:underline"
                        >
                            Download
                        </a>
                    </template>

                    <div
                        class="mt-1 text-xs text-gray-400"
                        x-text="notification.created_at"
                    ></div>

                </div>

            </template>

            <div
                x-show="notifications.length === 0"
                class="px-4 py-6 text-center text-sm text-gray-500"
            >
                No new notifications
            </div>

        </div>
    </div>
</div>

<script>
function notificationBell() {
    return {
        open: false,
        count: 0,
        notifications: [],

        init() {
            this.loadNotifications();

            // Check every 3 seconds
            setInterval(() => {
                this.loadNotifications();
            }, 3000);
        },

        loadNotifications() {
            fetch('{{ route('notifications') }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load notifications');
                }

                return response.json();
            })
            .then(data => {
                this.count = data.count;
                this.notifications = data.notifications;
            })
            .catch(error => {
                console.error('Notification error:', error);
            });
        }
    }
}
</script>
