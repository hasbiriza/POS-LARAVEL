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

  .badge {
    margin-right: 5px;
    margin-bottom: 5px;
  }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">User Roles</li>
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
            <th>Name</th>
            <th>Permissions</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($userRoles as $role)
          <tr>
            <td></td>
            <td>{{ $role->name }}</td>
            <td>
              @foreach($role->permissions->sortBy('name') as $permission)
              <span class="badge bg-primary">{{ $permission->name }}</span>
              @endforeach
            </td>
            <td class="text-center">
            <div class="btn-group">
              <button type="button" class="btn btn-icon btn-primary edit-button btn-sm custom-btn-color" data-bs-toggle="modal" data-bs-target="#editUserRole" data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}">
                <span class="tf-icons bx bx-edit"></span>
              </button>
              <button type="button" class="btn btn-icon btn-danger delete-button btn-sm" data-role-id="{{ $role->id }}">
                <span class="tf-icons bx bx-trash"></span>
              </button>
            </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add User Role Modal -->
<div class="modal fade" id="addUserRole" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-simple">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3>Add New User Role</h3>
          <p>Add a new user role for the system.</p>
        </div>
        <form id="userRoleForm" class="row" action="{{ route('userroles.store') }}" method="POST">
          @csrf
          <div class="col-12 mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="role_name" class="form-control" placeholder="Role Name" required>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Permissions</label>
            <div class="form-check">
              @foreach($permissions as $permission)
              <div>
                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                <label class="form-check-label" for="permission_{{ $permission->id }}">
                  {{ $permission->name }}
                </label>
              </div>
              @endforeach
            </div>
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Save</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add User Role Modal -->

<!-- Edit User Role Modal -->
<div class="modal fade" id="editUserRole" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-simple">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3>Edit User Role</h3>
          <p>Edit existing user role for the system.</p>
        </div>
        <form id="editUserRoleForm" class="row" action="{{ route('userroles.update', ':id') }}" method="POST">
          @csrf
          @method('PUT')
          <div class="col-12 mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="role_name" id="edit_role_name" class="form-control" placeholder="Role Name" required>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Permissions</label>
            <div class="form-check" id="edit_permissions">
              <!-- Permissions will be dynamically loaded here -->
            </div>
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit User Role Modal -->

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
              return 'User Details: ' + data[1];
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
        text: '<i class="bx bx-plus me-1"></i> <span class="d-none d-lg-inline-block">Add User Role</span>',
        className: 'create-new btn btn-primary me-2 btn-sm custom-btn-color',
        attr: {
          'data-bs-toggle': 'modal',
          'data-bs-target': '#addUserRole'
        }
      }],
    });

    $('div.head-label').html('<h5 class="card-title mb-0">User Roles</h5>');

    $(document).on('click', '.edit-button', function() {
      var roleId = $(this).data('role-id');
      var roleName = $(this).data('role-name');
      $('#editUserRoleForm').attr('action', function(i, val) {
        return val.replace(':id', roleId || '');
      });
      $('#edit_role_name').val(roleName);

      // Fetch and display permissions for this role
      $.ajax({
        url: '/userroles/' + roleId + '/permissions',
        type: 'GET',
        success: function(response) {
          var permissions = response.permissions;
          var rolePermissions = response.rolePermissions;
          var html = '';
          permissions.forEach(function(permission) {
            var isChecked = rolePermissions.includes(permission.id) ? 'checked' : '';
            html += '<div>';
            html += '<input class="form-check-input" type="checkbox" name="permissions[]" value="' + permission.id + '" id="edit_permission_' + permission.id + '" ' + isChecked + '>';
            html += '<label class="form-check-label" for="edit_permission_' + permission.id + '">' + permission.name + '</label>';
            html += '</div>';
          });
          $('#edit_permissions').html(html);
        },
        error: function(xhr) {
          console.error('Error fetching permissions:', xhr);
        }
      });
    });
  });
</script>

<script>
  $(document).on('click', '.delete-button', function() {
    var roleId = $(this).data('role-id');
    console.log('Role ID:', roleId);
    if (roleId === undefined) {
      console.error('Role ID is undefined');
      return;
    }
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
          url: '/userroles/' + roleId + '/delete',
          type: 'DELETE',
          data: {
            "_token": "{{ csrf_token() }}"
          },
          success: function(response) {
            Swal.fire(
              'Deleted!',
              'Role has been deleted.',
              'success'
            ).then(() => {
              location.reload();
            });
          },
          error: function(xhr) {
            Swal.fire(
              'Error!',
              'An error occurred while deleting the permission.',
              'error'
            );
          }
        });
      }
    });
  });
</script>



@endsection