<div class="p-4">
    @if($qrUrl)
        <img src="{{ $qrUrl }}" class="w-full h-auto max-h-[80vh] object-contain">
    @else
        <p class="text-gray-500">Tidak ada QR</p>
    @endif
</div>