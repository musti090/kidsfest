@extends('backend.layouts.app')
@section('title','İştirakçının bütün məlumatları')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.personal.users.list') }}">Bütün iştirakçılar</a></li>
@endsection
@section('content')
    <section class="content">
        @if($errors->any())
            @foreach($errors->all() as $error)
                <h2 class="text-center text-danger">{{ $error }}</h2>
            @endforeach
        @endif

        <div class="container-fluid">
          <div class="row">
                <div class="col-12">
                    <div class="text-right">
                   {{--     <button id="download-button" class="btn btn-secondary">
                            Məlumatları PDF-ə çıxar
                        </button>
                        <button onclick="printContent()">Çap et</button>--}}
                        <button class="btn btn-secondary no-print" onclick="window.print()">Çap et</button> <!-- Çap düyməsi -->

                    </div>

                </div>
            </div>
            <div id="invoice" class="row">
                <div class="col-sm-12">
                    <h3 class="text-center mt-2 mb-4">İştirakçının məlumatları</h3>
                    <table class="table table-borderless table-sm-responsive">
                        <tr class="setir">
                            <th class="text-left">Şəkil</th>
                            <td class="text-center">
                               {{-- <img id="my-image" style="width: 150px;  height: 185px;object-fit: contain"
                                     src="{{ asset('storage/'. $data->photo) }}" alt="İştirakçının şəkli">--}}
                            <img id="my-image" style="width: 150px;  height: 185px;object-fit: contain"
                            src="{{env('FTP_URL').'/storage/'. $data->photo}}" alt="İştirakçının şəkli">
                            {{--
                                                                <div style="width: 150px;  height: 185px; background-image: url({{ env('FTP_URL').'/storage/'. $data->photo }})"></div></td>
                            --}}
                        </tr>
                        <tr class="setir">
                            <th class="text-left">İştirakçı kodu</th>
                            <td class="text-center"><b>{{ $data->UIN }}</b></td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">FİN</th>
                            <td class="text-center"><b>{{ $data->fin_code }}</b></td>
                        </tr>
                        <tr  class="setir">
                            <th class="text-left">Müraciət etdiyi nominasiya</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('nominations')->select('name')->where('id',$data->nomination_id)->first()->name }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Seçilən nominasiya üzrə xüsusi incəsənət təhsili</th>
                            <td class="text-center">
                                @if( $data->art_type == 1 && $data->art_education != null)
                                    Var, {{ $data->art_education }}
                                @elseif( $data->art_type == 2 && $data->art_education != null)
                                    Var,  {{ $data->art_education }}
                                @else
                                    Yoxdur
                                @endif
                            </td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Ad</th>
                            <td class="text-center">{{ $data->name }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Soyad</th>
                            <td class="text-center">{{ $data->surname }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Ata adı</th>
                            <td class="text-center">{{ $data->patronymic }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Doğum tarixi</th>
                            <td class="text-center">{{  \Carbon\Carbon::parse($data->birth_date )->format('d.m.Y')}}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Yaş kateqoriyası</th>
                            <td class="text-center">{{ $data->age_category ?? null }}</td>
                        </tr>

                        <tr class="setir">
                            <th class="text-left">Cinsi</th>
                            <td class="text-center">{{  $data->gender == 'MALE' ? 'Kişi' : 'Qadın' }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Qeydiyyat ünvanı</th>
                            <td class="text-center">{{ $data->registration_address }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Faktiki yaşayış ünvanı
                            </th>
                            <td class="text-center">{{ $data->live_address }}</td>
                        </tr>
                        <tr class="setir">
                            <th class="text-left">Yaşadığı şəhər/rayon</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('m_n_regions')->select('name')->where('id',$data->mn_region_id)->first()->name }}</td>

                        </tr>
                        <tr class="setir">
                            <th class="text-left">Müraciət etdiyi şəhər/rayon</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('all_cities')->select('city_name')->where('id',$data->all_city_id)->first()->city_name }}</td>

                        </tr>
                            <tr class="setir">
                            <th class="text-left">Təhsil  müəssisəsinin növü</th>
                            <td class="text-center">{{ \Illuminate\Support\Facades\DB::table('education_schools')->select('school_type')->where('id',$data->school_type_id)->first()->school_type ?? null }}</td>

                        </tr>
                            <tr class="setir">
                            <th class="text-left">Təhsil  müəssisəsin adı</th>
                            <td class="text-center">
                              @if($data->created_at < '2025-03-15 01:28:00')
                                {{ \Illuminate\Support\Facades\DB::table('education_school_names')->select('name')->where('id',$data->school_id)->first()->name ?? null }}
                                  @else
                                    {{ \Illuminate\Support\Facades\DB::table('education_school_new_names')->select('name')->where('id',$data->school_id)->first()->name ?? null }}
                                @endif
                            </td>

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
                            <td class="text-center">{{  \Carbon\Carbon::parse($data->created_at )->format('d.m.Y H:i:s')}}</td>

                        </tr>
                    </table>
                </div>
                <div class="col-sm-12 page-break mb-5">
                    <h3 class="text-center mt-4 mb-4">Valideyn/Qanuni nümayəndə məlumatları</h3>
                    <table class="table table-borderless table-sm-responsive">
                            <tr class="setir">
                            <th class="text-left">FİN</th>
                            <td class="text-center"><b>{{ $data->parent_fin_code }}</b></td>
                        </tr>
                            <tr class="setir">
                            <th class="text-left">Ad</th>
                            <td class="text-center">{{ $data->parent_name }}</td>
                        </tr>
                            <tr class="setir">
                            <th class="text-left">Soyad</th>
                            <td class="text-center">{{ $data->parent_surname }}</td>
                        </tr>
                            <tr class="setir">
                            <th class="text-left">Ata adı</th>
                            <td class="text-center">{{ $data->parent_patronymic }}</td>
                        </tr>
                            <tr class="setir">
                            <th class="text-left">Əlaqə nömrəsi 1</th>
                            <td class="text-center">{{ "0".$data->first_prefix ." ".$data->first_phone_number }}</td>
                        </tr>
                            <tr class="setir">
                            <th class="text-left">Əlaqə nömrəsi 2</th>
                            <td class="text-center">{{ "0".$data->second_prefix ." ".$data->second_phone_number }}</td>
                        </tr>
                            <tr class="setir">
                            <th class="text-left">Email</th>
                            <td class="text-center">{{ $data->email}}</td>
                        </tr>
                    </table>
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
    <script src="{{ asset('backend/assets/myCustom/js/html2pdf.bundle.min.js') }}"></script>
    <script>

        let button = document.getElementById('download-button');

        function generatePDF() {
            // Choose the element that your content will be rendered to.

            var element = document.getElementById('invoice');
            var opt = {
                margin:       1,
                filename:     'myfile.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();
        }

        button.addEventListener('click', generatePDF);
    </script>
{{--    <script>
        function printContent() {
            printJS({
                printable: 'printArea',  // Çap ediləcək elementin ID-si
                type: 'html',            // Çap növü (html, pdf, image, json və s.)
                scanStyles: true,
                showModal: true,         // Çap dialoqu yüklənənə qədər yükləmə pəncərəsi göstər
                modalMessage: "Səhifə çap üçün hazırlanır...", // Modal mesaj
                documentTitle: "Çap Sənədi", // Başlıq
                onPrintDialogClose: () => alert("Çap tamamlandı!") // Çap dialoqu bağlananda işləyən funksiya
            });
        }
    </script>--}}
@endpush
@push('customCss')
{{--    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">--}}


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
    </style>
@endpush
