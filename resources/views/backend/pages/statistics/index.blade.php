@extends('backend.layouts.app')
@section('title','Əsas səhifə')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-right">
                        <button id="download-button" class="btn btn-info">
                            Məlumatları PDF-ə çıxar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="invoice" class="container-fluid">
            @role('developer|superadmin|content manager')
            <h2 class="text-center">İkinci Uşaq İncəsənət Festivalının qeydiyyat statistikası</h2>
            @endrole
            @role('admin')
            <h2 class="text-center">{{ \Illuminate\Support\Facades\DB::table('precincts')->where('id',$user_precinct)->first()->place_name }} qeydiyyat statistikası</h2>
            @endrole
            <div class="row text-center mt-5">
                <div class="col-lg-3 col-6 mb-3">
                    <div class="small-box bg-info mf">
                        <div class="inner">
                            <h3>{{ $personal_users }}</h3>
                            <p>Fərdi iştirakçı sayı</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info mf">
                        <div class="inner">
                            <h3>{{ $personalMales}}</h3>
                            <p>Fərdi kişilərin sayı</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info mf">
                        <div class="inner">
                            <h3>{{ $personalFemales }}</h3>
                            <p>Fərdi qadınların sayı</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6" >
                    <div class="small-box bg-info mf">
                        <div class="inner">
                            <h3>{{ $all }}</h3>
                            <p> Qeydiyyatdan keçənlərin ümumi sayı
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 mb-3">
                    <div class="small-box bg-info mf">
                        <div class="inner">
                            <h3>{{ $collectives }}</h3>
                            <p>Kollektiv sayı</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info mf">
                        <div class="inner">
                            <h3>{{ $collective_users }}</h3>
                            <p>Kollektiv iştirakçı sayı</p>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info mf">
                        <div class="inner">
                            <h3>{{ $collectiveMales}}</h3>
                            <p>Kollektiv kişilərin sayı</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info mf">
                        <div class="inner">
                            <h3>{{ $collectiveFemales }}</h3>
                            <p>Kollektiv qadınların sayı</p>
                        </div>
                    </div>
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
                margin:   1,
                filename:     'ümumi-statistika.pdf',
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
            };

            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();
        }

        button.addEventListener('click', generatePDF);
    </script>
@endpush
@push('customCss')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css') }}">
    <style>
        .mf {
            height: 138px;
        }
    </style>
@endpush
