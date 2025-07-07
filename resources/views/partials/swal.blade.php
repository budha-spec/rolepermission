@if (session('alert'))
<script>
    window._swalFlashData = @json(session('alert'));
</script>
@endif