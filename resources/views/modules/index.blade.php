@extends('access::layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Manage Modules</h2>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <!-- Add Module Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h6>Add Module</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('modules.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Module Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <span class="text-danger">{{$message}}</span> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Parent Module (Optional)</label>
                    <select name="parent_id" class="form-select">
                        <option value="">None</option>
                        @foreach ($parentModules as $module)
                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add Module</button>
            </form>
        </div>
    </div>

    <!-- List of Modules -->
    <div class="card">
        <div class="card-header">
            <h6>Module List (Click on Sub module to edit)</h6>
        </div>
        <div class="card-body">
            @if(!blank($parentModules))
            <div class="row">
                @foreach ($parentModules as $module)
                <div class="col-12 col-md-4 mb-4">
                    <div class="card {{$module->children->count() ? 'auto' : ''}}">
                        <div class="card-body">
                            <strong>{{ $module->name }}</strong>
                            @if ($module->children->count())
                            <ul class="list-group mt-2">
                                @foreach ($module->children as $sub)
                                <li class="list-group-item py-1 d-flex justify-content-between align-items-center">
                                    <a href="#" id="subModule_{{$sub->id}}" data-parent-value="{{$module->id}}" data-child-value="{{$sub->id}}" class="editable" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Click to edit...">{{ $sub->name }}</a>
                                    <a href="javascript:void(0)" class="float-end delete-module" data-id="{{$sub->id}}" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete">
                                        <img src="{{ asset('budhaspec/rolepermission/images/delete-icon.png') }}" alt="Delete" width="30" height="30">
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p><strong>No modules available yet.</strong></p>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ url('rolepermission-assets/js/jquery.jeditable.min.js') }}"></script>
<script type="text/javascript">
    $('.editable').on('click', function () {
        const tooltip = bootstrap.Tooltip.getInstance(this);
        if (tooltip) {
            tooltip.hide();
            tooltip.dispose();
        }
        const element = this;

        const observer = new MutationObserver(() => {
            if (!element.querySelector('input')) {
                element.setAttribute('data-bs-toggle', 'tooltip');
                initTooltips();
                observer.disconnect();
            }
        });

        observer.observe(element, { childList: true, subtree: true });
    });
    $('.editable').editable(`{{ route('module.child-update') }}`, {
        type      : 'text',
        submit    : 'Save',
        cancel    : 'Cancel',
        name      : 'name',
        indicator : 'Saving…',
        submitcssclass: 'btn btn-sm btn-success me-2 mt-1',
        cancelcssclass: 'btn btn-sm btn-danger mt-1',
        width   : '95%',
        height  : 'auto',
        method: 'POST',
        dataType: 'json',
        submitdata: function (value, settings) {
            return {
                _token: $('meta[name="csrf-token"]').attr('content'),
                module_id: $(this).data('child-value'),
                parent_id: $(this).data('parent-value')
            };
        },
        callback: function (response, settings) {
            this.setAttribute('title', 'Click to edit...');
            initTooltips();
            var result = $.parseJSON(response);
            if ((result.status && result.status==true) && result.data!==undefined) {
                $(this).html(result.data);

                showAlert('success', 'Module has been updated!');
            } else {
                showAlert('error', 'Something went wrong!');
            }
        },
        onerror: function(settings, original, xhr) {
            let response = JSON.parse(xhr.responseText);
            if (response && response.errors) {
                $.each(response.errors, function(k, errors) {
                    var [error] = errors;
                    if (error) {
                        showAlert('error', error);
                    }
                });
            }
            original.reset();
        }
    });
    $(document).on('click', '.delete-module', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var $self = $(this);
        if (confirm('Are you sure you want to delete?')) {
            var moduleId = $(this).data('id');
            if (moduleId) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : "{{ route('module.delete-child') }}",
                    data : { 'moduleId': moduleId },
                    type : 'POST',
                    dataType : 'json',
                    success : function(response) {
                        if (response.status && response.status=='success') {
                            $($self).parents('.list-group-item').remove();
                        }
                    }
                });
            }
        }
    }) 
</script>
@endpush
