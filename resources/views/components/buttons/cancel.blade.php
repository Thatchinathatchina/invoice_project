<button {{ $attributes->merge(['type' => 'button', 'class' => 'py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm']) }}>
    {{ $slot }}
</button>
