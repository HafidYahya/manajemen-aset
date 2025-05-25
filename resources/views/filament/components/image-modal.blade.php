<div class="p-4">
    @if($imageUrl)
        <img src="{{ $imageUrl }}" class="w-full h-auto max-h-[80vh] object-contain">
    @else
        <p class="text-gray-500">Tidak ada gambar</p>
    @endif
</div>