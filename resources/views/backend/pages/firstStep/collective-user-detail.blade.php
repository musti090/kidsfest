@extends('backend.layouts.app')
@section('title','Kollektivin bütün məlumatları')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.collective.users.list') }}">Bütün kollektivlər</a></li>
@endsection
@section('content')
    <section class="content">
        @if($errors->any())
            @foreach($errors->all() as $error)
                <h2 class="text-center text-danger">{{ $error }}</h2>
            @endforeach
        @endif
            @role('admin')
            <div id="panel" class="container-fluid pt-5 p-3 bg-white border mb-5 no-print">
                <form action="{{ route('backend.evaluate.collective.first.step') }}" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="col d-flex justify-content-center">
                            <h3 class="mr-3">Meyarlar</h3>
                            <h3><i class='fas fa-long-arrow-alt-right'></i></h3>
                        </div>
                        @foreach($criteria as $c)
                            <div class="col d-flex justify-content-center">
                                <h4><i>{{ \Illuminate\Support\Facades\DB::table('criteria')->where('id',$c->criterion_id)->first()->name}}</i></h4>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-row">
                        <div class="col d-flex justify-content-center">
                            <h3>Münsiflər</h3>
                        </div>
                        @foreach($criteria as $c)
                            <div class="col d-flex justify-content-center">

                            </div>
                        @endforeach
                    </div>
                    <div class="form-row">
                        <div class="col d-flex justify-content-center">
                            <h3><i class='fas fa-long-arrow-alt-down'></i></h3>
                        </div>
                        @foreach($criteria as $c)
                            <div class="col d-flex justify-content-center">

                            </div>
                        @endforeach
                    </div>
                    @foreach($judges as $key => $judge)
                        <div class="form-row mt-5 text-center">
                            <div class="col d-flex justify-content-center">
                                <h5>
                                    <i>{{ \Illuminate\Support\Facades\DB::table('judges_list')->where('id',$judge->judge_id)->first()->name }}</i>
                                </h5>
                            </div>
                            @foreach($criteria as $i => $c)
                                <div class="col d-flex justify-content-center">
                                    <input id="n{{($key + 1).($i + 1)}}" type="number" min="1" max="10"
                                           class="form-control w-50 mx-auto"
                                           style="min-width: 50px; max-width: 100px" name="n{{($key + 1).($i + 1)}}"
                                           onkeypress="return digitKeyOnly(event)">
                                    <input type="hidden" name="criteria{{($key + 1).($i + 1)}}" value="{{ $c->criterion_id }}">
                                    <input type="hidden" name="judge{{($key + 1).($i + 1)}}" value="{{ $judge->judge_id }}">
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <div class="form-row mt-5">
                        <div class="col d-flex justify-content-center">


                            <div>
                                <label for="toplam">Cəm:</label>
                            </div>
                            <div>
                                <input type="number" id="toplam" class="form-control w-50 mx-auto"
                                       style="min-width: 70px; max-width: 140px" name="toplam" readonly>
                            </div>

                        </div>

                        <div class="col d-flex justify-content-center">

                        </div>
                        <div class="col d-flex justify-content-center">
                            <button type="button" class="btn btn-info hesabla">Hesabla</button>
                        </div>
                        <div class="col d-flex justify-content-center">
                            <input type="hidden" name="id" value="{{ $collective->id }}">
                            <input type="hidden" name="nomination_id" value="{{ $collective->collective_nomination_id }}">
                            <input type="hidden" name="precinct_id" value="{{ $director->precinct_id }}">
                            <button id="tesdiq" type="submit" class="btn btn-info" disabled>Təsdiq et</button>
                        </div>
                        <div class="col">

                        </div>
                        <div class="col d-flex justify-content-center">
                            <div class="mr-3">
                                <label for="gelmeyib"><span class="text-danger">Gəlməyib</span></label>
                            </div>
                            <div>
                                <input id="gelmeyib" type="checkbox" name="gelmeyib">
                            </div>

                        </div>
                    </div>
                    <div class=" mt-5">
                        <div class="textarea-box">
                            <div>
                                <label for="note">Qeyd:</label>
                            </div>
                            <div>
                            <textarea class="mark-textarea" name="note" id="note" cols="" rows="5">

                            </textarea>
                                {{--     <input type="number" id="note" class="form-control w-50 mx-auto"
                                              style="min-width: 70px; max-width: 140px" name="note" >--}}
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            @endrole
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="text-left">
                        @if( $director->is_absent == 0 && $director->score == null )
                            @role('admin')
                            <button id="flip" class="btn btn-secondary no-print">Qiymətləndirmə</button>
                            @endrole
                        @elseif($director->score != null)
                            <button class="btn btn-secondary" disabled>
                                Qiymətləndirmə aparılıb
                            </button>
                        @elseif($director->is_absent != null)
                            <button class="btn btn-secondary" disabled>
                                Müsabiqəyə gəlmədi
                            </button>
                        @endif

                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="text-right">
                        {{--          <button id="download-button" class="btn btn-secondary">
                                      Məlumatları PDF-ə çıxar
                                  </button>--}}
                        <button class="btn btn-secondary no-print" onclick="window.print()">Çap et</button>
                        <!-- Çap düyməsi -->

                    </div>

                </div>
            </div>
            <div id="invoice" class="row">
                <div class="col-sm-12">
                    <h3 class="text-center mt-5 mb-5">Kollektivin məlumatları</h3>
                    <table class="table table-borderless table-sm-responsive">
                        <tr class="setir">
                            <th class="text-left">Kollektivin kodu</th>
                            <td class="text-center"><b>{{ $director->UIN }}</b></td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Kollektivin adı</th>
                            <td class="text-center">{{ $collective->collective_name }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Yarandığı il</th>
                            <td class="text-center">{{ $collective->collective_created_date }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">İştirakçı sayı</th>
                            <td class="text-center">{{ $teenagers_count}}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Yaş kateqoriyası</th>
                            <td class="text-center">{{ $collective->age_category ?? null}}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Müraciət etdiyi nominasiya</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('nominations')->select('name')->where('id',$collective->collective_nomination_id)->first()->name }}</td>
                        </tr>

                        <tr class="setir">
                            <th class="text-left">Müraciət etdiyi şəhər/rayon</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('all_cities')->select('city_name')->where('id',$collective->collective_city_id)->first()->city_name }}</td>

                        </tr>
                        <tr class="setir">
                            <th class="text-left">Faktiki fəaliyyət göstərdiyi şəhər</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('m_n_regions')->select('name')->where('id',$collective->collective_mn_region_id)->first()->name }}</td>

                        </tr>
                        <tr class="setir">
                            <th class="text-left">Təltiflər</th>
                            <td class="text-center">
                                @if( count($awards) > 0)
                                    @foreach( $awards as $key => $award)
                                        {{ ($key + 1)." . ".$award->awards_name }} <br>
                                    @endforeach
                                @else
                                    Yoxdur
                                @endif
                            </td>

                        </tr>
                        <tr class="setir">
                            <th class="text-left">Qeydiyyat tarixi</th>
                            <td class="text-center">{{  \Carbon\Carbon::parse($collective->created_at )->format('d.m.Y H:i:s')}}</td>

                        </tr>

                    </table>
                </div>
                <div class="col-sm-12">
                    <h3 class="text-center mt-5 mb-5">Kollektiv rəhbərinin məlumatları</h3>
                    <table class="table table-borderless table-sm-responsive">
                        <tr class="setir">
                            <th class="text-left">FİN</th>
                            <td class="text-center"><b>{{ $director->director_fin_code }}</b></td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Ad</th>
                            <td class="text-center">{{ $director->director_name }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Soyad</th>
                            <td class="text-center">{{ $director->director_surname }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Ata adı</th>
                            <td class="text-center">{{ $director->director_patronymic }}</td>
                        </tr>

                        <tr class="setir">
                            <th class="text-left">Əlaqə nömrəsi 1</th>
                            <td class="text-center">{{ "0".$director->first_prefix ." ".$director->first_phone_number }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Əlaqə nömrəsi 2</th>
                            <td class="text-center">{{ "0".$director->second_prefix ." ".$director->second_phone_number }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Email</th>
                            <td class="text-center">{{ $director->email}}</td>
                        </tr>

                    </table>
                </div>
                <div class="col-sm-12 mt-5">

                    <h3 class="text-center mt-5 mb-5">Kollektiv üzvlərinin məlumatları</h3>
                    <div class="row ">

                        @foreach( $teenagers as $key => $teenager)

                            <div class="col-sm-6 setir  pb-3 page-break mt-5">
                                <div class="row  mt-3">
                                    <div class="col-sm-3"><h3>№</h3></div>
                                    <div class="col-sm-9 text-center"><h3>{{ $teenager->group_number }}</h3></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Şəkil</b></div>
                                    <div class="col-sm-9 text-center">
                                        <img
                                            style="width: 150px;  height: 185px;object-fit: contain"
                                            src="{{ env('FTP_URL').'/storage/'. $teenager->photo }}"
                                            alt="İştirakçının şəkli"></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>FİN</b></div>
                                    <div class="col-sm-9 text-center"><b>{{ $teenager->fin_code }}</b></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Seçilən nominasiya üzrə xüsusi incəsənət təhsili</b></div>
                                    <div class="col-sm-9 text-center">
                                        @if( $teenager->art_type == 1 && $teenager->art_education != null)
                                            Var, {{ $teenager->art_education }}
                                        @elseif( $teenager->art_type == 2 && $teenager->art_education != null)
                                            Var,  {{ $teenager->art_education }}
                                        @else
                                            Yoxdur
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Ad</b></div>
                                    <div class="col-sm-9 text-center">{{ $teenager->name }}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Soyad</b></div>
                                    <div class="col-sm-9 text-center">{{ $teenager->surname }}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Ata adı</b></div>
                                    <div class="col-sm-9 text-center">{{ $teenager->patronymic }}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Doğum tarixi</b></div>
                                    <div
                                        class="col-sm-9 text-center">{{  \Carbon\Carbon::parse($teenager->birth_date )->format('d.m.Y')}}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Cinsi</b></div>
                                    <div
                                        class="col-sm-9 text-center">{{  $teenager->gender == 'MALE' ? 'Kişi' : 'Qadın' }}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Qeydiyyat ünvanı</b></div>
                                    <div class="col-sm-9 text-center">{{ $teenager->registration_address }}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Faktiki yaşayış ünvanı</b></div>
                                    <div class="col-sm-9 text-center">{{ $teenager->live_address }}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Yaşadığı şəhər/rayon</b></div>
                                    <div
                                        class="col-sm-9 text-center">{{ \Illuminate\Support\Facades\DB::table('m_n_regions')->select('name')->where('id',$teenager->mn_region_id)->first()->name }}</div>
                                </div>

                            </div>

                        @endforeach
                    </div>

                    {{--     <table class="table table-bordered table-sm-responsive">
                             @foreach( $teenagers as $teenager)
                                     <tr class="setir">
                                     <td class="p-0">
                                         <div style="background-color: #212529;width: 100%;height: 2px;">

                                         </div>
                                     </td>
                                     <td class="p-0">
                                         <div style="background-color: #212529;width: 100%;height: 2px;">

                                         </div>
                                     </td>

                                 </tr>
                                     <tr class="setir">
                                     <td class="text-center"><h3>{{ $teenager->group_number }}</h3></td>
                                     <td class="text-center"></td>
                                 </tr>

                                     <tr class="setir">
                                     <th class="text-left">Şəkil</th>
                                     <td class="text-center">
                                         --}}{{--   <img id="my-image" style="width: 150px;  height: 185px;object-fit: contain"
                                                 src="{{ asset('storage/'. $teenager->photo) }}" alt="İştirakçının şəkli">--}}{{--
                                         <img id="my-image" style="width: 150px;  height: 185px;object-fit: contain"
                                              src="{{ env('FTP_URL').'/storage/'. $teenager->photo }}"
                                              alt="İştirakçının şəkli">
                                 </tr>
                                     <tr class="setir">
                                     <th class="text-left">FİN</th>
                                     <td class="text-center"><b>{{ $teenager->fin_code }}</b></td>
                                 </tr>

                                     <tr class="setir">
                                     <th class="text-left">Seçilən nominasiya üzrə xüsusi incəsənət təhsili</th>
                                     <td class="text-center">
                                         @if( $teenager->art_type == 1 && $teenager->art_education != null)
                                             Var, {{ $teenager->art_education }}
                                         @elseif( $teenager->art_type == 2 && $teenager->art_education != null)
                                             Var,  {{ $teenager->art_education }}
                                         @else
                                             Yoxdur
                                         @endif
                                     </td>
                                 </tr>
                                     <tr class="setir">
                                     <th class="text-left">Ad</th>
                                     <td class="text-center">{{ $teenager->name }}</td>
                                 </tr>
                                     <tr class="setir">
                                     <th class="text-left">Soyad</th>
                                     <td class="text-center">{{ $teenager->surname }}</td>
                                 </tr>
                                     <tr class="setir">
                                     <th class="text-left">Ata adı</th>
                                     <td class="text-center">{{ $teenager->patronymic }}</td>
                                 </tr>
                                     <tr class="setir">
                                     <th class="text-left">Doğum tarixi</th>
                                     <td class="text-center">{{  \Carbon\Carbon::parse($teenager->birth_date )->format('d.m.Y')}}</td>
                                 </tr>
                                     <tr class="setir">
                                     <th class="text-left">Cinsi</th>
                                     <td class="text-center">{{  $teenager->gender == 'MALE' ? 'Kişi' : 'Qadın' }}</td>
                                 </tr>
                                     <tr class="setir">
                                     <th class="text-left">Qeydiyyat ünvanı</th>
                                     <td class="text-center">{{ $teenager->registration_address }}</td>
                                 </tr>
                                     <tr class="setir">
                                     <th class="text-left">Faktiki yaşayış ünvanı
                                     </th>
                                     <td class="text-center">{{ $teenager->live_address }}</td>
                                 </tr>
                                     <tr class="setir">
                                     <th class="text-left">Yaşadığı şəhər/rayon</th>
                                     <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('m_n_regions')->select('name')->where('id',$teenager->mn_region_id)->first()->name }}</td>

                                 </tr>



                         </table>--}}
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
            $('.hesabla').on('click', function () {
                let n11 = parseInt($('#n11').val());
                let n12 = parseInt($('#n12').val());
                let n13 = parseInt($('#n13').val());
                let n14 = parseInt($('#n14').val());
                let n15 = parseInt($('#n15').val());

                let n21 = parseInt($('#n21').val());
                let n22 = parseInt($('#n22').val());
                let n23 = parseInt($('#n23').val());
                let n24 = parseInt($('#n24').val());
                let n25 = parseInt($('#n25').val());

                let n31 = parseInt($('#n31').val());
                let n32 = parseInt($('#n32').val());
                let n33 = parseInt($('#n33').val());
                let n34 = parseInt($('#n34').val());
                let n35 = parseInt($('#n35').val());
                let r = (n11 + n12 + n13 + n14 + n15 + n21 + n22 + n23 + n24 + n25 + n31 + n32 + n33 + n34 + n35) / 3;
                r = Math.floor(r);
                $('#toplam').val(r);
                return false;
            });
            $("#qiymetlendirme").click(function () {
                $(".bal").toggle();
            });
            $(".hesabla").click(function () {
                $("#tesdiq").prop('disabled', false);
            });
            $("#gelmeyib").click(function () {
                $("#tesdiq").prop('disabled', false);
            });
        });
    </script>
    <script>
        function digitKeyOnly(e) {
            var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
            var value = Number(e.target.value + e.key) || 0;

            if ((keyCode >= 37 && keyCode <= 40) || (keyCode == 8 || keyCode == 9 || keyCode == 13) || (keyCode >= 48 && keyCode <= 57)) {
                return isValidNumber(value);
            }
            return false;
        }

        function isValidNumber(number) {
            return (1 <= number && number <= 10)
        }
    </script>
    <script>
        $(document).ready(function () {
            $("#flip").click(function () {
                $("#panel").slideToggle();
            });
        });
    </script>
    <!-- html2pdf CDN link -->
    {{--    <script src="{{ asset('backend/assets/myCustom/js/html2pdf.bundle.min.js') }}"></script>
        <script>
            const button = document.getElementById('download-button');

            function generatePDF() {
                // Choose the element that your content will be rendered to.
                const element = document.getElementById('invoice');
                var opt = {
                    margin: 1,
                    filename: 'kollektiv.pdf',
                    html2canvas: {scale: 1},
                    jsPDF: {unit: 'in', format: 'a4', orientation: 'portrait'}
                };

                // New Promise-based usage:
                html2pdf().set(opt).from(element).save();
            }

            button.addEventListener('click', generatePDF);
        </script>--}}

@endpush
@push('customCss')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css') }}">
    <style>
        th {
            width: 35%;
        }

        @page {
            margin: 0; /* Header və footer-i silir */
        }

        @media print {
            body {
                margin: 20px; /* Çap zamanı kənarlarda kiçik boşluq saxlayır */
            }

            .page-break {
                page-break-before: always; /* Yeni səhifədən başlasın */
            }

            .no-print {
                display: none; /* Çap zamanı gizlənsin */
            }
        }

        .setir {
            border: 10px solid #ffffff;
            background-color: rgba(0, 0, 0, .05);

        }

        .mark-textarea{
            width: 100%;
        }

        .textarea-box {
            padding: 0 50px;
        }

        #panel {
            display: none;
        }

        #flip {
            cursor: pointer;
        }

        #panel, #flip {
            border: solid 1px #c3c3c3;
        }
    </style>
@endpush
