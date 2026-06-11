<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Store Details — SuperAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin:0;padding:0;box-sizing:border-box; }
        body { font-family:'Inter',sans-serif;background:#0f172a;color:#e2e8f0;min-height:100vh; }
        .sidebar { position:fixed;top:0;left:0;width:260px;height:100vh;background:linear-gradient(160deg,#1e293b 0%,#0f172a 100%);border-right:1px solid #1e3a5f;display:flex;flex-direction:column;z-index:100; }
        .sidebar-brand { padding:24px 20px;border-bottom:1px solid #1e3a5f; }
        .sidebar-brand .badge { display:inline-block;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;font-size:10px;font-weight:700;letter-spacing:1px;padding:2px 8px;border-radius:20px;text-transform:uppercase;margin-bottom:8px; }
        .sidebar-brand h1 { font-size:18px;font-weight:700;color:#f1f5f9; }
        .sidebar-nav { padding:16px 12px;flex:1; }
        .nav-label { font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#475569;padding:8px 8px 4px; }
        .nav-item { display:flex;align-items:center;gap:12px;padding:11px 12px;border-radius:10px;color:#94a3b8;text-decoration:none;font-size:14px;font-weight:500;transition:all 0.2s;margin-bottom:2px; }
        .nav-item:hover,.nav-item.active { background:linear-gradient(135deg,rgba(99,102,241,0.15),rgba(139,92,246,0.1));color:#a5b4fc; }
        .nav-item i { width:18px;text-align:center; }
        .sidebar-footer { padding:16px;border-top:1px solid #1e3a5f; }
        .user-info { display:flex;align-items:center;gap:12px; }
        .user-avatar { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:white; }
        .user-name { font-size:13px;font-weight:600;color:#e2e8f0; }
        .user-role { font-size:11px;color:#6366f1; }
        .logout-btn { margin-left:auto;background:none;border:none;color:#ef4444;cursor:pointer;font-size:16px;padding:4px 8px;border-radius:6px; }
        .main { margin-left:260px;padding:32px; }
        .page-header { margin-bottom:28px;display:flex;align-items:flex-start;justify-content:space-between; }
        .page-header h2 { font-size:26px;font-weight:700;color:#f1f5f9; }
        .page-header p { color:#64748b;font-size:14px;margin-top:4px; }
        .stats-row { display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:28px; }
        .stat-card { background:#1e293b;border:1px solid #1e3a5f;border-radius:12px;padding:20px;text-align:center; }
        .stat-val { font-size:32px;font-weight:800;color:#f1f5f9; }
        .stat-lbl { font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#64748b;margin-top:4px; }
        .card { background:#1e293b;border:1px solid #1e3a5f;border-radius:16px;overflow:hidden;margin-bottom:24px; }
        .card-header { padding:18px 24px;border-bottom:1px solid #1e3a5f; }
        .card-header h3 { font-size:15px;font-weight:600;color:#f1f5f9; }
        .info-grid { display:grid;grid-template-columns:1fr 1fr;gap:0; }
        .info-item { padding:16px 24px;border-bottom:1px solid rgba(30,58,95,0.5); }
        .info-item:nth-child(odd) { border-right:1px solid rgba(30,58,95,0.5); }
        .info-label { font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#475569;margin-bottom:4px; }
        .info-value { font-size:14px;color:#e2e8f0; }
        table { width:100%;border-collapse:collapse; }
        thead th { padding:11px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#475569;background:rgba(15,23,42,0.5);text-align:left; }
        tbody td { padding:14px 20px;border-top:1px solid rgba(30,58,95,0.5);font-size:14px;color:#94a3b8; }
        .badge-status { display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600; }
        .badge-active { background:rgba(16,185,129,0.15);color:#34d399; }
        .badge-inactive { background:rgba(100,116,139,0.15);color:#94a3b8; }
        .badge-suspended { background:rgba(239,68,68,0.15);color:#f87171; }
        .btn { padding:9px 18px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;cursor:pointer;transition:all 0.2s;border:none; }
        .btn-warning { background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.2); }
        .btn-secondary { background:rgba(30,58,95,0.5);color:#94a3b8;border:1px solid #1e3a5f; }
        .text-muted { color:#64748b; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="badge">⚡ SuperAdmin</div>
        <h1>Control Panel</h1>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Navigation</div>
        <a href="{{ route('superadmin.dashboard') }}" class="nav-item"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="{{ route('superadmin.tenants.index') }}" class="nav-item active"><i class="fas fa-store"></i> All Stores</a>
        <a href="{{ route('superadmin.tenants.create') }}" class="nav-item"><i class="fas fa-plus-circle"></i> Add New Store</a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">SuperAdmin</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </div>
</div>

<div class="main">
    <div class="page-header">
        <div>
            <h2><i class="fas fa-store" style="color:#818cf8;margin-right:10px"></i>{{ $tenant->name }}</h2>
            <p>Tenant #{{ $tenant->id }} — {{ $tenant->slug }}</p>
        </div>
        <div style="display:flex;gap:10px">
            <a href="{{ route('superadmin.tenants.edit', $tenant) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-val" style="color:#60a5fa">{{ $summary['total_users'] }}</div>
            <div class="stat-lbl">Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="color:#34d399">{{ $summary['total_customers'] }}</div>
            <div class="stat-lbl">Customers</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="color:#fbbf24">{{ $summary['total_purchases'] }}</div>
            <div class="stat-lbl">Purchases</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="color:#818cf8">{{ $summary['total_installments'] }}</div>
            <div class="stat-lbl">Installments</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="color:#f87171">{{ $summary['pending_installments'] }}</div>
            <div class="stat-lbl">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="color:#34d399">{{ $summary['paid_installments'] }}</div>
            <div class="stat-lbl">Paid</div>
        </div>
    </div>

    {{-- Store Info --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-info-circle" style="color:#818cf8;margin-right:8px"></i>Store Information</h3></div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Store Name</div>
                <div class="info-value">{{ $tenant->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    @if($tenant->status === 'active')
                        <span class="badge-status badge-active">Active</span>
                    @elseif($tenant->status === 'suspended')
                        <span class="badge-status badge-suspended">Suspended</span>
                    @else
                        <span class="badge-status badge-inactive">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $tenant->email ?? '—' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $tenant->phone ?? '—' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Created</div>
                <div class="info-value">{{ $tenant->created_at->format('d M Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Subscription Expires</div>
                <div class="info-value">{{ $tenant->subscription_expires_at?->format('d M Y') ?? 'No Expiry' }}</div>
            </div>
        </div>
    </div>

    {{-- Users --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-users" style="color:#818cf8;margin-right:8px"></i>Store Users ({{ $users->count() }})</h3></div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="text-muted">{{ $user->id }}</td>
                    <td style="color:#e2e8f0;font-weight:500">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->implode(', ') ?: '—' }}</td>
                    <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
