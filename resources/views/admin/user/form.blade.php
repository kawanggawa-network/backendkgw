@php
if (isset($object)) {
    $viewData = [
        'title' => 'Edit User',
        'breadcrumbs' => [
            'Users',
            $object->email,
            'Edit',
        ],
    ];
} else {
    $viewData = [
        'title' => 'Add User',
        'breadcrumbs' => [
            'User',
            'Add',
        ]
    ];
}
@endphp

@extends('layouts.app', $viewData)

@section('content')
{{-- Form Start --}}
@php
if (isset($object)) {
    $actionUrl = route('users.update', $object->id);
} else {
    $actionUrl = route('users.store');
}
@endphp
<div class="row">
    <div class="{{ isset($object) ? "col-md-8" : "col-md-12" }}">
        <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">

            @if (isset($object))
            {{ method_field('PATCH') }}
            <input type="hidden" name="user_id" value="{{ $object->id }}" />
            @endif
        
            {{ csrf_field() }}
        
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $viewData['title'] }}</h4>
                </div>
                <br>
                <div class="card-body">
        
        
                    <div class="form-body">
                        <div class="row">
        
                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <span>Name</span>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="position-relative has-icon-left">
                                            <input type="text" class="form-control" name="name"
                                                value="{{ isset($object) ? $object->name : old('name') }}" placeholder="Name"
                                                autofocus>
                                            <div class="form-control-position">
                                                <i class="feather icon-user"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <span>Email</span>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="position-relative has-icon-left">
                                            <input type="email" class="form-control" name="email"
                                                value="{{ isset($object) ? $object->email : old('email') }}"
                                                placeholder="Email">
                                            <div class="form-control-position">
                                                <i class="feather icon-mail"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            {{-- <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <span>Username</span>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="position-relative has-icon-left">
                                                <input type="text" class="form-control" name="username"  value="{{ isset($object) ? $object->username : old('username') }}"
                            placeholder="Username">
                            <div class="form-control-position">
                                <i class="feather icon-user"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-12">
                <div class="form-group row">
                    <div class="col-md-2">
                        <span>Phone Number</span>
                    </div>
                    <div class="col-md-10">
                        <div class="position-relative has-icon-left">
                            <input type="text" class="form-control" name="phone_number"
                                value="{{ isset($object) ? $object->phone_number : old('phone_number') }}"
                                placeholder="Phone Number">
                            <div class="form-control-position">
                                <i class="feather icon-phone"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        
            <div class="col-12">
                <div class="form-group row">
                    <div class="col-md-2">
                        <span>Password</span>
                    </div>
                    <div class="col-md-10">
                        <div class="position-relative has-icon-left">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                            <div class="form-control-position">
                                <i class="feather icon-lock"></i>
                            </div>
                        </div>
                        @if (isset($object))
                        <small class="text-info">Leave it blank if you don't want to change password.</small>
                        @endif
                    </div>
                </div>
            </div>
        
        
            <div class="col-12">
                <div class="form-group row">
                    <div class="col-md-2">
                        <span>Password Conf.</span>
                    </div>
                    <div class="col-md-10">
                        <div class="position-relative has-icon-left">
                            <input type="password" class="form-control" name="password_confirmation"
                                placeholder="Password Confirmation">
                            <div class="form-control-position">
                                <i class="feather icon-lock"></i>
                            </div>
                        </div>
                        @if (isset($object))
                        <small class="text-info">Leave it blank if you don't want to change password.</small>
                        @endif
                    </div>
                </div>
            </div>
        
            {{-- <div class="col-12">
                <div class="form-group row">
                    <div class="col-md-2">
                        <span>PIN (6 digits)</span>
                    </div>
                    <div class="col-md-10">
                        <div class="position-relative has-icon-left">
                            <input type="password" class="form-control" name="pin" placeholder="PIN" minlength="6"
                                maxlength="6">
                            <div class="form-control-position">
                                <i class="feather icon-credit-card"></i>
                            </div>
                        </div>
                        @if (isset($object))
                        <small class="text-info">Leave it blank if you don't want to change PIN.</small>
                        @endif
                    </div>
                </div>
            </div>
        
        
            <div class="col-12">
                <div class="form-group row">
                    <div class="col-md-2">
                        <span>PIN Conf.</span>
                    </div>
                    <div class="col-md-10">
                        <div class="position-relative has-icon-left">
                            <input type="password" class="form-control" name="pin_confirmation" placeholder="PIN Confirmation">
                            <div class="form-control-position">
                                <i class="feather icon-credit-card"></i>
                            </div>
                        </div>
                        @if (isset($object))
                        <small class="text-info">Leave it blank if you don't want to change PIN.</small>
                        @endif
                    </div>
                </div>
            </div> --}}
        
            <div class="col-12">
                <div class="form-group row">
                    <div class="col-md-2">
                        <span>Roles</span>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            @foreach ($roles as $role)
                            <div class="col-sm-4">
                                <!-- Default unchecked -->
                                <div class="custom-control custom-checkbox roles">
                                    <input type="checkbox" class="custom-control-input" name="roles[]" id="role{{ $role->id }}"
                                        value="{{ $role->id }}"
                                        {{ isset($object) && $object->roles->pluck('name')->contains($role->name) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small>Do not choose anything if a normal user</small>
                    </div>
                </div>
            </div>
                
            {{-- <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <span>Gender</span>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="custom-control custom-radio roles">
                                                <input type="radio" class="custom-control-input" name="gender" value="male" id="gender-male" {{ isset($object) && $object->gender == 'male' ? 'checked' : '' }}>
            <label class="custom-control-label" for="gender-male">Male</label>
            </div>
            <div class="custom-control custom-radio roles">
                <input type="radio" class="custom-control-input" name="gender" value="female" id="gender-female"
                    {{ isset($object) && $object->gender == 'female' ? 'checked' : '' }}>
                <label class="custom-control-label" for="gender-female">Female</label>
            </div>
            </div>
            </div>
            </div> --}}
        
        
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Save</button>
            </div>
            </div>
            </div>
            </div>
            </div>
        </form>
    </div>

    {{-- Detail User --}}
    @if (isset($object))
    <div class="col-md-4">
            <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Detail</h4>
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ $object->photo_url }}" style="max-width: 50%;" class="rounded-circle img-border box-shadow-1">
                        </div>
                        <br>

                        @php
                        $details = [
                            'name' => 'Name',
                            'email' => 'Email',
                            'provider' => 'Login with Social Media',
                        ];
                        @endphp

                        @foreach ($details as $key => $label)
                        <div class="mt-1">
                            <h6 class="mb-0">{{ $label }}:</h6>
                            <p>{{ !is_null($object->$key) ? $object->$key : '-' }}</p>
                        </div>
                        @endforeach

                    </div>
                </div> 
        </div> 
    @endif
    {{-- Detail User --}}
</div>

@endsection

@push('after_scripts')
<script>
    function checkUserType() {
        if ($("input[name=type]:checked").val() == "public") {
            $("#subsribe-charge").hide();
        } else {
            $("#subsribe-charge").show();
        }
    }
    checkUserType();

    $("input[name=type]").on("change", function () {
        checkUserType();
    });

</script>
@endpush
