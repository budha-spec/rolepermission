@extends('access::layouts.app')

@section('content')
<div class="container py-4">
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <!-- Users List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Users</h5>
        </div>
        <div class="card-body p-0">
            @if($users->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ !blank($user->role) ? $user->role->name : '-' }}</td>
                            <td>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-3">
                <p class="mb-0 text-muted">No users available.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection