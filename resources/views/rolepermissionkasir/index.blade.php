@extends('template.app')
@section('title', 'Role Kasir')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
<li class="breadcrumb-item active" aria-current="page">Role Kasir</li>
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
            <th>Nama</th>
            <th>Akses Toko</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($kasirUsers as $user)
          <tr>
            <td></td>
            <td>{{ $user['name'] }}</td>
            <td>
              @foreach($user['stores'] as $storeId => $storeName)
              <span class="badge bg-info">{{ $storeName }}</span>
              @endforeach
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-icon btn-primary edit-button btn-sm custom-btn-color" data-bs-toggle="modal" data-bs-target="#editUserRole" data-user-id="{{ $user['id'] }}" data-user-name="{{ $user['name'] }}">
                <span class="tf-icons bx bx-edit"></span>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Edit User Role Modal -->
<div class="modal fade" id="editUserRole" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-simple">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3>Edit Role Kasir</h3>
          <p>Edit existing role for kasir.</p>
        </div>
        <form id="editUserRoleForm" class="row" action="{{ route('rolepermissionkasir.update', ':id') }}" method="POST">
          @csrf
          @method('PUT')
          <div class="col-12 mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" id="edit_name" class="form-control" placeholder="Nama Kasir" required>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Akses Toko</label>
            <select class="form-select select2" name="stores[]" id="edit_stores" multiple>
              @foreach($stores as $store)
                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Batal</button>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    $('.datatables-roles').DataTable({
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function(row) {
              var data = row.data();
              return 'Role Details: ' + data[1];
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
      buttons: [],
    });

    $('div.head-label').html('<h5 class="card-title mb-0">Role Kasir</h5>');

    $('.select2').select2({
      dropdownParent: $('#editUserRole')
    });

    $(document).on('click', '.edit-button', function() {
      var userId = $(this).data('user-id');
      var userName = $(this).data('user-name');
      $('#editUserRoleForm').attr('action', function(i, val) {
        return val.replace(':id', userId || '');
      });
      $('#edit_name').val(userName);

      $.ajax({
        url: '/role-permission-kasir/' + userId,
        type: 'GET',
        success: function(response) {
          console.log(response.userStores);
          $('#edit_stores').val(response.userStores).trigger('change');
        },
        error: function(xhr) {
          console.error('Error fetching user details:', xhr);
        }
      });
    });

    $('#editUserRoleForm').on('submit', function(e) {
      e.preventDefault();
      var form = $(this);
      var url = form.attr('action');
      
      $.ajax({
        url: url,
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
          $('#editUserRole').modal('hide');
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data kasir berhasil diperbarui',
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            location.reload();
          });
        },
        error: function(xhr) {
          console.error('Error updating user role:', xhr);
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Terjadi kesalahan saat memperbarui data kasir',
          });
        }
      });
    });
  });
</script>

@endsection