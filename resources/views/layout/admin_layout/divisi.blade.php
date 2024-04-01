@extends('layout.main_layout.main')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card content-card">
                <div class="card-header">
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-times"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->has('nama'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-times"></i>
                            {{ $errors->first('nama') }}
                        </div>
                    @endif
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $num = 1;
                            ?>
                            @foreach ($data as $da)
                                <tr>
                                    <td>{{ $num++ }}</td>
                                    <td>{{ $da->nama }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-6 col-sm-12 col-xs-12 mb-1">
                                                <button type="button" class="btn btn-warning btn-xs btn-block"
                                                    id="editButton-{{ $da->id }}" data-toggle="modal"
                                                    data-target="#modal-update-{{ $da->id }}"><i
                                                        class="fas fa-edit"></i></button>
                                            </div>
                                            <div class="col-6 col-sm-12 col-xs-12">
                                                <button type="button" class="btn  btn-danger btn-xs btn-block"
                                                    id="deleteButton" onclick="hapus({{ $da->id }})"><i
                                                        class="fas fa-solid fa-trash"></i></button>
                                            </div>
                                        </div>
                                        <form action="{{ route('deleteDivisi') }}" method="post"
                                            id="formHapus{{ $da->id }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $da->id }}">
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div><!--/. container-fluid -->
    </section>
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Divisi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('createDivisi') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" placeholder="input nama" name="nama"
                                        required>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    @foreach ($data as $da)
        <div class="modal fade" id="modal-update-{{ $da->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Divisi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('updateDivisi') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $da->id }}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" placeholder="input nama" name="nama"
                                            required value="{{ $da->nama }}">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endforeach
@endSection

@section('script')
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [{
                    "text": "Tambah Divisi",
                    "className": "btn btn-primary btn-info",
                    "action": function() {
                        $('#modal-add').modal('show');
                    }
                }],
                "columnDefs": [{
                        "width": "10%",
                        "targets": 0
                    },
                    {
                        "width": "80%",
                        "targets": 1
                    },
                    {
                        "width": "10%",
                        "targets": 2
                    }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


        });

        function hapus(id) {
            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "Kamu Tidak Akan Bisa Mengembalikannya Lagi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('form#formHapus' + id).submit();
                }
            })
        }
    </script>
@endSection
