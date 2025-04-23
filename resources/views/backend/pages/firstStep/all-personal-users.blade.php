@extends('backend.layouts.app')
@section('title','Fərdi iştirakçılar')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.personal.users.list') }}">Bütün iştirakçılar</a></li>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-right">
                        <a href="{{ route('backend.personal.export.excel',request()->query()) }}"  class="btn btn-secondary">
                            Excel-ə çıxar
                        </a>
                      {{--  <a href="{{ route('backend.personal.users.numbers.list',request()->query()) }}"  class="btn btn-secondary">
                            Nömrə Excel-ə çıxar
                        </a>--}}
                    </div>
                </div>
            </div>

        </div>
        @role('admin')
        <div  class="container-fluid mt-5 p-2 bg-white">
                <div class="col-sm-12 text-center"><h2>{{ \Illuminate\Support\Facades\DB::table('precincts')->where('id',$user_precinct)->first()->place_name }}</h2></div>
        </div>
        @endrole
        <div id="flip" class="container-fluid mt-5 p-2 bg-white">
            <div class="col-sm-12 text-center"><h4>Axtarış</h4></div>
        </div>

        <div id="panel" class="container-fluid pt-5 p-3 bg-white">
            <form action="{{ route('backend.personal.users.list') }}">
                <div class="form-row">
                    <div class="col">
                        <input type="text" class="form-control" value="{{ old('UIN', request('UIN')) }}"
                               placeholder="İştirakçı kodu" name="UIN">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" value="{{ old('fin_code', request('fin_code')) }}"
                               placeholder="FİN" name="fin_code">
                    </div>
                    <div class="col">
                        <select name="nomination_id" class="form-control">
                            <option value="" hidden>Nominasiya</option>
                            @foreach($nominations as $nomination)
                                <option
                                    value="{{ $nomination->id }}" {{ request('nomination_id') == $nomination->id ? 'selected' : '' }}>{{ $nomination->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row mt-5">
                    <div class="col">
                        <input type="text" class="form-control" value="{{ old('name', request('name')) }}"
                               placeholder="Ad" name="name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" value="{{ old('surname', request('surname')) }}"
                               placeholder="Soyad" name="surname">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" value="{{ old('patronymic', request('patronymic')) }}"
                               placeholder="Ata adı" name="patronymic">
                    </div>
                </div>
                <div class="form-row mt-5">
                    <div class="col">
                        <input type="date" class="form-control" value="{{ old('birth_date', request('birth_date')) }}"
                               placeholder="Doğum tarixi" name="birth_date">
                    </div>

                    <div class="col">
                        <select name="gender" class="form-control">
                            <option value="" hidden>Cinsi</option>
                            <option value="MALE" {{ request('gender') == 'MALE' ? 'selected' : '' }}>Kişi</option>
                            <option value="FEMALE" {{ request('gender') == 'FEMALE' ? 'selected' : '' }}>Qadın</option>
                        </select>
                    </div>
                    <div class="col">
                        <select  name="age_category" class="form-control text-center">
                            <option value="" hidden>Yaş kateqoriyası</option>
                            <option value="6-9" {{ request('age_category') == '6-9' ? 'selected' : '' }}>6-9</option>
                            <option value="10-13" {{ request('age_category') == '10-13' ? 'selected' : '' }}>10-13</option>
                            <option value="14-17" {{ request('age_category') == '14-17' ? 'selected' : '' }}>14-17</option>
                        </select>
                    </div>
                    <div class="col">
                        <select name="age" class="form-control text-center">
                            <option value="" hidden>Yaş</option>
                            @for ($i = 6; $i <= 17; $i++)
                                <option value="{{ $i }}" {{ request('age') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                </div>
                <div class="form-row mt-5">
                    @role('developer|superadmin|content manager')
                    <div class="col">
                        <select name="all_city_id" class="form-control">
                            <option value="" hidden>Müraciət etdiyi şəhər/rayon</option>
                            @foreach($cities as $city)

                                <option
                                    value="{{ $city->id }}" {{ request('all_city_id') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>

                            @endforeach

                        </select>
                    </div>
                    <div class="col">
                        <select name="mn_region_id" class="form-control">
                            <option value="" hidden>Yaşadığı şəhər/rayon</option>
                            @foreach($regions as $region)

                                <option
                                    value="{{ $region->id }}" {{ request('mn_region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>

                            @endforeach

                        </select>
                    </div>
                    <div class="col">




                        <select name="precinct_id" class="form-control">
                            <option value="" hidden>Məkan</option>


                           @foreach( $precinct_data as $key => $pd)
                                <option value="{{ $key }}" >{{ $pd}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endrole
                </div>
                <div class="row mt-5 text-right">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-secondary">Axtar</button>
                        <a href="{{ route('backend.personal.users.list') }}" class="btn btn-secondary">Sıfırla</a>
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
                        @role('developer|superadmin|content manager')
                        <th class="text-center">Məkan</th>
                        @endrole
                        <th class="text-center">Kod</th>
                        <th class="text-center">FİN</th>
                        <th class="text-center">Ad</th>
                        <th class="text-center">Soyad</th>
                        <th class="text-center">Ata adı</th>
                    {{--    <th class="text-center">Doğum tarixi</th>--}}
                        <th class="text-center">Cinsi</th>
                        <th class="text-center">Müraciət etdiyi nominasiya</th>
                        <th class="text-center">Müraciət etdiyi şəhər/rayon</th>
                        <th class="text-center">Yaşadığı şəhər/rayon</th>
                        <th class="text-center">Yaş kateqoriyası</th>
                        <th class="text-center">Tarix</th>
                        <th class="text-center">Saat</th>
                        <th class="text-center">Nəticə</th>
                     {{--   <th class="text-center">Yaş</th>--}}
                        <th class="text-center">Ətraflı</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key => $value)
                        <tr class="setir">
                            <td class="text-center sutun">{{ $key + $data->firstItem() }}</td>
                            @role('developer|superadmin|content manager')
                            <td class="text-center">{{ $precinct_data[$value->precinct_id] ?? null }}</td>
                            @endrole
                            <td class="text-center"><b>{{ $value->UIN }}</b></td>
                            <td class="text-center">{{ $value->fin_code }}</td>
                            <td class="text-center">{{ $value->name }}</td>
                            <td class="text-center">{{ $value->surname }}</td>
                            <td class="text-center">{{ $value->patronymic }}</td>
              {{--              <td class="text-center">{{  \Carbon\Carbon::parse($value->birth_date )->format('d.m.Y')}}</td>--}}
                            <td class="text-center"><b>{{  $value->gender == 'MALE' ? 'Kişi' : 'Qadın' }}</b></td>
                            <td class="text-center">{{ $nominations_data[$value->nomination_id] ?? null}}</td>
                            <td class="text-center">{{ $cities_data[$value->all_city_id] ?? null }}</td>
                            <td class="text-center">{{ $regions_data[$value->mn_region_id] ?? null }}</td>
                            <td class="text-center">{{ $value->age_category }}</td>
                            <td class="text-center">{{ $value->date != null ? \Carbon\Carbon::parse($value->date)->format('d.m.Y')  : null }}</td>
                            <td class="text-center">{{  $value->time != null ?  \Carbon\Carbon::parse($value->time)->format('H:i')  : null }}</td>

                            @role('superadmin|developer|content manager')
                            <td class="text-center">
                                @if($value->is_absent == 0)
                                    @if($value->score == null)
                                        <span>  Qiymətləndirmə aparılmayıb</span>
                                    @else
                                        @if($value->score >= 30)
                                            <b><span class="text-success">Keçib, </span><span
                                                    style="font-size: 20px">{{ $value->score }}</span> </b>
                                        @else
                                            <b><span class="text-danger">Keçməyib, </span><span
                                                    style="font-size: 20px">{{ $value->score }}</span> </b>
                                        @endif
                                    @endif
                                @else
                                    <b><span class="text-danger">Müsabiqəyə gəlmədi</span></b>
                                @endif
                            </td>
                            @endrole
                            @role('admin')
                            <td class="text-center">
                                @if($value->is_absent == 0)
                                    @if($value->score == null)
                                        <span>Qiymətləndirmə aparılmayıb</span>
                                    @else
                                        <b><span class="text-info">Qiymətləndirmə aparılıb</span></b>
                                    @endif
                                @else
                                    <b><span class="text-danger">Müsabiqəyə gəlmədi</span></b>
                                @endif
                            </td>
                            @endrole
               {{--             <td class="text-center">{{ $value->age }}</td>--}}
                            <td>
                                <a target="_blank" class="btn btn-sm btn-secondary"
                                   href="{{ route('backend.personal.user.detail',$value->id) }}">
                                    <i class="fas fa-eye"></i>
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
    <!-- html2pdf CDN link -->
    <script src="{{ asset('backend/assets/myCustom/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        const button = document.getElementById('download-button');

        function generatePDF() {
            // Choose the element that your content will be rendered to.
            const element = document.getElementById('invoice');
            var opt = {
                margin: 1,
                filename: 'fərdi-istifadəçilər.pdf',
                html2canvas: {scale: 2},
                jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'},
            };

            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();
        }

        button.addEventListener('click', generatePDF);
    </script>
    <script>
        $(document).ready(function(){
            $("#flip").click(function(){
                $("#panel").slideToggle();
            });
        });
    </script>
@endpush
@push('customCss')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css') }}">
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








