@php
if (isset($object)) {
    $title = 'Edit';
    $actionUrl = route('page.update', $object->id);
} else {
    $title = 'Create';
    $actionUrl = route('page.store');
}

$viewData = [
    'title' => $title . ' Page',
    'breadcrumbs' => [
        'Page',
        $title,
    ],
];

@endphp

@extends('layouts.app', $viewData)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $viewData['title'] }}</h4>
            </div>
            <div class="card-content">
                <div class="card-body">

                    <form class="form form-horizontal" method="POST" action="{{ $actionUrl }}"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($object))
                        {{ method_field('PATCH') }}
                        @endif
                        <div class="form-body">
                            <div class="row">

                                @foreach ($fields as $key => $field)
                                <div class="{{ $field['class'] }}" id="{{ $key }}">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <span>{{ $field['label'] }}</span>
                                        </div>
                                        <div class="col-md-10">
                                            <div
                                                class="position-relative @if(isset($field['icon'])) has-icon-left @endif">
                                                {!! getFieldInput($key, $field, isset($object) ? $object->$key : (isset($field['default']) ? $field['default'] : old($key))) !!}
                                                @if(isset($field['icon']))
                                                <div class="form-control-position">
                                                    <i class="{{ $field['icon'] }}"></i>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <button type="submit"
                                        class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/editors/quill/quill.snow.css') }}">
@endpush

@push('after_scripts')
<script src="{{ asset('app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $(".select2").select2();

        // Quill Editor
        var quill = new Quill('.editor', {
            modules: {
                'syntax': true,
                'toolbar': [
                    [{
                        'font': []
                    }, {
                        'size': []
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }],
                    [{
                        'script': 'super'
                    }, {
                        'script': 'sub'
                    }],
                    [{
                        'header': '1'
                    }, {
                        'header': '2'
                    }, 'blockquote', 'code-block'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['direction', {
                        'align': []
                    }],
                    ['link'],
                ]
            },
            theme: 'snow'
        });

        function syncEditor() {
            var contents = $(".ql-editor").html();
            $('textarea[name=content_text]').val(contents);
        }

        quill.on('text-change', function () {
            syncEditor();
        });
        syncEditor();

    });

</script>
@endpush
