@extends('backend.layouts.app')
@section('title','Qiymətləndirmə')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="">Siyahı</a></li>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-right">
                        <a href="{{ route('backend.personal.export.excel',request()->query()) }}"
                           class="btn btn-secondary">
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
            <form action="{{ route('backend.judges.evaluate.first.step') }}">
                <div class="form-row">
                    <div class="col">
                        <select name="precinct_id" class="form-control">
                            <option value="" hidden>Məkanlar</option>
                            @foreach($precincts as $id => $name)
                                <option value="{{ $id }}" {{ request('precinct_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select name="nomination_id" class="form-control">
                            <option value="" hidden>Nominasiyalar</option>

                            @foreach($nominations as $id => $name)
                                <option value="{{ $id }}" {{ request('nomination_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row mt-5">
                    <div class="col">
                        <select name="criterion_id" class="form-control">
                            <option value="" hidden>Meyarlar</option>

                            @foreach($criteria as $id => $name)
                                <option value="{{ $id }}" {{ request('criterion_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select name="judge_id" class="form-control">
                            <option value="" hidden>Münsiflər</option>

                            @foreach($judges as $id => $name)
                                <option value="{{ $id }}" {{ request('judge_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="form-row mt-5">
                    <div class="col">
                        <select name="score" class="form-control">
                            <option value="" hidden>Ballar</option>
                            @for($i = 1;$i <= 10;$i++)
                                <option value="{{ $i }}" {{ request('score') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col">
                        <select name="type" class="form-control">
                            <option value="" hidden>Tipi</option>


                            <option value="1" {{ request('type') == 1 ? 'selected' : '' }}>Fərdi</option>
                            <option value="2" {{ request('type') == 2 ? 'selected' : '' }}>Kollektiv</option>

                        </select>
                    </div>

                </div>
                <div class="row mt-5 text-right">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-secondary">Axtar</button>
                        <a href="{{ route('backend.judges.evaluate.first.step') }}"
                           class="btn btn-secondary">Sıfırla</a>
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
                        <th class="text-center">Nominasiya</th>
                        <th class="text-center">Kod</th>
                        <th class="text-center">Ad</th>
                        <th class="text-center">Soyad</th>
                        <th class="text-center">Meyar</th>
                        <th class="text-center">Münsif</th>
                        <th class="text-center">Bal</th>
                        <th class="text-center">Tipi</th>
                        <th class="text-center">Tarix</th>
                        <th class="text-center">Saat</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key => $value)
                        <tr class="setir">
                            <td class="text-center sutun">{{ $key + $data->firstItem() }}</td>
                            <td class="text-center">   {{ $precincts[$value->precinct_id] ?? '-' }}</td>
                            <td class="text-center">{{ $nominations[$value->nomination_id] ?? '-' }}</td>
                            @if($value->type == 1)
                                <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('personal_user_card_information')->where('personal_user_id',$value->personal_id)->first()->UIN ?? null }}</td>
                                <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('personal_users')->where('id',$value->personal_id)->first()->name ?? null }}</td>
                                <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('personal_users')->where('id',$value->personal_id)->first()->surname  ?? null}}</td>
                            @else
                                <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('collective_directors')->where('collective_id',$value->collective_id)->first()->UIN ?? null }}</td>
                                <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('collectives')->where('id',$value->collective_id)->first()->name ?? null }}</td>
                                <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('collectives')->where('id',$value->collective_id)->first()->surname  ?? null}}</td>
                            @endif
                            <td class="text-center">{{ $criteria[$value->criterion_id] ?? '-' }}</td>
                            <td class="text-center">{{ $judges[$value->judge_id] ?? '-' }}</td>
                            <td class="text-center"><b>{{ $value->score }}</b></td>
                            <td class="text-center">{{ $value->type == 1 ? 'Fərdi' : 'Kollektiv' }}</td>
                            <td class="text-center">{{  \Carbon\Carbon::parse($value->created_at )->format('d.m.Y')}}</td>
                            <td class="text-center">{{  \Carbon\Carbon::parse($value->created_at )->format('H:i:s')}}</td>

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
        $(document).ready(function () {
            $("#flip").click(function () {
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








