<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek BPM</title>
    <link rel="stylesheet" href="{{ asset('css/cek-bpm.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="header">
        <h2>BPM Realtime</h2>
        <form action="{{ route('login.form') }}" method="GET">
            <button type="submit" class="back-login-button">Kembali ke Login</button>
        </form>
    </div>

    <div class="card">
        Nilai BPM: <span id="currentBpm">0</span>
    </div>

    <div id="chart-container">
        <canvas id="bpmChart" width="600" height="300"></canvas>
    </div>

    <script>
        let bpmData = [];
        let bpmLabels = [];

        const ctx = document.getElementById('bpmChart').getContext('2d');
        const bpmChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: bpmLabels,
                datasets: [{
                    label: 'BPM',
                    data: bpmData,
                    borderColor: 'rgba(0,123,255,1)',
                    backgroundColor: 'rgba(0,123,255,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        function fetchBpmData() {
            fetch("{{ route('cek.bpm.data') }}")
                .then(res => res.json())
                .then(data => {
                    if(data.length === 0) return;

                    // Ambil data terbaru untuk card
                    const latest = data[0];
                    document.getElementById('currentBpm').innerText = latest.bpm;

                    // Chart dari lama ke baru
                    bpmData.length = 0;
                    bpmLabels.length = 0;
                    data.reverse().forEach(item => {
                        bpmData.push(item.bpm);
                        bpmLabels.push(new Date(item.created_at).toLocaleTimeString());
                    });

                    bpmChart.update();
                });
        }

        fetchBpmData();
        setInterval(fetchBpmData, 2000); 
    </script>
</body>
</html>
