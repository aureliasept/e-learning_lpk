{{-- Table Data Cell Component --}}
@props(['center' => false, 'right' => false, 'muted' => false, 'bold' => false])

<td {{ $attributes->merge(['class' => 'px-5 py-4 text-sm' . ($center ? ' text-center' : ($right ? ' text-right' : '')) . ($muted ? ' text-gray-400' : ' text-white') . ($bold ? ' font-medium' : '')]) }}>
    {{ $slot }}
</td>
