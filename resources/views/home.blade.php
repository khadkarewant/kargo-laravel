<x-guest-layout>

    <h1>Welcome</h1>
    <p>Cargo and Customs Clearance.</p>
    
    <a href="{{ route('login') }}">Login</a>
    <a href="{{ route('register') }}">Register</a>

    <hr style="margin: 20px 0;">
    
    <h2>Track Your Shipment</h2>
    
    <form action="{{ route('tracking.show') }}" method="GET">
        <div>
            <label for="tracking_id">Tracking ID</label>
            <input
            type="text"
            name="tracking_id"
            id="tracking_id"
            value="{{ old('tracking_id') }}"
            placeholder="Enter tracking ID"
            required
            >
        </div>
        
        @error('tracking_id')
            <p style="color: red;">{{ $message }}</p>
            @enderror
            
            <button type="submit">Track</button>
    </form>
        
</x-guest-layout>