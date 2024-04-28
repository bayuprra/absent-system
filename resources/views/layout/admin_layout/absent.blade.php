@php
    use Carbon\Carbon;
    $endDateThisMonth = intval(Carbon::now()->endOfMonth()->format('d')) ?? 31;
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
                    {{-- <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama</th>
                                <th colspan="{{ $endDateThisMonth }}">
                                    {{ Carbon::parse(now())->locale('id_ID')->isoFormat('MMMM YYYY') }}</th>
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
                                    <th data-orderable="false" class="{{ $cellStyle }}">
                                        {{ $date->format('d') }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $nama => $absences)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $nama }}</td>
                                    @php
                                        $dateIter = Carbon::now()->startOfMonth();
                                    @endphp
                                    @for ($i = 0; $i < $endDateThisMonth; $i++)
                                        <td>
                                            @php
                                                $attendance = null;
                                                $dateBeforeNow = true;
                                                foreach ($absences as $absence) {
                                                    $absenceDate = Carbon::parse($absence['tanggalAbsent']);
                                                    if (
                                                        $dateIter->format('Y-m-d') == $absenceDate->format('Y-m-d') &&
                                                        $absence['checkin'] != null
                                                    ) {
                                                        $attendance = 'hadir';
                                                        break;
                                                    } elseif ($dateIter->format('Y-m-d') < date('Y-m-d')) {
                                                        $dateBeforeNow = false;
                                                    }
                                                }
                                            @endphp

                                            @if ($attendance === 'hadir')
                                                <p style="display: none">hadir</p>
                                                <small class="badge badge-success">{{ $absence['flag'] ?? 'WFO' }}</small>
                                            @elseif($dateBeforeNow)
                                                -
                                            @else
                                                <p style="display: none">noHadir</p>
                                                <small class="badge badge-danger"><i
                                                        class="fas fa-times-circle"></i></small>
                                            @endif

                                        </td>
                                        @php
                                            $dateIter->addDay();
                                        @endphp
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                    <form id="filterForm" action="{{ route('absentData') }}" method="GET" class="mb-3">
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
                                <th>No</th>
                                <th>Nama</th>
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
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $nama }}</td>
                                    @for ($i = 0; $i < $selectedMonth->daysInMonth; $i++)
                                        <td>
                                            @php
                                                $dateIter = $selectedMonth->copy()->startOfMonth()->addDays($i);
                                                $attendance = null;
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
                                                            break;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if ($attendance === 'hadir')
                                                <small class="badge badge-success">{{ $absence['flag'] ?? 'WFO' }}</small>
                                            @elseif ($attendance === '-')
                                                -
                                            @else
                                                <small class="badge badge-danger"><i
                                                        class="fas fa-times-circle"></i></small>
                                            @endif
                                        </td>
                                    @endfor

                                </tr>
                            @endforeach
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
                                        if (content.table) {
                                            content.table.body.forEach(function(row) {
                                                row.forEach(function(cell) {
                                                    switch (cell.text) {
                                                        case "hadir":
                                                            cell.text =
                                                                'hadir';
                                                            break;
                                                        case "noHadir":
                                                            cell.text =
                                                                'tidak hadir';
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
    </script>
@endSection
