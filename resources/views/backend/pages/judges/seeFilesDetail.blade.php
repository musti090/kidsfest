@extends('backend.layouts.app')
@section('title','Münsifin faylları')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('backend.see.judges.files.detail.store',$data->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <table class="table">
                                    <tr>
                                        <th><h2>Fayl 1 (10:00 - 11:00) <span><br><a class="btn btn-info mt-3" target="_blank" href="{{ asset($data->file1) }}">Cari fayl</a></span>   </h2></th>
                                        <td>
                                            <div class="form-group">
                                                <label for="file1">Fayl 1 yeniləmək</label>
                                                <input name="file1" type="file" class="form-control" id="file1">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> <h2>Fayl 2 (11:00 - 12:00) <span><br><a class="btn btn-info mt-3" target="_blank" href="{{ asset($data->file2) }}">Cari fayl</a></span>   </h2></th>

                                        <td>
                                            <div class="form-group">
                                                <label for="file2">Fayl 2 yeniləmək</label>
                                                <input name="file2" type="file" class="form-control" id="file2">
                                                </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>  <h2>Fayl 3 (12:00 - 13:00) <span><br><a class="btn btn-info mt-3" target="_blank" href="{{ asset($data->file3) }}">Cari fayl</a></span>   </h2></th>
                                        <td>
                                            <div class="form-group">
                                                <label for="file3">Fayl 3 yeniləmək</label>
                                                <input name="file3" type="file" class="form-control" id="file3">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><h2>Fayl 4 (13:00 - 14:00) <span><br><a class="btn btn-info mt-3" target="_blank" href="{{ asset($data->file4) }}">Cari fayl</a></span>   </h2></th>
                                        <td>
                                            <div class="form-group">
                                                <label for="file4">Fayl 4 yeniləmək</label>
                                                <input name="file4" type="file" class="form-control" id="file4">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Təsdiqlə</button>
                                </div>


                            </form>

                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
@push('customJs')
    <script src="{{ asset('backend/assets/plugins/toastr/toastr.min.js') }}"></script>
    @if( session()->has('success') )
        <script> toastr.success('{{ session('success') }}')</script>
    @endif
    @if( session()->has('error') )
        <script> toastr.error('{{ session('error') }}')</script>
    @endif

    <!-- Select2 -->
    <script src="{{ asset('backend/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
@endpush
@push('customCss')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('backend/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css') }}">

    <style>
        #panel, #panel2, #panel3 {
            display: none;
        }

        #flip, #flip2, #flip3 {
            cursor: pointer;
        }

    </style>
@endpush




