<x-filament::page>
    <div
        x-data="mapComponent()"
        x-init="initMap()"
        class="rounded-lg overflow-hidden border shadow"
    >
        {{-- PETA --}}
        <div
            id="map"
            style="height: 500px; background: #eee;"
            class="w-full"
        ></div>

        {{-- GRID ASET --}}
    <div class="mt-6 bg-white p-4 border rounded shadow">
        {{-- Search --}}
        <div class="mt-6 mb-2">
            <input 
                type="text" 
                placeholder="Cari nama aset..." 
                x-model="searchQuery"
                class="border p-2 rounded w-full text-sm"
            />
        </div>
        <h2 class="text-sm font-semibold mb-2">Daftar Aset</h2>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">IMEI</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="aset in filteredAsets" :key="aset.imei">
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border" x-text="aset.nama"></td>
                        <td class="p-2 border" x-text="aset.imei"></td>
                        <td class="p-2 border">
                            <button 
                                class="px-2 py-1 text-white bg-blue-500 rounded hover:bg-blue-600"
                                @click="focusToAset(aset.imei)"
                            >Lihat di Peta</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    </div>

    {{-- LEAFLET CDN --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- JS --}}
    <script>
    function mapComponent() {
        return {
            map: null,
            asetData: @entangle('asetData'),
            asetPaths: @entangle('asetPaths'),
            searchQuery: '',

            get filteredAsets() {
                if (!this.searchQuery) return this.asetData;
                return this.asetData.filter(aset =>
                    aset.nama.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            },

            initMap() {
                this.map = L.map('map').setView([-6.6, 106.8], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                }).addTo(this.map);

                this.renderAllAsets();
            },

            renderAllAsets() {
                if (!Array.isArray(this.asetData)) return;

                const allLatLng = [];

                this.asetData.forEach(aset => {
                    const latlng = aset.last;
                    allLatLng.push(latlng);

                    // Marker lokasi terakhir
                    L.marker(latlng)
                        .addTo(this.map)
                        .bindPopup(`<strong>${aset.nama}</strong><br>IMEI: ${aset.imei}`);

                    // Garis lintasan
                    const path = this.asetPaths[aset.imei] || [];

                    if (path.length > 1) {
                        // Polyline
                        L.polyline(path, {
                            color: aset.color,
                            weight: 4,
                            opacity: 0.8
                        }).addTo(this.map);

                        // Titik START (🟢)
                        L.circleMarker(path[0], {
                            radius: 6,
                            color: 'green',
                            fillColor: 'green',
                            fillOpacity: 0.9
                        }).addTo(this.map).bindPopup(`Start - ${aset.nama}`);

                        // Titik END (🔴)
                        L.circleMarker(path[path.length - 1], {
                            radius: 6,
                            color: 'red',
                            fillColor: 'red',
                            fillOpacity: 0.9
                        }).addTo(this.map).bindPopup(`End - ${aset.nama}`);
                    }
                });

                // Auto zoom ke semua titik
                if (allLatLng.length > 0) {
                    this.map.fitBounds(allLatLng, { padding: [50, 50] });
                }
            },

            focusToAset(imei) {
                const path = this.asetPaths[imei];
                if (!path || path.length === 0) return;

                const last = path[path.length - 1];
                this.map.setView(last, 16, { animate: true });

                // Tampilkan popup opsional
                L.popup()
                    .setLatLng(last)
                    .setContent(`Aset IMEI: ${imei}`)
                    .openOn(this.map);
            }
        }
    }
</script>

</x-filament::page>
