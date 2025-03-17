@extends('backend.layouts.app')
@section('title','Fərdi iştirakçılar')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-right">
                        <a href="{{ route('backend.personal.export.excel',request()->query()) }}"  class="btn btn-secondary">
                            Excel-ə çıxar
                        </a>
                    </div>
                </div>
            </div>

        </div>
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

                </div>
                <div class="form-row mt-5">
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
                        <th class="text-center">Kod</th>
                        <th class="text-center">FİN</th>
                        <th class="text-center">Ad</th>
                        <th class="text-center">Soyad</th>
                        <th class="text-center">Ata adı</th>
                        <th class="text-center">Doğum tarixi</th>
                        <th class="text-center">Cinsi</th>
                        <th class="text-center">Müraciət etdiyi nominasiya</th>
                        <th class="text-center">Müraciət etdiyi şəhər/rayon</th>
                        <th class="text-center">Ətraflı</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key => $value)
                        <tr class="setir">
                            <td class="text-center sutun">{{ $key + $data->firstItem() }}</td>
                            <td class="text-center"><b>{{ $value->UIN }}</b></td>
                            <td class="text-center">{{ $value->fin_code }}</td>
                            <td class="text-center">{{ $value->name }}</td>
                            <td class="text-center">{{ $value->surname }}</td>
                            <td class="text-center">{{ $value->patronymic }}</td>
                            <td class="text-center">{{  \Carbon\Carbon::parse($value->birth_date )->format('d.m.Y')}}</td>
                            <td class="text-center"><b>{{  $value->gender == 'MALE' ? 'Kişi' : 'Qadın' }}</b></td>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('nominations')->select('name')->where('id',$value->nomination_id)->first()->name }}</td>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('all_cities')->select('city_name')->where('id',$value->all_city_id)->first()->city_name }}</td>
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








