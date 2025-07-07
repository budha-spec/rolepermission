@extends('access::layouts.app')

@section('content')
<div class="container">

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        <fieldset class="border p-3 rounded">
            <legend class="float-none w-auto px-2">Edit User</legend>
            <div class="mb-3">
                <label>Name <span>*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')<span class="text-danger">{{$message}}</span>@enderror
            </div>

            <div class="mb-3">
                <label>Email <span>*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')<span class="text-danger">{{$message}}</span>@enderror
            </div>

            <div class="mb-3">
                <label>Role <span>*</span></label>
                <select id="role" name="role_id" class="form-control form-select">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                    <option value="{{$role->id}}" @if(!empty($user->role_id) && $user->role_id==$role->id) selected @endif>{{$role->name}}</option>
                    @endforeach                
                </select>
                @error('role_id')<span class="text-danger">{{$message}}</span>@enderror
            </div>
            <a href="{{ route('user.list') }}" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </fieldset>
    </form>
</div>
@endsection