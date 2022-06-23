@extends('Layouts.mainlayout')
@section('content')

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Admins List Data</h6>
        @if ($message = Session::get('success'))
            <div class="alert alert-success m-3">
                {{ $message }}
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Admins Data</h3>
                <a href="{{ env('APP_URL') }}/mynotes-admins/create" class="btn btn-primary mb-3">Add+</a>
            </div>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <a href="{{ env('APP_URL') }}/mynotes-admins/{{ $admin->id }}" class="btn btn-info">Show</a>
                            <a href="{{ env('APP_URL') }}/mynotes-admins/{{ $admin->id }}/edit" class="btn btn-warning">Edit</a>
                            <form action="{{ env('APP_URL') }}/mynotes-admins/{{ $admin->id }}" method="POST" class="d-inline-block">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are You Sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
