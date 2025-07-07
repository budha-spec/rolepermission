@extends('access::layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Manage Roles</h2>

    <!-- Add Role Form (Left) -->
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="row align-items-end">
            <div class="col-md-5 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Role name" value="{{ old('name') }}" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mt-2">Add Role</button>
                        </div>
                    </div>
                </div>
            </div>
            @if(Schema::hasTable('users'))
            <div class="col-md-7 mb-3 text-end">
                <a href="{{ route('user.list') }}" class="btn btn-success">
                    <i class="fa fa-users"></i> Assign Role to User
                </a>
            </div>
            @endif
        </div>
    </form>

    <!-- Role List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Roles & Assign Permission</h5>
        </div>
        <div class="card-body p-0">
            @if($roles->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $index => $role)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $role->name }}</td>
                            <td>
                                <div class="d-inline-flex align-items-center gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="left" title="Assign Permission">
                                        <i class="fa fa-user-shield"></i>
                                    </a>
                                    <!-- Delete Button -->
                                    <form id="deleteRole{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete Role">
                                            <i class="fa fa-trash m-1"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-3">
                <p class="mb-0 text-muted">No roles available.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection