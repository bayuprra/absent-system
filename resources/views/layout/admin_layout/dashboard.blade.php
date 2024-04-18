@php
    use Carbon\Carbon;
@endphp
@extends('layout.main_layout.main')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Karyawan</span>
                            <span class="info-box-number">
                                {{ $sumKaryawan ?? 0 }}
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-6">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Divisi</span>
                            <span class="info-box-number">{{ $sumDivisi ?? 0 }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Today's Absent Report</h5>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-center">
                                        <strong>Date :
                                            {{ Carbon::parse(now())->locale('id_ID')->isoFormat('d MMMM YYYY') }}</strong>
                                    </p>
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3966.160674761011!2d106.84286877499046!3d-6.2425446937457565!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNsKwMTQnMzMuMiJTIDEwNsKwNTAnNDMuNiJF!5e0!3m2!1sid!2sid!4v1713419577812!5m2!1sid!2sid"
                                        width="600" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-4">
                                    <p class="text-center">
                                        <strong>Status</strong>
                                    </p>
                                    <div class="progress-group">
                                        Checking In
                                        <span
                                            class="float-right"><b>{{ $dataDashboard['countUserCheckin'] ?? 0 }}</b>/{{ $dataDashboard['countTodayAbsent'] ?? 0 }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ $dataDashboard['countUserCheckinPercentage'] ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                    <div class="progress-group">
                                        Checking Out
                                        <span
                                            class="float-right"><b>{{ $dataDashboard['countUserCheckout'] ?? 0 }}</b>/{{ $dataDashboard['countTodayAbsent'] ?? 0 }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-danger"
                                                style="width: {{ $dataDashboard['countUserCheckoutPercentage'] ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- ./card-body -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-3 col-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-info"><i class="fas fa-clock"></i>
                                            @if ($dataDashboard['firstUserCheckinTime'])
                                                {{ Carbon::parse($dataDashboard['firstUserCheckinTime'])->locale('id_ID')->isoFormat('HH:mm:ss') }}
                                            @else
                                                No Data
                                            @endif
                                        </span>
                                        <h5 class="description-header">
                                            {{ $dataDashboard['firstUserCheckin'] ?? 'No Data' }}
                                        </h5>
                                        <span class="description-text">First User Checking In</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-info"><i class="fas fa-clock"></i>
                                            @if ($dataDashboard['lastUserCheckinTime'])
                                                {{ Carbon::parse($dataDashboard['lastUserCheckinTime'])->locale('id_ID')->isoFormat('HH:mm:ss') }}
                                            @else
                                                No Data
                                            @endif
                                        </span>

                                        <h5 class="description-header">{{ $dataDashboard['lastUserCheckin'] ?? 'No Data' }}
                                        </h5>
                                        <span class="description-text">Last User Checking In</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-info"><i class="fas fa-clock"></i>
                                            @if ($dataDashboard['firstUserCheckoutTime'])
                                                {{ Carbon::parse($dataDashboard['firstUserCheckoutTime'])->locale('id_ID')->isoFormat('HH:mm:ss') }}
                                            @else
                                                No Data
                                            @endif
                                        </span>
                                        <h5 class="description-header">
                                            {{ $dataDashboard['firstUserCheckout'] ?? 'No Data' }}
                                        </h5>
                                        <span class="description-text">First User Checking Out</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-6">
                                    <div class="description-block">
                                        <span class="description-percentage text-info"><i class="fas fa-clock"></i>
                                            @if ($dataDashboard['lastUserCheckoutTime'])
                                                {{ Carbon::parse($dataDashboard['lastUserCheckoutTime'])->locale('id_ID')->isoFormat('HH:mm:ss') }}
                                            @else
                                                No Data
                                            @endif
                                        </span>
                                        <h5 class="description-header">
                                            {{ $dataDashboard['lastUserCheckout'] ?? 'No Data' }}
                                        </h5>
                                        <span class="description-text">Last User Checking Out</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
@endSection

@section('script')
@endSection
