<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Rekap Aset</h2>

    <x-filament::card class="mt-6">
    <h3 class="text-lg font-bold mb-2">📦 Rekap Aset Berdasarkan Nama & Lokasi</h3>

    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="p-2">#</th>
                <th class="p-2">Nama Aset</th>
                <th class="p-2">Lokasi</th>
                <th class="p-2">Jumlah</th>
                <th class="p-2">Nilai Total</th>
                <th class="p-2">Status Terbanyak</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekapNamaLokasi as $i => $row)
                <tr class="border-b">
                    <td class="p-2">{{ $i + 1 }}</td>
                    <td class="p-2">{{ $row->nama }}</td>
                    <td class="p-2">
                        {{ $row->lokasi->ruangan ?? '-' }} - {{ $row->lokasi->gedung ?? '' }}
                    </td>
                    <td class="p-2">{{ $row->jumlah }}</td>
                    <td class="p-2">Rp {{ number_format($row->total_nilai, 0, ',', '.') }}</td>
                    <td class="p-2">
                        <span class="capitalize">{{ $row->status_utama }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::card>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-filament::card>
            <p class="font-medium">Total Aset</p>
            <p class="text-2xl">{{ $data['total'] }}</p>
        </x-filament::card>

        <x-filament::card>
            <p class="font-medium">Total Nilai Perolehan</p>
            <p class="text-2xl">Rp {{ number_format($data['nilaiTotal'], 0, ',', '.') }}</p>
        </x-filament::card>
    </div>
    
</x-filament::page>
