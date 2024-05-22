@extends('layouts.backend', ['title' => 'History Confidence Tag-Sub Question Result'])

@section('breadcrumb')
    @if ($category && isset($category))
        <li class="breadcrumb-item active">Sub Question Result | Category: {{ $category->name }}</li>
    @else
        <li class="breadcrumb-item active">Sub Question Result</li>
    @endif
@endsection
@section('content-backend')
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <ul class="list-unstyled nav">
                        <div class="table-responsive" style="width: 20%;">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIM</th>
                                        <td>{{ $user->nim }}</td>
                                    </tr>
                                    <tr>
                                        <th>Lihat Hasil</th>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#chartModal">Lihat Hasil</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Move the chart into a modal -->
                        <div class="modal fade" id="chartModal" tabindex="-1" role="dialog"
                            aria-labelledby="chartModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="chartModalLabel">Result Confidence Tag</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="chartContainer" style="text-align: right;" class="ml-auto">
                                            <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @hasrole('superadmin')
                            <li class="nav-item ml-3"><a href="{{ route('user.create') }}"><i class="mdi mdi-animation"></i></a>
                            </li>
                        @endhasrole
                    </ul>
                    <table id="datatable-buttons" class="table table-striped table-bordered w-100 text-center">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th class="custom-width">Yakin + Benar</th>
                                <th class="custom-width">Yakin + Salah</th>
                                <th class="custom-width">Tidak Yakin + Benar</th>
                                <th class="custom-width">Tidak Yakin + Salah</th>
                                <th class="custom-width">Waktu Yang Dibutuhkan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($attempts as $attempt)
                                <tr>
                                    <td>{{ $attempt->question->question }}</td>
                                    <td>{{ $attempt->yakin_benar }}</td>
                                    <td>{{ $attempt->yakin_salah }}</td>
                                    <td>{{ $attempt->tidak_yakin_benar }}</td>
                                    <td>{{ $attempt->tidak_yakin_salah }}</td>
                                    <td>{{ $attempt->time_taken }}</td>
                                </tr>
                            @endforeach
                        </tbody>
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

@push('after-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
@endpush

@push('after-script')
    <script>
        window.onload = function() {
            const xValues = ["Yakin + Benar", "Yakin + Salah", "Tidak Yakin + Benar", "Tidak Yakin + Salah"];
            const yValues = [
                {{ $totalScores['yakin_benar'] }},
                {{ $totalScores['yakin_salah'] }},
                {{ $totalScores['tidak_yakin_benar'] }},
                {{ $totalScores['tidak_yakin_salah'] }},
            ];
            const barColors = [
                "#b91d47",
                "#00aba9",
                "#2b5797",
                "#e8c3b9",
            ];

            new Chart("myChart", {
                type: "pie",
                data: {
                    labels: xValues,
                    datasets: [{
                        backgroundColor: barColors,
                        data: yValues
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: "Parameter Confidence Tag-Sub Question Result"
                    }
                }
            });
        };
    </script>
@endpush
