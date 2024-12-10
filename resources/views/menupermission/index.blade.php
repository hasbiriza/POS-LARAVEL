@extends('template.app')
@section('title', 'Menu Permissions')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
<style type="text/css">
  .select2-container {
    z-index: 9999;
  }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Menu Permissions</li>
@endsection

@section('content')
<div class="flex-grow-1">
  @include('components.toast-notification')

  <!-- Menu Required Permissions -->
  <div class="card">
    <div class="card-datatable table-responsive">
      <table class="datatables-required-permission table border-top table-striped">
        <thead>
          <tr>
            <th></th>
            <th>No</th>
            <th>Menu</th>
            <th>Required Permissions</th>
            <th style="text-align: center;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($menus as $menu)
          <tr>
            <td></td>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $menu['menu'] }}</td>
            <td>
              @foreach($menu['permissions'] as $permission)
              <span class="badge bg-info me-1">{{ $permission }}</span>
              @endforeach
            </td>
            <td style="text-align: center;">
              <a href="#" class="btn btn-icon btn-primary edit-button btn-sm custom-btn-color" data-bs-toggle="modal" data-bs-target="#editMenuPermissionModal" data-menu-id="{{ $menu['id'] }}" data-menu-name="{{ $menu['menu'] }}" data-permissions="{{ json_encode($menu['permissions']) }}" data-permission-ids="{{ json_encode($menu['permission_ids']) }}">
                <span class="tf-icons bx bx-edit"></span>
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <!-- End Menu Required Permissions -->

</div>

<!-- Edit Menu Permission Modal -->
<div class="modal fade" id="editMenuPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-simple">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3>Edit Menu Permission</h3>
          <p>Modify the required permissions for the menu.</p>
        </div>
        <form id="editMenuPermissionForm" class="row" action="{{ route('menupermission.updateMenuPermission', ':id') }}" method="POST">
          @csrf
          @method('PUT')
          <input type="hidden" id="editMenuId" name="menu_id">
          <div class="col-12 mb-3">
            <label class="form-label">Menu Name</label>
            <input type="text" id="editMenuName" class="form-control" readonly>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Required Permissions</label>
            <select id="editMenuPermissions" name="permissions[]" class="form-select select2" multiple>
              @foreach($permissions as $permission)
              <option value="{{ $permission->id }}">{{ $permission->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit Menu Permission Modal -->

<!-- List Permission Modal -->
<div class="modal fade" id="listPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3>Permission List</h3>
          <p>Here is a list of all permissions available in the system.</p>
        </div>
        <form id="addPermissionForm" class="row mb-4" action="{{ route('menupermission.store') }}" method="POST">
          @csrf
          <div class="col-md-8">
            <input type="text" name="permission_name" class="form-control" placeholder="Input New Permission" required>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100"><i class="bx bx-save me-1"></i> Save</button>
          </div>
        </form>
        <hr>
        <div class="table-responsive">
          <table class="table table-bordered datatables-permissions" style="border:none;">
            <thead>
              <tr>
                <th>Permission Name</th>
                <th style="text-align: center;">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($permissions as $permission)
              <tr>
                <td>
                  <input type="text" readonly class="form-control permission-input border-0" value="{{ $permission->name }}" data-permission-id="{{ $permission->id }}" data-permission-guard="{{ $permission->guard_name }}" style="background-color: transparent;">
                </td>
                <td class="text-center">
                  <a href="#" class="btn btn-icon btn-primary edit-button-xx btn-sm custom-btn-color" data-permission-id="{{ $permission->id }}">
                    <span class="tf-icons bx bx-edit"></span>
                  </a>
                  <button class="btn btn-icon btn-danger delete-button btn-sm" data-permission-id="{{ $permission->id }}">
                    <span class="tf-icons bx bx-trash"></span>
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ List Permission Modal -->


@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>



<script>
  $(document).ready(function() {
    var table = $('.datatables-required-permission').DataTable({
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function(row) {
              var data = row.data();
              return 'Detail ' + data[1];
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
        text: '<i class="bx bx-show me-1"></i> <span class="d-none d-lg-inline-block">List Permissions</span>',
        className: 'view-permission btn btn-primary ms-2 btn-sm custom-btn-color',
        attr: {
          'data-bs-toggle': 'modal',
          'data-bs-target': '#listPermissionModal'
        }
      }],
    });

    $('div.head-label').html('<h5 class="card-title mb-0">Menu Permissions</h5>');

    $('.select2').select2({
      allowClear: true,
      width: '100%'
    });

    $(document).on('click', '.edit-button', function() {
      var row = $(this).closest('tr');
      var menuId = $(this).data('menu-id');
      var menuName = $(this).data('menu-name');
      var permissions = $(this).data('permissions');
      var permissionIds = $(this).data('permission-ids');

      // Mengambil data dari DataTable
      var rowData = table.row(row).data();
      if (rowData) {
        menuName = rowData[2]; // Kolom Menu
        permissions = rowData[3].split(', '); // Kolom Required Permissions
      }

      $('#editMenuId').val(menuId);
      $('#editMenuName').val(menuName);

      // Clear previous selections
      $('#editMenuPermissions').val(null).trigger('change');

      // Set selected permissions
      $('#editMenuPermissions').val(permissionIds).trigger('change');

      // Update form action URL
      var formAction = $('#editMenuPermissionForm').attr('action');
      formAction = formAction.replace(':id', menuId);
      $('#editMenuPermissionForm').attr('action', formAction);
    });
  });
</script>

<script>
  $(document).ready(function() {
    $(".datatables-permissions").DataTable();
  });
</script>
<script>
  $(document).ready(function() {
    $(document).on('click', '.edit-button-xx', function() {
      $('.permission-input').prop('readonly', false);
      $(this).html('<i class="bx bx-save me-1"></i>').removeClass('btn-outline-primary edit-button-xx').addClass('save-button btn-outline-info');
    });

    $(document).on('click', '.save-button', function(e) {
      e.preventDefault();
      var permissionId = $(this).closest('tr').find('.permission-input').data('permission-id');
      var permissionName = $(this).closest('tr').find('.permission-input').val();
      $.ajax({
        url: '/menupermission/' + permissionId + '/update',
        type: 'PUT',
        data: {
          "_token": "{{ csrf_token() }}",
          "permission_name": permissionName
        },
        success: function(response) {
          if (response.success) {
            var toastElement = $('<div class="bs-toast toast toast-placement-ex m-2 fade bg-primary top-0 end-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
              '<div class="toast-header">' +
              '<i class="bx bx-check-circle text-success me-2"></i>' +
              '<div class="me-auto fw-medium">Success</div>' +
              '<small>Just now</small>' +
              '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>' +
              '</div>' +
              '<div class="toast-body">' +
              '<p class="mb-0">' + (response.message || 'Permission updated successfully') + '</p>' +
              '<div class="mt-2 pt-2 border-top">' +
              '<button type="button" class="btn btn-sm btn-success" data-bs-dismiss="toast">OK</button>' +
              '<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="toast">Close</button>' +
              '</div>' +
              '</div>' +
              '</div>');
            $('body').append(toastElement);
            toastElement.toast('show');
          } else {
            var toastElement = $('<div class="bs-toast toast toast-placement-ex m-2 fade bg-danger top-0 end-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
              '<div class="toast-header">' +
              '<i class="bx bx-x-circle text-danger me-2"></i>' +
              '<div class="me-auto fw-medium">Error</div>' +
              '<small>Just now</small>' +
              '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>' +
              '</div>' +
              '<div class="toast-body">' +
              '<p class="mb-0">' + (response.message || 'An error occurred while updating the permission') + '</p>' +
              '<div class="mt-2 pt-2 border-top">' +
              '<button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="toast">OK</button>' +
              '<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="toast">Close</button>' +
              '</div>' +
              '</div>' +
              '</div>');
            $('body').append(toastElement);
            toastElement.toast('show');
          }
        },
        error: function(xhr) {
          console.error('Error:', xhr);
          var toastElement = $('<div class="bs-toast toast toast-placement-ex m-2 fade bg-danger top-0 end-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
            '<div class="toast-header">' +
            '<i class="bx bx-x-circle text-danger me-2"></i>' +
            '<div class="me-auto fw-medium">Error</div>' +
            '<small>Just now</small>' +
            '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>' +
            '<div class="toast-body">' +
            '<p class="mb-0">An error occurred while updating the permission</p>' +
            '<div class="mt-2 pt-2 border-top">' +
            '<button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="toast">OK</button>' +
            '<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="toast">Close</button>' +
            '</div>' +
            '</div>' +
            '</div>');
          $('body').append(toastElement);
          toastElement.toast('show');
        }
      });
      $(this).closest('tr').find('.permission-input').prop('readonly', true);
      $(this).html('<i class="bx bx-edit me-1"></i>').removeClass('save-button btn-outline-info').addClass('edit-button-xx btn-outline-primary');
    });

  });
</script>
<script>
  $(document).on('click', '.delete-button', function() {
    var permissionId = $(this).data('permission-id');
    console.log('ID Izin:', permissionId);
    if (permissionId === undefined) {
      console.error('ID Izin tidak terdefinisi');
      return;
    }
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: "Anda tidak akan dapat mengembalikan ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#696cff',
      cancelButtonColor: '#8592a3',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal',
      customClass: {
        container: 'swal-container'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/menupermission/' + permissionId + '/delete',
          type: 'DELETE',
          data: {
            "_token": "{{ csrf_token() }}"
          },
          success: function(response) {
            Swal.fire({
              title: 'Terhapus!',
              text: 'Izin telah dihapus.',
              icon: 'success',
              customClass: {
                container: 'swal-container'
              }
            }).then(() => {
              location.reload();
            });
          },
          error: function(xhr) {
            Swal.fire({
              title: 'Kesalahan!',
              text: 'Terjadi kesalahan saat menghapus izin.',
              icon: 'error',
              customClass: {
                container: 'swal-container'
              }
            });
          }
        });
      }
    });
  });
</script>

<style>
  .swal-container {
    z-index: 999999999999999 !important;
  }
</style>

@endsection