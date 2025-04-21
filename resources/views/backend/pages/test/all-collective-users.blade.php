@extends('backend.layouts.app')
@section('title','Kollektiv')

@section('content')
    <section class="content">
        <div class="container-fluid">

        </div>

        <div id="panel" class="container-fluid pt-3 p-5 bg-white">
            <form action="{{ route('backend.collective.users.list') }}">
                <div class="form-row">
                    <div class="col">
                        <input type="text" class="form-control" value="{{ old('UIN', request('UIN')) }}"
                               placeholder="Kollektivin kodu" name="UIN">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control"
                               value="{{ old('director_fin_code', request('director_fin_code')) }}"
                               placeholder="Kollektiv rəhbərinin FİN-i" name="director_fin_code">
                    </div>
                    <div class="col">
                        <select name="collective_nomination_id" class="form-control">
                            <option value="" hidden>Nominasiya</option>
                            @foreach($nominations as $nomination)
                                <option
                                    value="{{ $nomination->id }}" {{ request('collective_nomination_id') == $nomination->id ? 'selected' : '' }}>{{ $nomination->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row mt-5">
                    <div class="col">
                        <input type="text" class="form-control"
                               value="{{ old('collective_name', request('collective_name')) }}"
                               placeholder="Kollektivin adı" name="collective_name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control"
                               value="{{ old('director_name', request('director_name')) }}"
                               placeholder="Kollektiv rəhbərinin adı" name="director_name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control"
                               value="{{ old('director_surname', request('director_surname')) }}"
                               placeholder="Kollektiv rəhbərinin soyadı" name="director_surname">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control"
                               value="{{ old('director_patronymic', request('director_patronymic')) }}"
                               placeholder="Kollektiv rəhbərinin  ata adı" name="director_patronymic">
                    </div>
                </div>
                <div class="form-row mt-5">
                    <div class="col">
                        <select name="collective_city_id" class="form-control">
                            <option value="" hidden>Müraciət etdiyi şəhər/rayon</option>
                            @foreach($cities as $city)

                                <option
                                    value="{{ $city->id }}" {{ request('collective_city_id') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>

                            @endforeach

                        </select>
                    </div>
                    <div class="col">
                        <select name="collective_mn_region_id" class="form-control">
                            <option value="" hidden>Yaşadığı şəhər/rayon</option>
                            @foreach($regions as $region)

                                <option
                                    value="{{ $region->id }}" {{ request('collective_mn_region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>

                            @endforeach

                        </select>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control"
                               value="{{ old('collective_created_date', request('collective_created_date')) }}"
                               placeholder="Kollektivin yarandığı il" name="collective_created_date">
                    </div>
                    <div class="col">
                        <select name="age_category" class="form-control text-center">
                            <option value="" hidden>Yaş kateqoriyası</option>
                            <option value="6-9" {{ request('age_category') == '6-9' ? 'selected' : '' }}>6-9</option>
                            <option value="10-13" {{ request('age_category') == '10-13' ? 'selected' : '' }}>10-13
                            </option>
                            <option value="14-17" {{ request('age_category') == '14-17' ? 'selected' : '' }}>14-17
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row mt-5 text-right">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-secondary">Axtar</button>
                        <a href="{{ route('backend.collective.users.list') }}" class="btn btn-secondary">Sıfırla</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="container-fluid mt-5 pt-3 bg-white">
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-11 text-primary"><h3>Say : {{$count}}  </h3></div>
            </div>
            <div class="col-sm-12">
                <table class="table table-borderless table-responsive">
                    <thead>
                    <tr>
                        <th class="text-center">№</th>
                        <th class="text-center">Məkan</th>
                        <th class="text-center">Müraciət etdiyi nominasiya</th>
                        <th class="text-center">Yaşadığı şəhər/rayon</th>
                        <th class="text-center">Tarix</th>
                        <th class="text-center">Saat</th>
                        <th class="text-center">Ətraflı</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key => $value)
                        <tr class="setir">
                            <td class="text-center sutun">{{ $key + $data->firstItem() }}</td>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('precincts')->where('id',$value->precinct_id)->first()->place_name ?? null}}</td>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('nominations')->select('name')->where('id',$value->collective_nomination_id)->first()->name }}</td>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('m_n_regions')->select('name')->where('id',$value->collective_mn_region_id)->first()->name }}</td>
                            <td class="text-center">{{ $value->date != null ? \Carbon\Carbon::parse($value->date)->format('d.m.Y')  : null }}</td>
                            <td class="text-center">{{  $value->time != null ?  \Carbon\Carbon::parse($value->time)->format('H:i')  : null }}</td>
                            <td>
                                <a target="_blank" class="btn btn-sm btn-secondary"
                                   href="{{ route('backend.collective-changes.edit',$value->id) }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $data->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
            </div>

        </div>


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
    {{--   <!-- Select2 -->
       <script src="{{ asset('backend/assets/plugins/select2/js/select2.full.min.js') }}"></script>
       <script>
           $(function () {
               //Initialize Select2 Elements
               $('.select2bs4').select2({
                   theme: 'bootstrap4'
               })
           })
       </script>--}}
    <script>
        $(document).ready(function () {
            $("#flip").click(function () {
                $("#panel").slideToggle();
            });
        });
    </script>
@endpush
@push('customCss')
    {{--    <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css') }}">--}}
    <style>
        #panel {
            display: none;
        }

        #flip {
            cursor: pointer;
        }

        #panel, #flip {
            border: solid 1px #c3c3c3;
        }

        .setir {
            border: 10px solid #ffffff;
            background-color: rgba(0, 0, 0, .05);

        }

        .sutun {
            background-color: white;
        }
    </style>
@endpush


