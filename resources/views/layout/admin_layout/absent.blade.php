@php
    use Carbon\Carbon;
    $endDateThisMonth = intval(Carbon::now()->endOfMonth()->format('d')) ?? 31;
@endphp
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
                    @if ($errors->has('username'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-times"></i>
                            {{ $errors->first('username') }}
                        </div>
                    @endif
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama</th>
                                <th colspan="{{ $endDateThisMonth }}">Tanggal</th>
                            </tr>
                            <tr>
                                @for ($i = 0; $i < $endDateThisMonth; $i++)
                                    @php
                                        $date = Carbon::now()->startOfMonth()->addDays($i);
                                        $isWeekend = in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
                                        $isToday = $date->isToday();
                                        $cellStyle = '';
                                        if ($isToday) {
                                            $cellStyle = 'bg-success';
                                        } elseif ($isWeekend) {
                                            $cellStyle = 'bg-danger';
                                        }
                                    @endphp
                                    <th data-orderable="false" class="{{ $cellStyle }}">{{ $i + 1 }}</th>
                                @endfor
                            </tr>

                        </thead>

                        <tbody>
                            <?php
                            $num = 1;
                            ?>

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div><!--/. container-fluid -->
    </section>
@endSection

@section('script')
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": false,
                "scrollX": true,
                "autoWidth": false,
                "lengthChange": false,
                "buttons": [{
                    text: 'Export',
                    className: 'filter-dropdown',
                    extend: 'collection',
                    buttons: [{
                            extend: 'excel',
                            exportOptions: {
                                columns: ':visible' // Ekspor kolom yang terlihat
                            }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: ':visible' // Ekspor kolom yang terlihat
                            }
                        }, {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible' // Ekspor kolom yang terlihat
                            }
                        }
                    ]
                }, "colvis", {
                    text: 'Filter',
                    className: 'filter-dropdown',
                    extend: 'collection',
                    buttons: [{
                            text: 'All',
                            action: function(e, dt, node, config) {
                                let tod = new Date().getDate()
                                dt.column(1).search('').draw();
                            }
                        }, {
                            text: 'Today',
                            action: function(e, dt, node, config) {
                                let tod = new Date().getDate()
                                dt.column(1).search(tod)
                                    .draw();
                            }
                        },
                        {
                            text: 'This Week',
                            action: function(e, dt, node, config) {
                                let tod = new Date().getMonth()
                                let months = ['Januari', 'Februari', 'Maret', 'April',
                                    'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                                    'Oktober', 'November', 'Desember'
                                ];
                                let monthName = months[tod];
                                dt.column(1).search(monthName).draw();
                            }
                        },
                        {
                            text: 'This Month',
                            action: function(e, dt, node, config) {
                                let tod = new Date().getMonth()
                                let months = ['Januari', 'Februari', 'Maret', 'April',
                                    'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                                    'Oktober', 'November', 'Desember'
                                ];
                                let monthName = months[tod];
                                dt.column(1).search(monthName).draw();
                            }
                        }
                    ]
                }],
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


        });
    </script>
@endSection
