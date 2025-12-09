<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>

<header>
    <div>BPM Tracker - Hallo, {{ $user->name }}</div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Keluar</button>
    </form>
</header>

<div class="main">

    <div class="filter-actions">
        {{-- Filter --}}
        <form method="GET" action="{{ route('dashboard') }}" class="filter-form">
            <label>Filter:</label>
            <select name="record_id" id="filterRecord">
                <option value="">Semua</option>
                @foreach($recordIds as $rid)
                    <option value="{{ $rid }}" @if(request('record_id') == $rid) selected @endif>Record {{ $rid }}</option>
                @endforeach
            </select>
            <button type="submit">Filter</button>
        </form>

        {{-- Export & Delete --}}
        <div class="action-buttons">
            <a href="{{ route('dashboard.export') }}" class="button export">Export CSV</a>
            <form action="{{ route('dashboard.truncate') }}" method="POST" onsubmit="return confirm('Delete all records?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete">Hapus Semua Data</button>
            </form>
        </div>
    </div>



    {{-- Realtime Card --}}
    <h3>Realtime Data</h3>
    <div class="card" id="realtimeCard">
        @if($latestData)
            <p><strong>Age:</strong> {{ $latestData->age }}</p>
            <p><strong>Gender:</strong> {{ $latestData->gender }}</p>
            <p><strong>Status:</strong> {{ $latestData->status }}</p>
            <p><strong>BPM:</strong> {{ $latestData->bpm }}</p>
            <p><strong>Record ID:</strong> {{ $lastRecordId }}</p>
        @else
            <p>Data tidak tersedia.</p>
        @endif
    </div>

    {{-- Chart + Table Grid --}}
    <div class="dashboard-grid">

        {{-- Chart --}}
        <div class="chart-container">
            <h4>BPM Chart (Record ID: {{ $lastRecordId }})</h4>
            <canvas id="bpmChart" width="400" height="300"></canvas>
        </div>

        {{-- Table --}}
        <div class="table-container">
            <h4>Record Data (Record ID: {{ $lastRecordId }})</h4>
            <table id="recordsTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Usia</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                        <th>BPM</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $index => $record)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $record->age }}</td>
                        <td>{{ $record->gender }}</td>
                        <td>{{ $record->status }}</td>
                        <td>{{ $record->bpm }}</td>
                        <td>{{ $record->created_at }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

<script>
function formatDateLabel(datetime){
    const d = new Date(datetime);
    const day = String(d.getDate()).padStart(2,'0');
    const month = String(d.getMonth()+1).padStart(2,'0'); // bulan 0-11
    const year = d.getFullYear();
    return `${day}-${month}-${year}`;
}

let bpmChart;
function renderChart(labels, data){
    // Format labels menjadi tanggal-bulan-tahun
    const formattedLabels = labels.map(l => formatDateLabel(l));

    const ctx = document.getElementById('bpmChart').getContext('2d');
    if(bpmChart) bpmChart.destroy();
    bpmChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: formattedLabels,
            datasets: [{
                label: 'BPM',
                data: data,
                backgroundColor: 'rgba(0,123,255,0.2)',
                borderColor: 'rgba(0,123,255,1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
}

// Initial render
renderChart(
    {!! $records->pluck('created_at') !!},
    {!! $records->pluck('bpm') !!}
);


// Realtime update setiap 2 detik
setInterval(() => {
    $.get("{{ route('dashboard.realtime') }}", { record_id: $("#filterRecord").val() }, function(res){
        if(res.records.length > 0){
            // Update Card
            const latest = res.latest;
            $("#realtimeCard").html(`
                <p><strong>Age:</strong> ${latest.age}</p>
                <p><strong>Gender:</strong> ${latest.gender}</p>
                <p><strong>Status:</strong> ${latest.status}</p>
                <p><strong>BPM:</strong> ${latest.bpm}</p>
                <p><strong>Record ID:</strong> ${latest.record_id}</p>
            `);

            // Update Table
            let tableHtml = '';
            res.records.forEach(r => {
                tableHtml += `<tr>
                    <td>${r.id}</td>
                    <td>${r.age}</td>
                    <td>${r.gender}</td>
                    <td>${r.status}</td>
                    <td>${r.bpm}</td>
                    <td>${r.created_at}</td>
                </tr>`;
            });
            $("#recordsTable tbody").html(tableHtml);

            // Update Chart
            const labels = res.records.map(r => r.created_at);
            const data = res.records.map(r => r.bpm);
            renderChart(labels, data);
        }
    });
}, 2000);

</script>

</body>
</html>
