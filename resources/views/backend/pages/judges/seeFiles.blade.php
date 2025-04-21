@extends('backend.layouts.app')
@section('title','Münsiflərin faylları')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">



                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card card-info ">
                                        <div class="bg-info pt-2 pb-2" id="flip3">
                                            <h5 class="text-center">Məntəqə və nominasiya üzrə axtarış</h5>
                                        </div>
                                        <!-- /.card-header -->
                                        <!-- form start -->
                                        <form id="panel4" action="{{ route('backend.see.judges.files') }}"
                                              method="get" class="form-horizontal">
                                            @csrf
                                            <div class="card-body">
                                                <div>
                                                    <select name="precinct_id_group" class="form-control select2bs4"
                                                            placeholder="Şəhər/Rayon seçin">
                                                        <option value="0" disabled selected hidden>Məntəqəni seçin
                                                        </option>
                                                        @foreach( $precincts as $r)
                                                            <option value="{{ $r->id }}">{{ ucfirst($r->place_name )}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div style="margin-top: 25px">
                                                    <select name="nomination_id_group"
                                                            class="form-control select2bs4 required"
                                                            placeholder="Nominasiyanı seçin">
                                                        <option value="0" disabled selected hidden>Nominasiyanı seçin
                                                        </option>
                                                        @foreach( $nominations as $n)
                                                            <option value="{{ $n->id }}">{{ ucfirst($n->name )}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn btn-info">Axtar</button>
                                            </div>
                                            <!-- /.card-footer -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-responsive-sm">
                                <thead>
                                <tr class="table-tr-bg">
                                    <th class="align-middle text-center">#</th>
                                    <th class="align-middle text-center">Münsifin adı, <br>soyadı</th>
                                    <th class="align-middle text-center">Nominasiyanın <br> adı</th>
                                    <th class="align-middle text-center">Məntəqə</th>
                                    <th class="align-middle text-center">Tarix</th>
                                    <th class="align-middle text-center">Vaxt</th>
                                    <th class="align-middle text-center">Fayllar</th>
                                    <th class="align-middle text-center">Sil</th>
                                </tr>
                                </thead>
                                <tbody id="sortable">
                                @foreach($data as $key => $d)
                                    <tr>
                                        <td class="align-middle text-center">{{ $key + $data->firstItem() }}</td>
                                        @php
                                            $nomination = \App\Models\Nomination::select('id','name')->where('id',$d->nomination_id)->firstOrFail();
                                            $precinct = \App\Models\Precinct::select('id','place_name')->where('id',$d->precinct_id)->firstOrFail();
                                           $judge = \App\Models\JudgesList::select('id','name')->where('id',$d->judge_id)->firstOrFail();
                                         //  echo $d->judge_id;
                                        @endphp
                                        <td class="align-middle text-center">{{ $judge->name }}</td>
                                        <td class="align-middle text-center">{{ $nomination->name }}</td>
                                        <td class="align-middle text-center">{{ $precinct->place_name }}</td>
                                        <td class="align-middle text-center">{{ $d->date }}</td>
                                        <td class="align-middle text-center">{{ $d->time }}</td>
                                        <td class="align-middle text-center">
                                            <span class="font-weight-bold">
                                                <a class="btn btn-info" target="_blank" href="{{ route('backend.see.judges.files.detail',$d->id) }}">
                                                    Fayllara bax
                                                </a>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a class="delete-item btn btn-sm btn-danger" id="{{ $d->id }}"
                                               href="javascript:void(0)">
                                                <i class="fas fa-trash-alt"></i>&nbsp;
                                                <span class="d-none d-lg-inline-block">Sil</span>
                                            </a>
                                            <form class="d-none" id="btn-delete-{{ $d->id}}"
                                                  action="{{ route('backend.judges.files.delete',$d->id) }}"
                                                  method="post">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- /.card-body -->
                        <div class="card-footer  clearfix">
                            {{ $data->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                    <!-- /.card -->
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
    <script>
        $(".delete-item").on('click', function (e) {
            let destroy_id = e.currentTarget.id;
            alertify.confirm('Silmək istədiyinizə əminsiniz ?', null,
                function () {
                    event.preventDefault();
                    document.getElementById('btn-delete-' + destroy_id).submit();
                },
                function () {
                    alertify.error('Cancel')
                }
            )
        });
    </script>
@endpush
@push('customCss')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('backend/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="{{ asset('backend/assets/myCustom/css/alertify/default.min.css') }}"/>
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
    <!-- JavaScript -->
    <script src="{{ asset('backend/assets/myCustom/js/alertify/alertify.min.js') }}"></script>
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


