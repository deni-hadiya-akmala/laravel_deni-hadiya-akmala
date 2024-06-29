@extends('auth.layouts')

@section('content')
<div class="container mt-4">
    <h1>Rumah Sakit</h1>
    <button class="btn btn-primary mb-2" onclick="showCreateForm()">Tambah Rumah Sakit</button>
    <table class="table table-bordered" id="rumahsakitTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Rumah Sakit</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal for Create and Edit Rumah Sakit -->
<div class="modal fade" id="rumahsakitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formTitle">Tambah Rumah Sakit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rumahsakitForm">
                    @csrf
                    <input type="hidden" id="rumahsakitId">
                    <div class="form-group">
                        <label>Nama Rumah Sakit</label>
                        <input type="text" id="nama_rumahsakit" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea rows="2" id="alamat" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="tel" id="telepon" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="rumahsakitForm" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#rumahsakitTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('rumahsakit.index') }}",
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'nama', name: 'nama' },
                { data: 'alamat', name: 'alamat' },
                { data: 'email', name: 'email' },
                { data: 'telepon', name: 'telepon' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#rumahsakitForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#rumahsakitId').val();
            let url = id ? `/api/rumahsakit/${id}` : '/api/rumahsakit';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: {
                    nama: $('#nama_rumahsakit').val(),
                    alamat: $('#alamat').val(),
                    email: $('#email').val(),
                    telepon: $('#telepon').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#rumahsakitModal').modal('hide');
                    $('#rumahsakitForm')[0].reset();
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Operation successful!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Operation failed!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
        });

        $(document).on('click', '.edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `/api/rumahsakit/${id}`,
                method: 'GET',
                success: function(response) {
                    $('#formTitle').text('Ubah Rumah Sakit');
                    $('#rumahsakitId').val(response.id);
                    $('#nama_rumahsakit').val(response.nama);
                    $('#alamat').val(response.alamat);
                    $('#email').val(response.email);
                    $('#telepon').val(response.telepon);
                    $('#rumahsakitModal').modal('show');
                }
            });
        });

        $(document).on('click', '.delete', function() {
            let id = $(this).data('id');
            let nama_rumahsakit = $(this).data('nama_rumahsakit');
            
            Swal.fire({
                title: 'Hapus Rumah Sakit',
                text: 'Apakah ingin menghapus rumah sakit ' + nama_rumahsakit + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/rumahsakit/${id}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Delete operation successful!',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Delete operation failed!',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });

    });

    function showCreateForm() {
        $('#formTitle').text('Tambah Rumah Sakit');
        $('#rumahsakitForm')[0].reset();
        $('#rumahsakitId').val('');
        $('#rumahsakitModal').modal('show');
    }
</script>
@endsection
