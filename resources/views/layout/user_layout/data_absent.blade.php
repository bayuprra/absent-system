@php
    use Carbon\Carbon;
    $endDateThisMonth = intval(Carbon::now()->endOfMonth()->format('d')) ?? 31;
    $sessionData = session()->get('data');
    $idKaryawan = $sessionData->idKaryawan ?? '';
    $idAkun = $sessionData->id ?? '';
@endphp
@extends('layout.main_layout.main')
@section('style')
    <style>
        .select2-container .select2-selection--single {
            height: auto;
        }
    </style>
@endSection
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
                    <form id="filterForm" action="{{ route('userAbsentData') }}" method="GET" class="mb-3">
                        <select name="month" id="monthSelect" class="form-control select2" style="width: 20%;height:auto">
                            @foreach ($months as $month)
                                <option value="{{ $month->format('Y-m') }}"
                                    {{ $selectedMonth->format('Y-m') == $month->format('Y-m') ? 'selected' : '' }}>
                                    {{ $month->isoFormat('MMMM YYYY') }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" hidden>Filter</button>
                    </form>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2">Nama</th>
                                <th colspan="{{ $selectedMonth->daysInMonth }}" id="forJudul">
                                    {{ Carbon::parse($selectedMonth)->locale('id_ID')->isoFormat('MMMM YYYY') }}</th>
                            </tr>
                            <tr>
                                <!-- Header for dates -->
                                @for ($i = 0; $i < $selectedMonth->daysInMonth; $i++)
                                    @php
                                        $date = $selectedMonth->copy()->startOfMonth()->addDays($i);
                                        $isWeekend = in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
                                    @endphp
                                    <th class="{{ $isWeekend ? 'bg-danger' : '' }}">{{ $date->format('d') }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows for absent data -->
                            @foreach ($data as $nama => $absences)
                                @php
                                    $base = explode('&&', $nama);
                                    $realName = $base[0];
                                    $idNama = $base[1];
                                @endphp
                                @if ($idNama == $idKaryawan)
                                    <tr>
                                        <td>{{ $realName }}</td>
                                        @for ($i = 0; $i < $selectedMonth->daysInMonth; $i++)
                                            <td>
                                                @php
                                                    $dateIter = $selectedMonth->copy()->startOfMonth()->addDays($i);
                                                    $attendance = null;
                                                    $idUserAbsent = null;
                                                    if ($dateIter->isFuture()) {
                                                        $attendance = '-';
                                                    } else {
                                                        foreach ($absences as $absence) {
                                                            $absenceDate = Carbon::parse($absence['tanggalAbsent']);
                                                            if (
                                                                $dateIter->eq($absenceDate) &&
                                                                $absence['checkin'] != null
                                                            ) {
                                                                $attendance = 'hadir';
                                                                $idUserAbsent = $absence['id'];
                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @if ($attendance === 'hadir')
                                                    <a href="#" data-toggle="modal"
                                                        data-target="#modal-detail-{{ $idUserAbsent }}"><small
                                                            class="badge badge-success">{{ $absence['flag'] ?? 'WFO' }}</small></a>
                                                    <p style="display: none">in:
                                                        {{ Carbon::parse($absence['checkin'])->locale('id_ID')->isoFormat('H:mm') }}
                                                    </p>
                                                    <p style="display: none">out:
                                                        @if ($absence['checkout'])
                                                            {{ Carbon::parse($absence['checkout'])->locale('id_ID')->isoFormat('H:mm') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </p>
                                                @elseif ($attendance === '-')
                                                    -
                                                @else
                                                    <p style="display: none">x</p>
                                                    <small class="badge badge-danger"><i
                                                            class="fas fa-times-circle"></i></small>
                                                @endif
                                            </td>
                                        @endfor

                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div><!--/. container-fluid -->
    </section>
    @foreach ($data as $nama => $absences)
        @php
            $base = explode('&&', $nama);
            $realName = $base[0];
        @endphp
        @foreach ($absences as $absence)
            <div class="modal fade" id="modal-detail-{{ $absence['id'] }}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Detail Absent</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body card">
                            <div class="card-header">
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" disabled value="{{ $realName }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Tanggal</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" disabled
                                            value="{{ Carbon::parse($absence['checkin'])->locale('id_ID')->isoFormat('D MMMM YYYY') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body row">
                                <div class="col-6">
                                    <div class="timeline">
                                        <div class="time-label">
                                            <span
                                                class="bg-success">{{ Carbon::parse($absence['checkin'])->locale('id_ID')->isoFormat('HH:mm') }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-sign-in-alt bg-success"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header"><a href="#">Employee Checking In</a>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    @if (!$absence['checkout'])
                                        <p class="text-muted text-center">
                                            Karyawan Belum Checkout.
                                        </p>
                                    @else
                                        <div class="timeline">
                                            <div>
                                                <i class="fas fa-sign-out-alt bg-danger"></i>
                                                <div class="timeline-item">
                                                    <h3 class="timeline-header"><a href="#">Employee Checking
                                                            Out</a>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="time-label">
                                                <span
                                                    class="bg-danger">{{ Carbon::parse($absence['checkout'])->locale('id_ID')->isoFormat('HH:mm') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <iframe
                                        src="https://maps.google.com/maps?q={{ $absence['latitude'] }},{{ $absence['longitude'] }}&hl=es&z=14&amp;output=embed"
                                        height="200" width="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        @endforeach
    @endforeach
@endSection

@section('script')
    <script>
        $(function() {
            // Inisialisasi DataTables
            var table = $("#example1").DataTable({
                "responsive": false,
                "scrollX": true,
                "autoWidth": false,
                "lengthChange": false,
                "buttons": [{
                        text: 'Export',
                        className: 'filter-dropdown',
                        extend: 'collection',
                        buttons: [{
                                extend: 'pdf',
                                orientation: 'landscape',
                                pageSize: 'A4',
                                customize: function(doc) {
                                    doc.content.forEach(function(content) {
                                        if (content.style === 'title') {
                                            content.text +=
                                                ` ${$('#forJudul').text()}`
                                        }
                                        if (content.table) {
                                            content.table.body.forEach(function(row) {
                                                row.forEach(function(cell) {
                                                    switch (cell.text) {
                                                        case "x":
                                                            cell.text =
                                                                'x';
                                                            break;
                                                    }
                                                });
                                            });
                                        }
                                    });
                                }
                            },
                            {
                                extend: 'print',
                                customize: function(win) {
                                    $(win.document.body).find('table').addClass('display').css(
                                        'font-size', '12px');
                                    $(win.document.body).find('tr').addClass('trClass');
                                    $(win.document.body).find('td').addClass('tdClass');
                                }
                            }
                        ]
                    },

                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('#monthSelect').on('change', function(e) {
                $('#filterForm').submit()
            })
        });

        function detailAbsent(id) {
            $('#modal-detail').modal('show')
        }
    </script>
@endSection
