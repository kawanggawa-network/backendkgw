@extends('layouts.app', [
    'title' => 'Dashboard',
    'breadcrumbs' => [
        'Dashboard'
    ],
])

@section('content')
<section id="dashboard-analytics">

    <div class="row">

        <div class="col-md-3">
            <div class="card">
                <div class="card-header d-flex flex-column align-items-start pb-0">
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-layers text-primary font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="text-bold-700 mt-1">{{ $userCount }}</h2>
                    <p class="mb-0">Jumlah User</p>
                    <br>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
