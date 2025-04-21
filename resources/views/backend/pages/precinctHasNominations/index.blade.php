@extends('backend.layouts.app')
@section('title','Məntəqələr üzrə nominasiyalar')
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
                                            <h5 class="text-center">Məntəqə üzrə axtarış</h5>
                                        </div>
                                        <!-- /.card-header -->
                                        <!-- form start -->
                                        <form id="panel3" action="{{ route('backend.precincts-has-nominations.search') }}"
                                              method="get" class="form-horizontal">
                                            @csrf
                                            <div class="card-body">

                                                <select name="precinct_id" class="form-control select2bs4"
                                                        placeholder="Məntəqə seçin"
                                                        id="sub_category_name">
                                                    <option value="0" disabled selected hidden>Məntəqə seçin
                                                    </option>
                                                    @foreach( $precincts as $r)
                                                        <option value="{{ $r->id }}">{{ ucfirst($r->place_name )}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <!-- /.card-body -->
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn btn-info">Axtar</button>
                                            </div>
                                            <!-- /.card-footer -->
                                        </form>
                                    </div>
                                </div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4 text-right">
                                    <a href="{{ route('backend.precincts-has-nominations.create') }}">
                                        <button class="btn btn-primary mr-3">
                                            <i class="fas fa-plus-circle"></i>&nbsp; Əlavə et
                                        </button>
                                    </a>
                                </div>
                            </div>

                            <table class="table table-responsive-sm">
                                <thead>
                                <tr class="table-tr-bg">
                                    <th class="align-middle text-center">#</th>
                                    <th class="align-middle text-center">Məntəqə</th>
                                    <th class="align-middle text-center">Nominasiya</th>
                                    <th class="align-middle text-center">Başlama tarixi</th>
                                    <th class="align-middle text-center">Bitmə tarixi</th>
                                    <th class="align-middle text-center">Redaktə</th>
                                </tr>
                                </thead>
                                <tbody id="sortable">
                                @foreach($data as $key => $d)
                                    <tr>
                                        <td class="align-middle text-center">{{ $key + $data->firstItem() }}</td>
                                        <td class="align-middle text-center font-weight-bold">
                                            {{ \App\Models\Precinct::where('id',$d->precinct_id)->first()->place_name ?? null }}
                                        </td>
                                        <td class="align-middle text-center font-weight-bold">
                                            {{ \App\Models\Nomination::where('id',$d->nomination_id)->first()->name ?? null }}
                                        </td>
                                        <td class="align-middle text-center font-weight-bold">{{  \Carbon\Carbon::parse($d->start_date )->format('d.m.Y')  }}</td>
                                        <td class="align-middle text-center font-weight-bold">{{  \Carbon\Carbon::parse($d->end_date)->format('d.m.Y')  }}</td>
                                        <td class="align-middle text-center">
                                            <a  class="btn btn-sm btn-warning"
                                               href="{{ route('backend.precincts-has-nominations.edit',$d->id) }}">
                                                <i class="fas fa-edit"></i>&nbsp;<span
                                                        class="d-none d-lg-inline-block">&nbsp Redaktə et</span>
                                            </a>
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
    <script>
        $(document).ready(function () {
            $("#flip").click(function () {
                $("#panel").slideToggle();
            });
            $("#flip2").click(function () {
                $("#panel2").slideToggle();
            });
            $("#flip3").click(function () {
                $("#panel3").slideToggle();
            });
            $('.hesabla').on('click', function () {
                let m1 = parseInt($('#m1').val());
                let m2 = parseInt($('#m2').val());
                let m3 = parseInt($('#m3').val());
                let m4 = parseInt($('#m4').val());
                let m5 = parseInt($('#m5').val());
                let l1 = parseInt($('#l1').val());
                let l2 = parseInt($('#l2').val());
                let l3 = parseInt($('#l3').val());
                let l4 = parseInt($('#l4').val());
                let l5 = parseInt($('#l5').val());
                let n1 = parseInt($('#n1').val());
                let n2 = parseInt($('#n2').val());
                let n3 = parseInt($('#n3').val());
                let n4 = parseInt($('#n4').val());
                let n5 = parseInt($('#n5').val());
                let r = (m1 + m2 + m3 + m4 + m5 + l1 + l2 + l3 + l4 + l5 + n1 + n2 + n3 + n4 + n5) / 3;
                r = Math.floor(r);
                $('#toplam').val(r);
                return false;
            });
        });
    </script>
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


