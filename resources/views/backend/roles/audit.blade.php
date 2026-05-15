@extends('admin.layouts.main')

@section('title', $title)
@section('breadcrumb-main', $title_main)
@section('breadcrumb-title', $title)
@section('breadcrumb-sub-title', $title_sub)

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Permission Audit Log</h5>
                <p class="text-muted mb-0">Track all permission changes and access attempts</p>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-select">
                            <option>All Actions</option>
                            <option>Permission Created</option>
                            <option>Permission Deleted</option>
                            <option>Role Assigned</option>
                            <option>Role Removed</option>
                            <option>Access Denied</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" placeholder="From Date">
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" placeholder="To Date">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Target</th>
                                <th>IP Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-03-19 22:15:30</td>
                                <td>admin@example.com</td>
                                <td>Permission Created</td>
                                <td>blog.create</td>
                                <td>192.168.1.100</td>
                                <td><span class="badge bg-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>2024-03-19 22:10:15</td>
                                <td>admin@example.com</td>
                                <td>Role Assigned</td>
                                <td>Editor Role → john@example.com</td>
                                <td>192.168.1.100</td>
                                <td><span class="badge bg-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>2024-03-19 22:05:45</td>
                                <td>user@example.com</td>
                                <td>Access Denied</td>
                                <td>users.delete</td>
                                <td>192.168.1.105</td>
                                <td><span class="badge bg-danger">Failed</span></td>
                            </tr>
                            <tr>
                                <td>2024-03-19 21:58:20</td>
                                <td>admin@example.com</td>
                                <td>Permission Deleted</td>
                                <td>old.permission</td>
                                <td>192.168.1.100</td>
                                <td><span class="badge bg-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>2024-03-19 21:45:10</td>
                                <td>manager@example.com</td>
                                <td>Role Removed</td>
                                <td>Admin Role → user@example.com</td>
                                <td>192.168.1.102</td>
                                <td><span class="badge bg-success">Success</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">Showing 1-5 of 127 entries</small>
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
