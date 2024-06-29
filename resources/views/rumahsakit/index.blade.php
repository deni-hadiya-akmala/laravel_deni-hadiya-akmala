@extends('auth.layouts')

@section('content')
    <div class="container mt-4">
        <h1>Pasien</h1>
        <button class="btn btn-primary mb-2" onclick="showCreateForm()">Tambah rumah sakit</button>
        <table class="table table-bordered" id="rumahsakitTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Modal for Create and Edit rumah sakit -->
    <div class="modal fade" id="rumahsakitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                            <label>Nama</label>
                            <input type="text" id="nama_rumahsakit" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="number" id="stok" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" id="jenis_rumahsakit" class="form-control" required>
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
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'telepon',
                        name: 'telepon'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
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
                        nama_rumahsakit: $('#nama_rumahsakit').val(),
                        stok: $('#stok').val(),
                        jenis_rumahsakit: $('#jenis_rumahsakit').val(),
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
                        $('#formTitle').text('Ubah rumah sakit');
                        $('#rumahsakitId').val(response.id);
                        $('#nama_rumahsakit').val(response.nama_rumahsakit);
                        $('#stok').val(response.stok);
                        $('#jenis_rumahsakit').val(response.jenis_rumahsakit);
                        $('#rumahsakitModal').modal('show');
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                let id = $(this).data('id');
                let nama_brg = $(this).data('nama_brg');
                
                Swal.fire({
                    title:  'Hapus rumah sakit',
                    text: 'Apakah ingin menghapus nama rumah sakit '+ nama_brg +'?',
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
            $('#formTitle').text('Tambah rumah sakit');
            $('#rumahsakitForm')[0].reset();
            $('#rumahsakitId').val('');
            $('#rumahsakitModal').modal('show');
        }
    </script>
@endsection
