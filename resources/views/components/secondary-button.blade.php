<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 text-[#c9a341] font-bold text-sm uppercase tracking-widest hover:text-white transition-colors duration-200 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
