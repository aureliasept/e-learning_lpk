@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full bg-[#0f172a] border border-gray-700 rounded-xl p-3 text-white text-sm placeholder-gray-500 focus:border-[#c9a341] focus:ring-0 transition-colors duration-200']) }}>
