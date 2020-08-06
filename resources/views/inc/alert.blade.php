@push('after_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endpush

@push('before_scripts')
    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
@endpush

{{-- Success message --}}
@if (session('status'))
    @push('before_scripts')
        <script>
            Swal.fire({
                type: 'success',
                title: 'Success',
                text: '{{ session('status') }}'
            })
        </script>

    @endpush
@endif
{{-- Success message --}}

{{-- Error message --}}
@if($errors->any())

    @push('after_styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.css') }}">
    @endpush

    @push('after_scripts')
        <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>

        <script>
            @foreach($errors->all() as $item)
            toastr.error('{{ $item }}', 'Oops', { "timeOut": 0 });
            @endforeach
        </script>
    @endpush

@endif
{{-- Error messages --}}
