@extends('layouts.app')

@section('title', 'Assign Role to User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Assign Role to User: {{ $data->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.assign-role-update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ encrypt($data->id) }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Select Role</label>
                                    <select name="role" id="role" class="form-control" required>
                                        <option value="">Choose a role...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" 
                                                @if(in_array($role->name, $assignedRoles)) selected @endif>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">Assign Role</button>
                                    <a href="{{ route('super.user.view') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </div>
                        </div>

                        @if($data->roles->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Current Role Permissions</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Permission Name</th>
                                                <th>Guard</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data->getPermissionsViaRoles() as $permission)
                                            <tr>
                                                <td>{{ $permission->name }}</td>
                                                <td>{{ $permission->guard_name }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
