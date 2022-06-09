@extends('Layouts.mainlayout')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Add Admin</h6>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="/mynotes-admins" method="POST">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="">
                                    <label for="name" class="form-label">Name :</label>
                                    <input type="text" class="form-control" name="name" placeholder="Admin Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="">
                                    <label for="email" class="form-label">Email :</label>
                                    <input type="email" class="form-control" name="email" placeholder="Admin Email">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password :</label>
                        <input type="password" class="form-control" name="password" placeholder="Admin Password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password :</label>
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
