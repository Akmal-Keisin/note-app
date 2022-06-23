@extends('Layouts.mainlayout')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Admin Detail</h6>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <div class="mr-3">
                        <img style="max-width: 150px" src="{{ $admin->image }}" alt="{{ $admin->name }}">
                    </div>
                    <table class="table-responsive">
                        <tbody>
                            <tr>
                                <th><b>Name</b></th>
                                <td> : </td>
                                <td>{{ $admin->name }}</td>
                            </tr>
                            <tr>
                                <th><b>Email</b></th>
                                <td> : </td>
                                <td>{{ $admin->email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
