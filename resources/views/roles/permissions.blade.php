@extends('access::layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Assign Permissions to: <span class="text-primary">{{ $role->name }}</span></h2>

    <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        @php $rolePermissions = !blank($role->permissions) ? $role->permissions->pluck('module_id')->toArray() : [];
        @endphp
        <fieldset class="border p-3 rounded">
            <legend class="float-none w-auto px-2">Modules/Sub Modules</legend>
          <div class="row" id="accordionGrid">
            @foreach ($modules as $index => $module)
            <div class="col-12 col-md-4 mb-3">
                <div class="accordion" id="accordion{{ $index }}">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                            aria-controls="collapse{{ $index }}">
                            <input class="form-check-input me-2 parent-checkbox" type="checkbox" name="permissions[{{$module->id}}]"
                            value="{{ $module->id }}" {{ in_array($module->id, $rolePermissions) ? 'checked' : '' }}>
                            <strong>{{ $module->name }}</strong>
                        </button>
                    </h2>
                    @if ($module->children->count())
                    <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                    aria-labelledby="heading{{ $index }}">
                    <div class="accordion-body">
                       @foreach ($module->children as $k => $sub)
                       <div class="form-check mb-1 ms-3">
                        <input class="form-check-input child-checkbox" id="permissions{{$index.$k}}" type="checkbox" name="permissions[{{$module->id}}][]"
                        value="{{ $sub->id }}" {{ in_array($sub->id, $rolePermissions) ? 'checked' : '' }}>
                        <label for="permissions{{$index.$k}}" class="form-check-label">{{ $sub->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach
</div>

<div class="mt-4">
    <a href="{{ route('roles.index') }}" class="btn btn-danger">Cancel</a>
    <button type="submit" class="btn btn-success">Save Permissions</button>
</div>
</fieldset>
</form>
</div>

{{-- Optional JS to auto-check child when parent is clicked --}}
@push('scripts')
<script>
    document.querySelectorAll('.parent-checkbox').forEach(parent => {
        parent.addEventListener('change', function () {
            let body = parent.closest('.accordion-item').querySelector('.accordion-body');
            if (!body) return;
            body.querySelectorAll('.child-checkbox').forEach(child => {
                child.checked = parent.checked;
            });
        });
    });
</script>
@endpush
@endsection