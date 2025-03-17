<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rayon və nominasiya üzrə statistika</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /*  #invoice {
              font-family: Arial, Helvetica, sans-serif;
              border-collapse: collapse;
              width: 100%;
          }

          #invoice td, #customers th {
              border: 1px solid #ddd;
          }

          !* #invoice tr{background-color: #f2f2f2;}*!

          #invoice tr:hover {
              background-color: #17a2b8;
          }

          #invoice th {
              background-color: #17a2b8;
              color: white;
              border: 1px solid white;
          }
  */
        #navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="gel d-flex align-items-center justify-content-center" style="height: 100vh;font-size: 20px">
    <button class="btn btn-info p-3">
        <span class="spinner-grow spinner-grow-sm"></span>
        Məlumatlar yüklənir..
    </button>
</div>
<div class="container-fluid wrapper">
    <div class="row mt-3">
        <div id="navbar" class="col-sm-12 p-3">
            <div class="row">
                <div class="col-sm-6">
                    <a class="yuklenir btn btn-info" href="{{ route('backend.dashboard.index') }}">
                        Əsas səhifəyə qayıt
                    </a>
                </div>
                <div class="col-sm-6">
                    <button id="download-button" class="btn btn-info float-right">
                        Məlumatları PDF-ə çıxar
                    </button>
                </div>
            </div>
        </div>
        <div class="col-sm-12 mt-3 mb-3">
            <table id="invoice" style="background-color: #f2f2f2" class="table table-bordered table-responsive">
                <tr>
                    <th class="align-middle text-center" rowspan="2">№</th>
                    <th class="align-middle text-center" rowspan="2">Rayon,Şəhər</th>
                    <th class="align-middle text-center" colspan="26">Nominasiyalar</th>
                </tr>

                <tr>
                    <th class="align-middle text-center" colspan="20">Fərdi</th>
                    <th class="align-middle text-center" colspan="6">Kollektiv</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    @foreach($personalNominations as $pn)
                        <th class="align-middle text-center pl-1 pr-1">{{ substr($pn->name,0,96)}}</th>
                    @endforeach
                    <th class="align-middle text-center pl-1 pr-1 text-white bg-secondary">Fərdi <br> Cəm</th>
                    @foreach($collectiveNominations as $cn)
                        <th class="align-middle text-center">{{ substr($cn->name,0,96)}}</th>
                    @endforeach
                    <th class="align-middle text-center pl-1 pr-1 text-white bg-secondary">Kollektiv <br> Cəm</th>
                </tr>

                @foreach( $regions as $key => $r)
                    @php
                        $pernomCount = 0;
                        $colnomCount = 0;
                    @endphp
                    <tr>
                        <th class="align-middle text-center">{{  $key + 1 }}</th>
                        <th class="align-middle text-center">{{  $r->name }}</th>
                        @foreach($personalNominations as $p => $nom)
                            <td class="align-middle  text-center">{{  $x = \Illuminate\Support\Facades\DB::table('personal_users')->where('mn_region_id', $r->id)->where('nomination_id', $nom->id)->count()  }} </td>
                            @php
                                $pernomCount = $pernomCount + $x;
                            @endphp
                        @endforeach
                        <td class="align-middle text-center text-white bg-secondary">{{ $pernomCount }}</td>
                        @foreach($collectiveNominations as $col_nom)
                            <td class="align-middle  text-center">{{ $y = \Illuminate\Support\Facades\DB::table('collectives')->where('collective_nomination_id', $col_nom->id)->where('collective_mn_region_id', $r->id)->count() }}</td>
                            @php
                                $colnomCount = $colnomCount + $y;
                            @endphp
                        @endforeach
                        <td class="align-middle text-center text-white bg-secondary">{{ $colnomCount }}</td>
                    </tr>

                @endforeach
            </table>

        </div>
   {{--     <div class="ml-3">
      {{ $regions->links('vendor.pagination.custom') }}
        </div>--}}
    </div>

</div>
</div>
<!-- html2pdf CDN link -->
<script src="{{ asset('backend/assets/myCustom/js/html2pdf.bundle.min.js') }}"></script>
<script>
    const button = document.getElementById('download-button');

    function generatePDF() {
        // Choose the element that your content will be rendered to.
        const element = document.getElementById('invoice');
        var opt = {
            margin: 2,
            filename: 'nominasiya-rayon.pdf',
            html2canvas: {scale: 3},
            jsPDF: {unit: 'in', format: 'a1', orientation: 'l'},
        };

        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();
    }

    button.addEventListener('click', generatePDF);
</script>
<script>
    $(document).ready(function () {
        $(".gel").removeClass('d-flex');
        $(".gel").addClass('d-none');
        $(".yuklenir").click(function () {
            $(".wrapper").hide();
            $(".gel").removeClass('d-none');
            $(".gel").addClass('d-flex');
        });
    });
</script>
</body>
</html>
