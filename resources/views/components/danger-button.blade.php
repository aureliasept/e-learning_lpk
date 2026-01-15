<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center text-red-500 hover:text-red-400 font-bold uppercase tracking-widest text-xs transition']) }}>
    {{ $slot }}
</button>
