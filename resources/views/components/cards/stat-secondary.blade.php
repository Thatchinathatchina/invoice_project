@props([
    'title',
    'value',
    'iconBg' => 'bg-emerald-100',
    'iconColor' => 'text-emerald-600'
])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 {{ $iconBg }} rounded-full flex items-center justify-center">
            <span class="{{ $iconColor }} font-bold text-sm">{{ $value }}</span>
        </div>
        <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
    </div>
</div>
