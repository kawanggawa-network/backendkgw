@extends('layouts.app', [
    'title' => 'File Manager',
    'breadcrumbs' => [
        'File Manager'
    ],
])

@php
$dir = 'packages/barryvdh/elfinder';
$locale = 'id_ID';
@endphp

@push('after_styles')
<!-- elFinder CSS (REQUIRED) -->
<link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/elfinder.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/theme.css') }}">
@endpush

@section('content')
<div id="elfinder"></div>
@endsection

@push('after_scripts')
<!-- jQuery and jQuery UI (REQUIRED) -->
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

<!-- elFinder JS (REQUIRED) -->
<script src="{{ asset($dir.'/js/elfinder.min.js') }}"></script>

@if($locale)
    <!-- elFinder translation (OPTIONAL) -->
    <script src="{{ asset($dir."/js/i18n/elfinder.$locale.js") }}"></script>
@endif

<script>
$('#elfinder').elfinder({
    // set your elFinder options here
    @if($locale)
        lang: '{{ $locale }}', // locale
    @endif
    customData: { 
        _token: '{{ csrf_token() }}'
    },
    url : '{{ route("elfinder.connector") }}',  // connector URL
    soundPath: '{{ asset($dir.'/sounds') }}'
});
</script>
@endpush