@extends('backend.layouts.app')
@section('title','Şəhər üzrə say')
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
        <div  id="invoice" class="container-fluid">
            <div class="col-sm-12 border-right">
                <h2 class="text-center mt-5 mb-5"> Şəhər üzrə say</h2>
                <table class="table table-bordered bg-info">
                    <thead>
                    <tr>
                        <th class="text-center">№</th>
                        <th class="text-center">Şəhər adı</th>
                        <th class="text-center">Fərdi iştirakçılar üzrə say</th>
                        <th class="text-center">Kollektiv üzrə say</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach( $city_count as $key => $cc)
                        <tr>
                            <td class="text-center"><h5>{{ $key + 1 }}</h5></td>
                            <td class="text-center"><h5>{{ $cc[2] }}</h5></td>
                            <td class="text-center"><h4>{{ $cc[0] }}</h4></td>
                            <td class="text-center"><h4>{{ $cc[1] }}</h4></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
                filename:     'şəhər-üzrə-statistika.pdf',
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








