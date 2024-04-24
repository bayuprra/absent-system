@php
    $sessionData = session()->get('data');
    $idKaryawan = $sessionData->idKaryawan ?? '';
    $idAkun = $sessionData->id ?? '';
    use Carbon\Carbon;

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
                        @if ($dataAbsent->absenttime['status'])
                            <div class="card-header row">
                                NO ABSENT TODAY
                            </div>
                        @else
                            <div class="card-header row">
                                @php
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
                                        {!! $checkin ? 'style="cursor:no-drop;pointer-events: none;"' : '' !!} data-toggle="modal" data-target="#modal-add" id="checkin">
                                        <i class="far
                                    fa-calendar-check"></i> Checking
                                        In
                                    </a>
                                </div>

                                <div class="col-6"><a class="btn btn-app btn-block {{ $checkout ? 'bg-secondary' : '' }}"
                                        {!! $checkout ? 'style="cursor:no-drop;pointer-events: none;"' : '' !!} data-toggle="modal" data-target="#modal-add" id="checkout"
                                        {!! $checkin == false ? 'style="cursor:no-drop;pointer-events: none;"' : '' !!}>
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
                        @endif
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
                    <button type="button" id="saveAbsen" class="btn btn-primary" data-absentId="{{ $dataAbsent->id ?? 0 }}"
                        disabled>Simpan Absent</button>
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
        const locLat = -6.2425440;
        const locLong = 106.8454430;

        //ubah 2 baris ini jika akan testing
        const optLatitude = -6.2425440;
        const optLongitude = 106.8454430;

        function takeAbsent() {
            const map = L.map('map');
            map.setView([51.505, -0.09], 15);
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
                let accuracy = position.coords.accuracy;

                userLatitude = position.coords.latitude;
                userLongitude = position.coords.longitude;
                if (accuracy > 50) {
                    userLatitude = optLatitude;
                    userLongitude = optLongitude;
                    accuracy = 50;
                }
                const distance = calcCrow(locLat, locLong, userLatitude, userLongitude);
                $("#saveAbsen").prop('disabled', false).attr('data-distance', distance)
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

        function calcCrow(lat1, lon1, lat2, lon2) {
            var R = 6371; // km
            var dLat = toRad(lat2 - lat1);
            var dLon = toRad(lon2 - lon1);
            var lat1 = toRad(lat1);
            var lat2 = toRad(lat2);

            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c;
            return d;
        }

        function toRad(Value) {
            return Value * Math.PI / 180;
        }

        $("#saveAbsen").click(function(e) {
            console.log($(this).data())
            const distance = $(this).data('distance');
            const status = $(this).data('status');
            const absent = $(this).data('absentid');

            if (!distance || !status || absent === 0) {
                alert("please refresh page");
                location.reload()
            }
            const isWFO = false;
            if (parseInt(distance) < 0.5) {
                isWFO = true;
            }
            const dataAbsent = {
                id: parseInt(absent),
                status: status,
                distance: isWFO
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $(
                        'meta[name="csrf-token"]').attr(
                        'content')
                },
                url: "{{ route('takeAbsent') }}",
                type: 'POST',
                data: {
                    'data': dataAbsent
                },
                dataType: 'json',
                success: function(response) {
                    $("#modal-add").hide()
                    let timerInterval;
                    Swal.fire({
                        title: "check" + status + " Berhasil!",
                        html: "Otomatis menutup dalam <b></b> detik.",
                        timer: 5000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                                const remainingTime = Swal.getTimerLeft();
                                timer.textContent =
                                    `${Math.ceil(remainingTime / 1000)}`;
                            }, 1000);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                    }).then((result) => {
                        location.reload();
                    });

                },
                error: function(err) {
                    console.log(err)
                }
            });
        })
        $("#checkin").click(function(e) {
            $("#saveAbsen").attr('data-status', 'in')
        })
        $("#checkout").click(function(e) {
            $("#saveAbsen").attr('data-status', 'out')
        })
    </script>
@endSection
