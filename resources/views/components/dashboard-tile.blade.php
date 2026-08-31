<div class="w-full bg-nblue-300 border border-dashboard-tile rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between">

        <div>
            <p class="text-sm text-black font-bold">
                {{ $title }}
            </p>

            <p id="{{ $id }}"
               class="text-xl font-bold text-gray-800 mt-2 dashboard-tile-value">
                <i class="fas fa-spinner fa-spin text-sm"></i>
            </p>
        </div>

        <div class="{{ $iconBg }} {{ $iconColor }} p-3 rounded-full">
            <i class="{{ $icon }} text-xl"></i>
        </div>

    </div>
</div>