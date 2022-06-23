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
                <form action="{{ env('APP_URL') }}/mynotes-admins/{{ $admin->id }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="">
                                    <label for="name" class="form-label">Name :</label>
                                    <input type="text" class="form-control" name="name" placeholder="Admin Name" value="{{ old('name', $admin->name) }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="">
                                    <label for="email" class="form-label">Email :</label>
                                    <input type="email" class="form-control" name="email" placeholder="Admin Email" value="{{ old('email', $admin->email) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Photo Profile :</label>
                        <input type="file" class="form-control-file" name="image" placeholder="Photo Profile">
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
                        <button type="submit" class="btn btn-warning">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
