@php
    $sessionData = session()->get('data');
    $idKaryawan = $sessionData->idKaryawan ?? '';
    $idAkun = $sessionData->id ?? '';
@endphp
@extends('layout.main_layout.main')
@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">

    <style>
        #map {
            height: 350px;
        }
    </style>
@endSection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-body -->
                        <div class="card-header row">
                            @php
                                use Carbon\Carbon;
                                $checkin = false;
                                $checkout = false;
                                if ($dataAbsent->checkin != null) {
                                    $checkin = true;
                                }
                                if ($dataAbsent->checkout != null) {
                                    $checkout = true;
                                }
                            @endphp
                            <div class="col-6">
                                <a class="btn btn-app btn-block {{ $checkin ? 'bg-secondary' : '' }}"
                                    {!! $checkin ? 'style="cursor:no-drop;pointer-events: none;"' : '' !!} data-toggle="modal" data-target="#modal-add">
                                    <i class="far
                                    fa-calendar-check"></i> Checking In
                                </a>
                            </div>

                            <div class="col-6"><a class="btn btn-app btn-block {{ $checkout ? 'bg-secondary' : '' }}"
                                    {!! $checkout ? 'style="cursor:no-drop;pointer-events: none;"' : '' !!} onclick="alert(2)">
                                    <i class="far fa-calendar-check"></i> Checking Out
                                </a></div>
                        </div>
                        <div class="card-body row">
                            @if (!$checkin)
                                <div class="col-12">
                                    <p class="text-muted text-center">
                                        Anda Belum Melakukan Absent.
                                    </p>
                                </div>
                            @else
                                <div class="col-6">
                                    <div class="timeline">
                                        <div class="time-label">
                                            <span
                                                class="bg-success">{{ Carbon::parse($dataAbsent->checkin)->locale('id_ID')->isoFormat('HH:mm') }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-sign-in-alt bg-success"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header"><a href="#">Checking In</a>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    @if (!$checkout)
                                        <p class="text-muted text-center">
                                            Anda Belum Logout.
                                        </p>
                                    @else
                                        <div class="timeline">
                                            <div>
                                                <i class="fas fa-sign-out-alt bg-danger"></i>
                                                <div class="timeline-item">
                                                    <h3 class="timeline-header"><a href="#">Checking Out</a>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="time-label">
                                                <span
                                                    class="bg-danger">{{ Carbon::parse($dataAbsent->checkout)->locale('id_ID')->isoFormat('HH:mm') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

    <div class="modal fade" id="modal-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><button class="btn btn-primary btn-sm" onclick="takeAbsent()">Get
                            Location</button></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="map"></div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="button" id="saveAbsen" class="btn btn-primary">Simpan Absent</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endSection

@section('script')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        $(function() {
            var date = new Date()
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear()

            var Calendar = FullCalendar.Calendar;

            var calendarEl = document.getElementById('calendar');
            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                //Random default events
                events: [{
                    title: 'Meeting',
                    start: new Date(y, m, d),
                    allDay: true,
                    backgroundColor: 'none',
                    borderColor: 'none',
                    customProperty: 'apiCheck',
                    editable: true
                }],
                eventDidMount: function(info) {
                    if (info.event.extendedProps.customProperty === 'apiCheck') {
                        console.log(info.event.start)
                        // Panggil API untuk memeriksa kondisi
                        let cc = true;
                        if (cc) {
                            info.event.setProp('title', 'Take Absent');
                        } else {
                            info.event.setProp('title', 'Success Absent');
                        }
                    }
                },
                eventClick: function(info) {
                    $('#modal-add').modal('show');

                }
            });
            calendar.render();
        })

        function takeAbsent() {
            const map = L.map('map');
            map.setView([51.505, -0.09], 15);
            // Sets initial coordinates and zoom level
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 25,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            const options = {
                maximumAge: 0,
                timeout: 10000,
            };
            const successCallback = (position) => {
                let userLatitude, userLongitude;
                console.log(position)
                userLatitude = position.coords.latitude;
                userLongitude = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                let marker, circle, zoomed;

                if (marker) {
                    map.removeLayer(marker);
                    map.removeLayer(circle);
                }
                marker = L.marker([userLatitude, userLongitude]).addTo(map);
                circle = L.circle([userLatitude, userLongitude], {
                    radius: accuracy
                }).addTo(map);
                if (!zoomed) {
                    zoomed = map.fitBounds(circle.getBounds());
                }
                map.setView([userLatitude, userLongitude], 18);
            };

            const errorCallback = (error) => {
                alert("please enable location");
                location.reload();
            };
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback, options);
        }

        $("#saveAbsen").click(function(e) {

        })
    </script>
@endSection
