const defaultLat = -7.29006450847514;
const defaultLng = 112.63497441222195;
const addPointBtn = document.getElementById('addPoint');
const addPointText = document.getElementById('addPointText');
const spinnerIcon = document.getElementById('spinnerIcon');
const ORS_API_KEY = document.getElementById('orsApiKey')?.getAttribute('data-key') || '';
const intervalInput = document.getElementById('interval_patroli');
const previewContainer = document.getElementById('previewInterval');
const intervalList = document.getElementById('intervalList');


let map, patrolPoints = [],
patrolPolyline = null,
animatedMarker = null;

document.addEventListener("DOMContentLoaded", function() {



        $('.select2').select2({
            placeholder: "Cari dan pilih...",
            width: '100%'
        });

        map = L.map('map').setView([defaultLat, defaultLng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        setTimeout(() => map.invalidateSize(), 1000);

        document.getElementById('addPoint').addEventListener('click', () => {
            addPointBtn.disabled = true;
            addPointBtn.classList.add('opacity-70', 'cursor-not-allowed');
            addPointText.innerText = 'Klik pada peta...';
            spinnerIcon.classList.remove('hidden');

            map.once('click', function(e) {
                const marker = L.marker(e.latlng, {
                    draggable: true
                }).addTo(map);

                marker.on('dragend', function() {
                    const newPos = marker.getLatLng();
                    patrolPoints[patrolPoints.length - 1].lat = newPos.lat;
                    patrolPoints[patrolPoints.length - 1].lng = newPos.lng;
                    updatePointsDisplay();
                    drawSnappedPolyline();
                });

                patrolPoints.push({
                    lat: e.latlng.lat,
                    lng: e.latlng.lng,
                    marker
                });
                updatePointsDisplay();
                drawSnappedPolyline();

                addPointBtn.disabled = false;
                addPointBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                addPointText.innerText = '+ Tambah Titik';
                spinnerIcon.classList.add('hidden');
            });
        });

        
    });

    

    function updatePointsDisplay() {
        const list = document.getElementById('pointsList');
        list.innerHTML = '';
        const titikOnly = [];
        patrolPoints.forEach((point, index) => {
            const li = document.createElement('li');
            li.innerHTML = `Lat: ${point.lat.toFixed(5)}, Lng: ${point.lng.toFixed(5)} 
                <button onclick="removePoint(${index})" type="button" class="ml-2 text-red-500 hover:text-red-700 text-xs font-semibold underline">Hapus</button>`;
            list.appendChild(li);
            titikOnly.push({
                lat: point.lat,
                lng: point.lng
            });
        });
        document.getElementById('titik_patrol').value = JSON.stringify(titikOnly);
    }

    window.removePoint = function(index) {
        map.removeLayer(patrolPoints[index].marker);
        patrolPoints.splice(index, 1);
        updatePointsDisplay();
        drawSnappedPolyline();
    }

    async function drawSnappedPolyline() {
        if (patrolPolyline) map.removeLayer(patrolPolyline);
        if (animatedMarker) map.removeLayer(animatedMarker);
        if (patrolPoints.length < 2) return;

        const coords = patrolPoints.map(p => [p.lng, p.lat]);

        try {
            const response = await fetch(`/api/get-routing`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ coordinates: coords, profile: 'foot-walking'})
            });

            const data = await response.json();
            const snappedCoords = data.features[0].geometry.coordinates.map(c => [c[1], c[0]]);

            patrolPolyline = L.polyline(snappedCoords, {
                color: '#3b82f6',
                weight: 6,
                opacity: 0.8,
                className: 'leaflet-polyline-glow'
            }).addTo(map);

            animateMarker(snappedCoords);

        } catch (err) {
            console.error('ORS error:', err);
            showToast('Gagal mengambil jalur dari OpenRouteService', 'error');
        }
    }

    function animateMarker(path) {
        if (path.length < 2) return;

        let i = 0;
        animatedMarker = L.marker(path[0], {
            icon: L.divIcon({
                className: 'animated-marker',
                html: `<div class="w-4 h-4 bg-blue-500 rounded-full shadow-lg animate-ping"></div>`,
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            })
        }).addTo(map);

        const interval = setInterval(() => {
            if (i >= path.length) {
                clearInterval(interval);
                return;
            }
            animatedMarker.setLatLng(path[i]);
            i++;
        }, 150);
    }

    function showToast(message, type = 'error') {
        const toast = document.createElement('div');
        toast.className = `px-4 py-3 rounded-xl text-white shadow-lg animate-pop-in ${type === 'error' ? 'bg-red-600' : 'bg-green-600'}`;
        toast.innerText = message;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    function validateForm() {
        const lokasi = document.getElementById('lokasi').value;
        const pagi = document.getElementById('satpam_shift_pagi').value;
        const malam = document.getElementById('satpam_shift_malam').value;
        const titik = document.getElementById('titik_patrol').value;
        const interval_patroli = document.getElementById('interval_patroli').value;

        let titikParsed = [];
        try {
            titikParsed = JSON.parse(titik);
        } catch (e) {
            showToast('Format titik patroli tidak valid.');
            return false;
        }
        
        if (!lokasi || !pagi || !malam || titikParsed.length === 0 || !interval_patroli) {
            showToast('Semua field wajib diisi dan minimal satu titik patroli harus ditambahkan.');
            return false;
        }
    }

    intervalInput.addEventListener('input', function () {
        const interval = parseInt(this.value);
        intervalList.innerHTML = ''; // reset

        if (isNaN(interval) || interval <= 0) {
            previewContainer.classList.add('hidden');
            return;
        }

        previewContainer.classList.remove('hidden');

        const startHour = 0;
        const endHour = 24;

        for (let hour = startHour; hour < endHour; hour += interval) {
            const nextHour = hour + interval;
            let startFormatted = formatTime(hour);
            let endFormatted = formatTime(nextHour >= 24 ? 0 : nextHour);
            const display = `${startFormatted} - ${endFormatted}`;

            const badge = document.createElement('div');
            badge.className = "bg-blue-100 text-blue-800 text-sm font-semibold px-4 py-2 rounded-2xl shadow-md hover:bg-blue-200 transition-all duration-300 animate-pop-in";
            badge.textContent = display;

            intervalList.appendChild(badge);
        }
    });

    function formatTime(hour) {
        const hh = hour.toString().padStart(2, '0');
        return `${hh}:00`;
    }