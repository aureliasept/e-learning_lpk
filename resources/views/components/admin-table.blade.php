{{--
    Reusable Admin Table Component
    
    Contoh Penggunaan:
    <x-admin-table>
        <x-slot name="header">
            <x-admin-table-th>Nama</x-admin-table-th>
            <x-admin-table-th>Email</x-admin-table-th>
            <x-admin-table-th center>Aksi</x-admin-table-th>
        </x-slot>
        
        @foreach($items as $item)
        <x-admin-table-row>
            <x-admin-table-td>{{ $item->name }}</x-admin-table-td>
            <x-admin-table-td muted>{{ $item->email }}</x-admin-table-td>
            <x-admin-table-td center>Aksi buttons</x-admin-table-td>
        </x-admin-table-row>
        @endforeach
        
        <x-slot name="empty">
            Belum ada data
        </x-slot>
        
        <x-slot name="pagination">
            {{ $items->links() }}
        </x-slot>
    </x-admin-table>
--}}

@props(['emptyIcon' => null])

<div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            @if(isset($header))
            <thead>
                <tr class="bg-[#0b1221]/80 border-b border-[#1e293b]">
                    {{ $header }}
                </tr>
            </thead>
            @endif
            <tbody class="divide-y divide-[#1e293b]/70">
                {{ $slot }}
                
                @if(isset($empty) && $slot->isEmpty())
                <tr>
                    <td colspan="100" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center gap-4">
                            <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center">
                                @if($emptyIcon)
                                    {!! $emptyIcon !!}
                                @else
                                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="text-center">
                                {{ $empty }}
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    @if(isset($pagination))
    <div class="bg-[#0b1221]/50 px-6 py-4 border-t border-[#1e293b]">
        {{ $pagination }}
    </div>
    @endif
</div>
