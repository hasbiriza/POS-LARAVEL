@extends('template.app')
@section('title', 'Users')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
<style type="text/css">
    .select2-container {
        z-index: 9999;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Users</li>
@endsection

@section('content')
<div class="flex-grow-1">

    @include('components.toast-notification')

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-roles table border-top table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usersWithRoles as $user)
                    <tr>
                        <td></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                            $roles = $user->roles->pluck('name')->implode(', ');
                            @endphp
                            @foreach(explode(', ', $roles) as $role)
                            @if($role == 'admin')
                            <span class="badge bg-label-primary me-1">{{ $role }}</span>
                            @else
                            <span class="badge bg-label-info me-1">{{ $role }}</span>
                            @endif
                            @endforeach
                        </td>
                        <td>{{ $user->created_at }}</td>
                        <td class="text-center">
                        <div class="btn-group">
                            @can('users.edit')
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-icon btn-primary edit-button btn-sm custom-btn-color">
                            <span class="bx bx-edit"></span>
                            </a>
                            @endcan
                            @can('users.delete')
                            <button class="btn btn-icon btn-danger delete-button btn-sm" data-user-id="{{ $user->id }}">
                            <span class="bx bx-trash"></span>
                            </button>
                            @endcan
                            @cannot('users.edit')
                            @cannot('delete_users')
                            <span class="text-muted">No Access</span>
                            @endcannot
                            @endcannot
                        </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/ui-toasts.js') }}"></script>


<script>
    $(document).ready(function() {
        $('.datatables-roles').DataTable({
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Detail user ' + data[1];
                        }
                    }),
                    type: 'column',
                    renderer: function(api, rowIdx, columns) {
                        var data = $.map(columns, function(col, i) {
                            return col.title !== '' ?
                                '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>' :
                                '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            },
            columnDefs: [{
                className: 'control',
                orderable: false,
                targets: 0
            }],
            dom: '<"card-header"<"head-label text-center"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            buttons: [{
                text: '<i class="bx bx-plus me-1"></i>Add User',
                className: 'btn btn-primary btn-sm custom-btn-color',
                action: function(e, dt, node, config) {
                    window.location.href = '{{ route("users.create") }}';
                }
            }],
        });

        $('div.head-label').html('<h5 class="card-title mb-0">List User</h5>');


        $(document).on('click', '.delete-button', function() {
            var userId = $(this).data('user-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: '/users/' + userId + '/delete',
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the user.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

@endsection