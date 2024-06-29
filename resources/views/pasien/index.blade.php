@extends('auth.layouts')

@section('content')
    <div class="container mt-4">
        <h1>Pasien</h1>
        <button class="btn btn-primary mb-2" onclick="showCreateForm()">Tambah Pasien</button>
        <table class="table table-bordered" id="pasienTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pasien</th>
                    <th>Alamat</th>
                    <th>No Telepon</th>
                    <th>Rumah Sakit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Modal for Create and Edit Pasien -->
    <div class="modal fade" id="pasienModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formTitle">Tambah Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="pasienForm">
                        @csrf
                        <input type="hidden" id="pasienId">
                        <div class="form-group">
                            <label>Nama Pasien</label>
                            <input type="text" id="nama_pasien" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea id="alamat" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>No Telepon</label>
                            <input type="text" id="no_telepon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Rumah Sakit</label>
                            <select id="id_rumah_sakit" class="form-control" required>
                                <option value="">Pilih Rumah Sakit</option>
                                @foreach($rumah_sakits as $rumah_sakit)
                                    <option value="{{ $rumah_sakit->id }}">{{ $rumah_sakit->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="pasienForm" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#pasienTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pasien.index') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nama', name: 'nama' },
                    { data: 'alamat', name: 'alamat' },
                    { data: 'no_telepon', name: 'no_telepon' },
                    { data: 'rumah_sakit', name: 'rumah_sakit' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#pasienForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#pasienId').val();
                let url = id ? `/api/pasien/${id}` : '/api/pasien';
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        nama_pasien: $('#nama_pasien').val(),
                        alamat: $('#alamat').val(),
                        no_telepon: $('#no_telepon').val(),
                        id_rumah_sakit: $('#id_rumah_sakit').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#pasienModal').modal('hide');
                        $('#pasienForm')[0].reset();
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
                    url: `/api/pasien/${id}`,
                    method: 'GET',
                    success: function(response) {
                        $('#formTitle').text('Ubah Pasien');
                        $('#pasienId').val(response.id);
                        $('#nama_pasien').val(response.nama);
                        $('#alamat').val(response.alamat);
                        $('#no_telepon').val(response.no_telepon);
                        $('#id_rumah_sakit').val(response.rumah_sakit_id);
                        $('#pasienModal').modal('show');
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                let id = $(this).data('id');
                let nama_pasien = $(this).data('nama_pasien');
                
                Swal.fire({
                    title: 'Hapus Pasien',
                    text: 'Apakah ingin menghapus nama pasien ' + nama_pasien + '?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/pasien/${id}`,
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
            $('#formTitle').text('Tambah Pasien');
            $('#pasienForm')[0].reset();
            $('#pasienId').val('');
            $('#pasienModal').modal('show');
        }
    </script>
@endsection
