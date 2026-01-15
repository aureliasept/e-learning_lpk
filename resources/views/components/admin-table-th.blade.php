{{-- Table Header Cell Component --}}
@props(['center' => false, 'right' => false])

<th {{ $attributes->merge(['class' => 'px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider' . ($center ? ' text-center' : ($right ? ' text-right' : ''))]) }}>
    {{ $slot }}
</th>
