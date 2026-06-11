<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Stores — SuperAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; }
        .sidebar { position: fixed; top: 0; left: 0; width: 260px; height: 100vh; background: linear-gradient(160deg, #1e293b 0%, #0f172a 100%); border-right: 1px solid #1e3a5f; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-brand { padding: 24px 20px; border-bottom: 1px solid #1e3a5f; }
        .sidebar-brand .badge { display: inline-block; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; font-size: 10px; font-weight: 700; letter-spacing: 1px; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; margin-bottom: 8px; }
        .sidebar-brand h1 { font-size: 18px; font-weight: 700; color: #f1f5f9; }
        .sidebar-brand p { font-size: 12px; color: #64748b; margin-top: 2px; }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-label { font-size: 10px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #475569; padding: 8px 8px 4px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 11px 12px; border-radius: 10px; color: #94a3b8; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; margin-bottom: 2px; }
        .nav-item:hover, .nav-item.active { background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(139,92,246,0.1)); color: #a5b4fc; }
        .nav-item i { width: 18px; text-align: center; }
        .sidebar-footer { padding: 16px; border-top: 1px solid #1e3a5f; }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: white; }
        .user-name { font-size: 13px; font-weight: 600; color: #e2e8f0; }
        .user-role { font-size: 11px; color: #6366f1; }
        .logout-btn { margin-left: auto; background: none; border: none; color: #ef4444; cursor: pointer; font-size: 16px; padding: 4px 8px; border-radius: 6px; transition: background 0.2s; }
        .logout-btn:hover { background: rgba(239,68,68,0.1); }
        .main { margin-left: 260px; padding: 32px; }
        .page-header { margin-bottom: 32px; display: flex; align-items: flex-start; justify-content: space-between; }
        .page-header h2 { font-size: 26px; font-weight: 700; color: #f1f5f9; }
        .page-header p { color: #64748b; font-size: 14px; margin-top: 4px; }
        .btn { padding: 9px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.2s; border: none; }
        .btn-primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 7px; }
        .btn-success { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.2); }
        .btn-danger  { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .btn-warning { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
        .btn-info    { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.2); }
        .card { background: #1e293b; border: 1px solid #1e3a5f; border-radius: 16px; overflow: hidden; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #1e3a5f; display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 16px; font-weight: 600; color: #f1f5f9; }
        table { width: 100%; border-collapse: collapse; }
        thead th { padding: 12px 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #475569; background: rgba(15,23,42,0.5); text-align: left; }
        tbody td { padding: 16px 20px; border-top: 1px solid rgba(30,58,95,0.5); font-size: 14px; }
        tbody tr:hover { background: rgba(99,102,241,0.04); }
        .badge-status { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-active   { background: rgba(16,185,129,0.15); color: #34d399; }
        .badge-inactive { background: rgba(100,116,139,0.15); color: #94a3b8; }
        .badge-suspended{ background: rgba(239,68,68,0.15); color: #f87171; }
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #34d399; }
        .actions { display: flex; gap: 6px; flex-wrap: wrap; }
        .text-muted { color: #64748b; font-size: 13px; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="badge">⚡ SuperAdmin</div>
        <h1>Control Panel</h1>
        <p>Installment Management</p>
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
            <h2>All Stores</h2>
            <p>Sab registered stores ka list</p>
        </div>
        <a href="{{ route('superadmin.tenants.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Naya Store Banain
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-store" style="color:#818cf8;margin-right:8px"></i>Stores List ({{ $tenants->total() }} total)</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Store Name</th>
                    <th>Email / Phone</th>
                    <th>Users</th>
                    <th>Customers</th>
                    <th>Purchases</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                <tr>
                    <td class="text-muted">{{ $tenant->id }}</td>
                    <td>
                        <strong style="color:#f1f5f9">{{ $tenant->name }}</strong><br>
                        <span class="text-muted">{{ $tenant->slug }}</span>
                    </td>
                    <td>
                        <div class="text-muted">{{ $tenant->email ?? '—' }}</div>
                        <div class="text-muted">{{ $tenant->phone ?? '—' }}</div>
                    </td>
                    <td><span style="color:#60a5fa;font-weight:600">{{ $tenant->users_count }}</span></td>
                    <td><span style="color:#34d399;font-weight:600">{{ $tenant->customers_count }}</span></td>
                    <td><span style="color:#fbbf24;font-weight:600">{{ $tenant->purchases_count }}</span></td>
                    <td>
                        @if($tenant->status === 'active')
                            <span class="badge-status badge-active"><i class="fas fa-circle" style="font-size:6px"></i>Active</span>
                        @elseif($tenant->status === 'suspended')
                            <span class="badge-status badge-suspended"><i class="fas fa-circle" style="font-size:6px"></i>Suspended</span>
                        @else
                            <span class="badge-status badge-inactive"><i class="fas fa-circle" style="font-size:6px"></i>Inactive</span>
                        @endif
                    </td>
                    <td class="text-muted">{{ $tenant->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('superadmin.tenants.edit', $tenant) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('superadmin.tenants.toggle-status', $tenant) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $tenant->status === 'active' ? 'btn-danger' : 'btn-success' }}" title="Toggle Status">
                                    <i class="fas fa-{{ $tenant->status === 'active' ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('superadmin.tenants.destroy', $tenant) }}" style="display:inline"
                                  onsubmit="return confirm('{{ $tenant->name }} aur iska sab data delete hoga. Yakeen hai?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:#475569">
                        <i class="fas fa-store" style="font-size:32px;margin-bottom:12px;display:block;opacity:0.3"></i>
                        Koi store nahi — <a href="{{ route('superadmin.tenants.create') }}" style="color:#818cf8">Pehla store banain</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($tenants->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #1e3a5f">{{ $tenants->links() }}</div>
        @endif
    </div>
</div>
</body>
</html>
