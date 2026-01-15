<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-3 bg-[#c9a341] border border-transparent rounded-xl font-bold text-sm uppercase tracking-widest text-[#0f172a] hover:bg-[#b08d35] focus:outline-none focus:ring-2 focus:ring-[#c9a341] focus:ring-offset-2 focus:ring-offset-[#1e293b] shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200']) }}>
    {{ $slot }}
</button>
