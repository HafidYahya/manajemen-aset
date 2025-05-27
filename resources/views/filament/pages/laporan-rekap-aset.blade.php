<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <x-filament::card>
            <p class="font-medium text-sm text-gray-600">Total Aset</p>
            <p class="text-2xl font-semibold text-primary-600">{{ $data['total'] }}</p>
        </x-filament::card>

        <x-filament::card>
            <p class="font-medium text-sm text-gray-600">Total Nilai Perolehan</p>
            <p class="text-2xl font-semibold text-primary-600">
                Rp {{ number_format($data['nilaiTotal'], 0, ',', '.') }}
            </p>
        </x-filament::card>
    </div>
    <div class="flex items-center justify-end gap-2 mb-4">
        <x-filament::button tag="a" href="{{ route('export.rekap.excel') }}" icon="heroicon-o-arrow-down-tray">
            Export Excel
        </x-filament::button>

        <x-filament::button tag="a" href="{{ route('export.rekap.pdf') }}" icon="heroicon-o-document-text">
            Export PDF
        </x-filament::button>
    </div>


    {{ $this->table }}
</x-filament::page>
