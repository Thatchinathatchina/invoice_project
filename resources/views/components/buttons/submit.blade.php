<button {{ $attributes->merge(['type' => 'submit', 'class' => 'py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-sm']) }}>
    {{ $slot }}
</button>
