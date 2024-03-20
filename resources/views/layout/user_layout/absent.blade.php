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
                    <div class="card-header">
                        <button id="getLocation">tes</button>
                    </div>
                    <div class="card card-primary">
                        <input type="hidden" id="karyawan_id" value="{{ $idKaryawan }}">
                        <input type="hidden" id="akun_id" value="{{ $idAkun }}">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                        <!-- /.card-body -->
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
                enableHighAccuracy: true,
                maximumAge: 30000,
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
