@extends('layouts.backend', ['title' => 'Log-Last Result'])



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
                                        <th>Nama</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIM</th>
                                        <td>{{ $user->nim }}</td>
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
                                    <h3>{{ $totalAttempts }}</h3>

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
                                    <h3>{{ $totalCorrectAnswers }}<sup style="font-size: 20px"></sup></h3>
                                    <p>Jawaban benar</p>
                                </div>
                                <div class="icon">
                                    <i class="mdi mdi-emoticon"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->

                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $totalWrongAnswers }}</h3>

                                    <p>Jawaban salah</p>
                                </div>
                                <div class="icon">
                                    <i class="mdi mdi-emoticon-neutral"></i>
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
                    </div>
                    <br>
                    <table id="datatable-buttons" class="table table-striped table-bordered w-100 text-center">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Question</th>
                                <th>Confidence Tag</th>
                                <th>Result</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        @foreach ($attempts as $attempt)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $attempt->question ? $attempt->question->question : '' }}</td>
                                <td>{{ $attempt->confidence }}</td>
                                <td>{{ $attempt->nilai }}</td>
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
@push('custom-css')
    <link href="{{ asset('assets_backend/css/emot.css') }}" rel="stylesheet">
@endpush
