@if($getRecord()->qr_code)
    <img src="{{ asset('storage/' . $getRecord()->qr_code) }}" alt="QR Code">
@endif