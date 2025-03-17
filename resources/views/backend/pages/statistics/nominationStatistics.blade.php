@extends('backend.layouts.app')
@section('title','Şəhər/Rayon üzrə say')
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
            <div class="col-sm-12 border-left">
                <h3 class="text-center mt-5 mb-5">Nominasiyalar üzrə say</h3>
                <table class="table table-bordered bg-info">
                    <thead>
                    <tr>
                        <th class="text-center"><h5>№</h5></th>
                        <th class="text-center" ><h5>Nominasiyanın adı</h5></th>
                        <th class="text-center">Müraciət edənlərin sayı</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $k = 1; @endphp <!-- Initialize $k -->

                    @foreach($nomCount as $key => $nom)
                        <tr>
                            <td class="text-center">{{ $k++ }}</td> <!-- Display $k and increment it -->
                            <td class="text-center">{{ $nom[1] }}</td>
                            <td class="text-center"><h3>{{ $nom[0] }}</h3></td>
                        </tr>
                    @endforeach

                    @foreach($colnomCount as $key => $nom)
                        <tr>
                            <td class="text-center">{{ $k++ }}</td> <!-- Continue incrementing $k -->
                            <td class="text-center">{{ $nom[1] }}</td>
                            <td class="text-center"><h3>{{ $nom[0] }}</h3></td>
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
                filename:     'nominasiyalar-üzrə-statistika.pdf',
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








