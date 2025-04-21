@extends('backend.layouts.app')
@section('title','Bütün məntəqələr')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card table-responsive table-hover">
                        <div class="card-header">
                            <h6 class="card-title">Məntəqələrin siyahısı</h6>
                        </div>

                        <!-- ./card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-4 text-right mb-3">
                                    <a href="{{ route('backend.precincts.create') }}">
                                        <button class="btn btn-primary mr-3">
                                            <i class="fas fa-plus-circle"></i>&nbsp; Əlavə et
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <table class="table text-center">
                                <thead>
                                <tr class="table-tr-bg">
                                    <th class="align-middle text-center">Nömrə</th>
                                    <th class="align-middle text-center">Məntəqənin adı</th>
                                    <th class="align-middle text-center">Şəhər/Rayon</th>
                                    <th class="align-middle text-center">Məntəqənin ünvanı</th>
                                    <th class="align-middle text-center"> Birinci cavabdeh şəxs</th>
                                    <th class="align-middle text-center">Birinci cavabdeh şəxsin nömrəsi</th>
                                    <th class="align-middle text-center">İkinci  cavabdeh şəxs</th>
                                    <th class="align-middle text-center">İkinci  cavabdeh şəxsin nömrəsi</th>
                                    <th class="align-middle text-center">Redaktə</th>
                                </tr>
                                </thead>
                                <tbody style="padding-top: 10px" id="sortable">

                                @foreach( $data as $key => $user)
                                    <tr>
                                        <td class="sortable">{{ $key + $data->firstItem() }}</td>
                                        <td class="sortable">{{ $user->place_name  }}</td>
                                        <td class="sortable">{{ $user->sheher->city_name  }}</td>
                                        <td class="sortable">{{ $user->place_address  }}</td>
                                        <td class="sortable">{{ $user->first_person  }}</td>
                                        <td class="sortable">{{ $user->first_person_number  }}</td>
                                        <td class="sortable">{{ $user->second_person  }}</td>
                                        <td class="sortable">{{ $user->second_person_number  }}</td>
                                        <td class="align-middle text-center">
                                            <a  class="btn btn-sm btn-warning"
                                               href="{{ route('backend.precincts.edit',$user->id) }}">
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
                        <div class="card-footer clearfix">
                        {{ $data->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                    <!-- /.card -->

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
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
@endpush
@push('customCss')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css') }}">
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="{{ asset('backend/assets/myCustom/css/alertify/default.min.css') }}"/>
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
    <!-- JavaScript -->
    <script src="{{ asset('backend/assets/myCustom/js/alertify/alertify.min.js') }}"></script>
@endpush


