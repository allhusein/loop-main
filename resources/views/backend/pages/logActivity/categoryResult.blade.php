@extends('layouts.backend', ['title' => 'Log Activity-Category Result'])

@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="">Category Result</a></li>
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
                                        <th>Nama</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIM</th>
                                        <td>{{ $user->nim }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kelas</th>
                                        <td>{{ $user->kelas }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @hasrole('superadmin')
                            <li class="nav-item ml-3"><a href="{{ route('user.create') }}"><i class="mdi mdi-animation"></i></a>
                            </li>
                        @endhasrole
                    </ul>


                    <table id="datatable-buttons" class="table table-striped table-bordered w-100 text-center">


                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Poin</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($user->categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->nilai }}</td>
                                    <td>
                                        <a href="{{ route('log.last.result', ['user' => $user->id, 'categoryId' => $category->id]) }}"
                                            class="btn btn-info btn-sm btn-edit">
                                            <i class="mdi mdi-eye"></i> see
                                        </a>
                                        {{-- <form action="#" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-delete"
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="mdi mdi-delete"></i> Delete
                                            </button>
                                        </form> --}}
                                    </td>
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
