@extends('backend.layouts.app')
@section('title','Kollektivin bütün məlumatları')
@section('content')
    <section class="content">
        @if($errors->any())
            @foreach($errors->all() as $error)
                <h2 class="text-center text-danger">{{ $error }}</h2>
            @endforeach
        @endif

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-right">
              {{--          <button id="download-button" class="btn btn-secondary">
                            Məlumatları PDF-ə çıxar
                        </button>--}}
                        <button class="btn btn-secondary no-print" onclick="window.print()">Çap et</button> <!-- Çap düyməsi -->

                    </div>

                </div>
            </div>
            <div id="invoice" class="row">
                <div class="col-sm-12">
                    <h3 class="text-center mt-5 mb-5">Kollektivin məlumatları</h3>
                    <table class="table table-borderless table-sm-responsive">
                        <tr>
                            <th class="text-left">Kollektivin kodu</th>
                            <td class="text-center"><b>{{ $director->UIN }}</b></td>
                        </tr>
                        <tr>
                            <th class="text-left">Kollektivin adı</th>
                            <td class="text-center">{{ $collective->collective_name }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Yarandığı il</th>
                            <td class="text-center">{{ $collective->collective_created_date }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">İştirakçı sayı</th>
                            <td class="text-center">{{ $teenagers_count}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Müraciət etdiyi nominasiya</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('nominations')->select('name')->where('id',$collective->collective_nomination_id)->first()->name }}</td>
                        </tr>

                        <tr>
                            <th class="text-left">Müraciət etdiyi şəhər/rayon</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('all_cities')->select('city_name')->where('id',$collective->collective_city_id)->first()->city_name }}</td>

                        </tr>
                        <tr>
                            <th class="text-left">Faktiki fəaliyyət göstərdiyi şəhər</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('m_n_regions')->select('name')->where('id',$collective->collective_mn_region_id)->first()->name }}</td>

                        </tr>
                        <tr>
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
                        <tr>
                            <th class="text-left">Qeydiyyat tarixi</th>
                            <td class="text-center">{{  \Carbon\Carbon::parse($collective->created_at )->format('d.m.Y H:i:s')}}</td>

                        </tr>

                    </table>
                </div>
                <div class="col-sm-12">
                    <h3 class="text-center mt-5 mb-5">Kollektiv rəhbərinin məlumatları</h3>
                    <table class="table table-borderless table-sm-responsive">
                        <tr>
                            <th class="text-left">FİN</th>
                            <td class="text-center"><b>{{ $director->director_fin_code }}</b></td>
                        </tr>
                        <tr>
                            <th class="text-left">Ad</th>
                            <td class="text-center">{{ $director->director_name }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Soyad</th>
                            <td class="text-center">{{ $director->director_surname }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Ata adı</th>
                            <td class="text-center">{{ $director->director_patronymic }}</td>
                        </tr>

                        <tr>
                            <th class="text-left">Birinci telefon nömrəsi</th>
                            <td class="text-center">{{ "0".$director->first_prefix ." ".$director->first_phone_number }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">İkinci telefon nömrəsi</th>
                            <td class="text-center">{{ "0".$director->second_prefix ." ".$director->second_phone_number }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Email</th>
                            <td class="text-center">{{ $director->email}}</td>
                        </tr>

                    </table>
                </div>
                <div class="col-sm-12 mt-5">

                    <h3 class="text-center mt-5 mb-5">Kollektiv üzvlərinin məlumatları</h3>
                    <div class="row ">

                        @foreach( $teenagers as $key => $teenager)

                            <div class="col-sm-6  pb-3 page-break mt-5">
                                <div class="row mt-3">
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
                                    <div class="col-sm-9 text-center">{{  \Carbon\Carbon::parse($teenager->birth_date )->format('d.m.Y')}}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3"><b>Cinsi</b></div>
                                    <div class="col-sm-9 text-center">{{  $teenager->gender == 'MALE' ? 'Kişi' : 'Qadın' }}</div>
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
                                    <div class="col-sm-9 text-center">{{ \Illuminate\Support\Facades\DB::table('m_n_regions')->select('name')->where('id',$teenager->mn_region_id)->first()->name }}</div>
                                </div>

                            </div>

                        @endforeach
                    </div>

                    {{--     <table class="table table-bordered table-sm-responsive">
                             @foreach( $teenagers as $teenager)
                                 <tr>
                                     <td class="p-0">
                                         <div style="background-color: #212529;width: 100%;height: 2px;">

                                         </div>
                                     </td>
                                     <td class="p-0">
                                         <div style="background-color: #212529;width: 100%;height: 2px;">

                                         </div>
                                     </td>

                                 </tr>
                                 <tr>
                                     <td class="text-center"><h3>{{ $teenager->group_number }}</h3></td>
                                     <td class="text-center"></td>
                                 </tr>

                                 <tr>
                                     <th class="text-left">Şəkil</th>
                                     <td class="text-center">
                                         --}}{{--   <img id="my-image" style="width: 150px;  height: 185px;object-fit: contain"
                                                 src="{{ asset('storage/'. $teenager->photo) }}" alt="İştirakçının şəkli">--}}{{--
                                         <img id="my-image" style="width: 150px;  height: 185px;object-fit: contain"
                                              src="{{ env('FTP_URL').'/storage/'. $teenager->photo }}"
                                              alt="İştirakçının şəkli">
                                 </tr>
                                 <tr>
                                     <th class="text-left">FİN</th>
                                     <td class="text-center"><b>{{ $teenager->fin_code }}</b></td>
                                 </tr>

                                 <tr>
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
                                 <tr>
                                     <th class="text-left">Ad</th>
                                     <td class="text-center">{{ $teenager->name }}</td>
                                 </tr>
                                 <tr>
                                     <th class="text-left">Soyad</th>
                                     <td class="text-center">{{ $teenager->surname }}</td>
                                 </tr>
                                 <tr>
                                     <th class="text-left">Ata adı</th>
                                     <td class="text-center">{{ $teenager->patronymic }}</td>
                                 </tr>
                                 <tr>
                                     <th class="text-left">Doğum tarixi</th>
                                     <td class="text-center">{{  \Carbon\Carbon::parse($teenager->birth_date )->format('d.m.Y')}}</td>
                                 </tr>
                                 <tr>
                                     <th class="text-left">Cinsi</th>
                                     <td class="text-center">{{  $teenager->gender == 'MALE' ? 'Kişi' : 'Qadın' }}</td>
                                 </tr>
                                 <tr>
                                     <th class="text-left">Qeydiyyat ünvanı</th>
                                     <td class="text-center">{{ $teenager->registration_address }}</td>
                                 </tr>
                                 <tr>
                                     <th class="text-left">Faktiki yaşayış ünvanı
                                     </th>
                                     <td class="text-center">{{ $teenager->live_address }}</td>
                                 </tr>
                                 <tr>
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
    </style>
@endpush
