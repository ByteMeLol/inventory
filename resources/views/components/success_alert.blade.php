@session('success')
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ __('Success') }}',
        text: '{{ __(session('success')) }}',
        timer: 3500,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timerProgressBar: true,
    });
</script>
@endsession

@session('error')
<script>
    Swal.fire({
        icon: 'error',
        title: '{{ __('Error') }}',
        text: '{{ __(session('error')) }}',
        timer: 4500,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timerProgressBar: true,
    });
</script>
@endsession
