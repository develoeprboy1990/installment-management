<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naya Store Banain — SuperAdmin</title>
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
        .logout-btn { margin-left: auto; background: none; border: none; color: #ef4444; cursor: pointer; font-size: 16px; padding: 4px 8px; border-radius: 6px; }
        .main { margin-left: 260px; padding: 32px; max-width: 900px; }
        .page-header { margin-bottom: 32px; }
        .page-header h2 { font-size: 26px; font-weight: 700; color: #f1f5f9; }
        .page-header p { color: #64748b; font-size: 14px; margin-top: 4px; }
        .card { background: #1e293b; border: 1px solid #1e3a5f; border-radius: 16px; overflow: hidden; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #1e3a5f; }
        .card-header h3 { font-size: 16px; font-weight: 600; color: #f1f5f9; }
        .card-body { padding: 28px; }
        .section-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6366f1; margin: 24px 0 16px; padding-bottom: 8px; border-bottom: 1px solid #1e3a5f; }
        .section-title:first-child { margin-top: 0; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: span 2; }
        label { font-size: 13px; font-weight: 600; color: #94a3b8; }
        input, select, textarea {
            background: rgba(15,23,42,0.6);
            border: 1px solid #1e3a5f;
            border-radius: 10px;
            padding: 11px 14px;
            color: #e2e8f0;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        select option { background: #1e293b; }
        .error { color: #f87171; font-size: 12px; margin-top: 4px; }
        .form-actions { display: flex; gap: 12px; margin-top: 28px; padding-top: 24px; border-top: 1px solid #1e3a5f; }
        .btn { padding: 11px 22px; border-radius: 10px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s; border: none; }
        .btn-primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-secondary { background: rgba(30,58,95,0.5); color: #94a3b8; border: 1px solid #1e3a5f; }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171; border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; font-size: 14px; }
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
        <a href="{{ route('superadmin.tenants.index') }}" class="nav-item"><i class="fas fa-store"></i> All Stores</a>
        <a href="{{ route('superadmin.tenants.create') }}" class="nav-item active"><i class="fas fa-plus-circle"></i> Add New Store</a>
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
        <h2><i class="fas fa-plus-circle" style="color:#818cf8;margin-right:10px"></i>Naya Store Banain</h2>
        <p>Naye store ka naam, email aur pehle admin user ki details bharain</p>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Errors:</strong>
            <ul style="margin:6px 0 0 16px">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>Store Information</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.tenants.store') }}">
                @csrf

                {{-- Store Details --}}
                <div class="section-title"><i class="fas fa-store"></i> Store / Branch Details</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Store Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ali Electronics" required>
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Store Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="store@example.com">
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="03001234567">
                        @error('phone') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="active" {{ old('status','active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group full">
                        <label>Address</label>
                        <textarea name="address" rows="2" placeholder="Store ka address">{{ old('address') }}</textarea>
                        @error('address') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Admin User Details --}}
                <div class="section-title"><i class="fas fa-user-shield"></i> Pehle Admin User Ki Details</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Admin Name *</label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" placeholder="Admin ka naam" required>
                        @error('admin_name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Admin Email *</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" placeholder="admin@store.com" required>
                        @error('admin_email') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="admin_password" placeholder="Min 8 characters" required>
                        @error('admin_password') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm Password *</label>
                        <input type="password" name="admin_password_confirmation" placeholder="Password dobara likhain" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Store Banain
                    </button>
                    <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Wapas
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
