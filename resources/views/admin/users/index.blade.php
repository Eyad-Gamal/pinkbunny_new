@extends('layouts.admin')
@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
    <div class="toolbar">
        <div class="toolbar-left">
            <form method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." class="form-input" style="width: 300px;">
                <button type="submit" class="btn btn-outline btn-sm">Search</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead><tr>
                    <th>User</th>
                    <th>Contact</th>
                    <th>Orders</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-1">
                                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <strong class="text-primary">{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 12px; color: var(--text-secondary);">{{ $user->email }}</span>
                                <br><span style="font-size: 11px; color: var(--text-muted);">{{ $user->phone }}</span>
                            </td>
                            <td style="color: var(--text-secondary);">{{ $user->orders_count }}</td>
                            <td>
                                @if($user->hasRole('admin'))<span class="badge badge-purple">Admin</span>
                                @else<span class="badge badge-info">Customer</span>@endif
                            </td>
                            <td><span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td style="color: var(--text-muted); font-size: 12px;">{{ $user->created_at->format('M d, Y') }}</td>
                            <td><a href="{{ route('admin.users.show', $user->id) }}" class="link-action">View →</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination">{{ $users->links() }}</div>
@endsection
