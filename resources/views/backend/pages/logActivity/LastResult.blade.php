@extends('layouts.backend', ['title' => 'Log-Last Result'])

<style>
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col-lg-3,
    .col-6 {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .small-box {
        position: relative;
        display: block;
        overflow: hidden;
        height: 90px;
        text-align: center;
        color: #fff;
    }

    .small-box .inner {
        padding: 10px;
    }

    .small-box .icon {
        position: absolute;
        top: auto;
        bottom: 0;
        right: 0;
        padding: 10px;
    }

    .small-box .icon>.ion {
        font-size: 50px;
        display: inline-block;
        margin-right: 10px;
    }

    .small-box-footer {
        display: block;
        padding: 3px 0;
        background: rgba(0, 0, 0, 0.1);
        color: #fff;
        text-align: center;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }
</style>

@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="">Activity Last Result</a></li>
@endsection
@section('content-backend')
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <ul class="list-unstyled nav">
                        <div class="table-responsive" style="width: 35%;">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Percobaan Pengerjaan</th>
                                        <td>Value for Nama</td>
                                    </tr>
                                    <tr>
                                        <th>Benar</th>
                                        <td>Value for NIM</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        @hasrole('superadmin')
                            <li class="nav-item ml-3"><a href="{{ route('user.create') }}"><i class="mdi mdi-animation"></i></a>
                            </li>
                        @endhasrole
                    </ul>
                    <br>
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>150</h3>

                                    <p>Total Percobaan</p>
                                </div>
                                <div class="icon">
                                    <i class="mdi mdi-pencil-box"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>53<sup style="font-size: 20px">%</sup></h3>

                                    <p>Jawaban benar</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->

                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>65</h3>

                                    <p>Jawaban salah</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>44</h3>

                                    <p>Total Waktu</p>
                                </div>
                                <div class="icon">
                                    <i class="mdi mdi-clock"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-3">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Total Percobaan Pengerjaan</th>
                                    </tr>
                                    <tr>
                                        <th>NIM</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                    </tr>
                                    <tr>
                                        <th>NIM</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                    </tr>
                                    <tr>
                                        <th>NIM</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                    </tr>
                                    <tr>
                                        <th>NIM</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div> --}}



                    <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Question</th>
                                <th>Confidence Tag</th>
                                <th>Result</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        {{-- <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->user->nim }}</td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm btn-edit">
                                            <i class="mdi mdi-eye"></i> see
                                        </a>
                                        <form action="#" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-delete"
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="mdi mdi-delete"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> --}}
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('after-script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets_backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets_backend/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets_backend/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets_backend/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets_backend/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets_backend/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets_backend/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets_backend/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets_backend/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets_backend/plugins/datatables/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets_backend/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets_backend/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('assets_backend/pages/datatables.init.js') }}"></script>
@endpush
