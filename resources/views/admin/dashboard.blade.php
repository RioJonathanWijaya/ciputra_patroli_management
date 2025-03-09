@extends('layouts.app')


@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
        
        <div id="firebase-user-info" class="mb-4">
            <h2 class="text-xl font-semibold">User Information</h2>
            <div id="user-details" class="mt-2 text-gray-600">
                <p><strong>Name:</strong> <span id="user-name">Name</span></p>
                <p><strong>Email:</strong> <span id="user-email">Loading....</span></p>
                <p><strong>UID:</strong> <span id="user-uid">Loading...</span></p>
            </div>
        </div>
        
        
        <!-- Log Out Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mt-4 p-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">
                Log Out
            </button>
        </form>
    </div>
</div>


<script>
    
    // // Firebase Initialization
    //     const firebaseConfig = {
    // apiKey: "AIzaSyCE_bWZ-R12Sh0LaWTRZczK6vSOGBhUUQM",
    // authDomain: "ciputrapatroli.firebaseapp.com",
    // databaseURL: "https://ciputrapatroli-default-rtdb.asia-southeast1.firebasedatabase.app",
    // projectId: "ciputrapatroli",
    // storageBucket: "ciputrapatroli.firebasestorage.app",
    // messagingSenderId: "63894988040",
    // appId: "1:63894988040:web:f330974b0e4dac257f78bd"
    //     };

    // const app = initializeApp(firebaseConfig);
    // const auth = getAuth(app);

    
    @if($firebaseUserData)
        const firebaseUserData = @json($firebaseUserData);

        document.getElementById('user-name').textContent = firebaseUserData.displayName || 'N/A';
        document.getElementById('user-email').textContent = firebaseUserData.email;
        document.getElementById('user-uid').textContent = firebaseUserData.uid;
    @else
        window.location.href = '/login'; 
    @endif


</script>

<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-auth.js"></script>


@endsection
