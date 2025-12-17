<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivitas Administrator - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
</head>
<body>
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="section">
                <div class="section-header">
                    <div class="section-title">Aktivitas Administrator</div>
                </div>

                <div style="background:white;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-bottom:20px;">
                    <form method="GET" style="display:flex;gap:15px;flex-wrap:wrap;align-items:end;">
                        <div>
                            <label>Dari Tanggal</label>
                            <input type="date" name="dari" value="{{ request('dari') }}" class="form-control" style="padding:8px;border:1px solid #ddd;border-radius:4px;">
                        </div>
                        <div>
                            <label>Sampai Tanggal</label>
                            <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control" style="padding:8px;border:1px solid #ddd;border-radius:4px;">
                        </div>
                        <div>
                            <button type="submit" style="background:#007bff;color:white;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;">Filter</button>
                            @if(request()->hasAny(['dari','sampai']))
                                <a href="{{ route('aktivitas-administrator') }}" style="margin-left:10px;color:#dc3545;text-decoration:underline;">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Detail</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $i => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $i }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td><strong>{{ $log->user_name }}</strong></td>
                            <td><span style="color:#007bff;font-weight:600;">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td><small>{{ $log->ip_address }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;padding:40px;color:#666;">Belum ada aktivitas</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div style="margin-top:20px;">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>