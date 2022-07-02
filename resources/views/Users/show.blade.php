@extends('Layouts.mainlayout')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">User Detail</h6>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <div class="mr-3">
                        <img src="@if ($user->image == null) {{ env('APP_URL') . '/images/default.png' }} @else {{ $user->image }} @endif" style="max-width: 150px" alt="{{ $user->name }}">
                    </div>
                    <table class="table-responsive">
                        <tbody>
                            <tr>
                                <th><b>Name</b></th>
                                <td> : </td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th><b>Email</b></th>
                                <td> : </td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th><b>Phone Number</b></th>
                                <td> : </td>
                                <td>{{ $user->phone_number }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
