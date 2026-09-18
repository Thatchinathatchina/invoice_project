@props([
    'title',
    'value',
    'iconBg' => 'bg-emerald-100',
    'iconColor' => 'text-emerald-600',
    'valueClass' => 'text-gray-800'
])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="text-2xl font-bold {{ $valueClass }} mt-1">{{ $value }}</p>
        </div>
        <div class="w-12 h-12 {{ $iconBg }} rounded-lg flex items-center justify-center">
            <div class="{{ $iconColor }} w-6 h-6 flex items-center justify-center">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
