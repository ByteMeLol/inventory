@session('success')
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 3000,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timerProgressBar: true,
    });
</script>
    

@endsession