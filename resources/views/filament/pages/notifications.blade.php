<x-filament::page>
    <div class="space-y-4">
        @foreach ($this->getNotifications() as $notification)
            <div class="p-4 bg-white rounded shadow">
                <div class="font-semibold">{{ $notification->data['title'] ?? 'Notifikasi' }}</div>
                <div class="text-sm text-gray-600">{{ $notification->created_at->diffForHumans() }}</div>
            </div>
        @endforeach

        {{ $this->getNotifications()->links() }}
    </div>
</x-filament::page>
