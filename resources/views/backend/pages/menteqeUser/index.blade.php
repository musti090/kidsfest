<!DOCTYPE html>
<html lang="en">
<head>
    <title>Şəhər və nominasiya üzrə statistika</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
{{--    <style>
        #invoice {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #invoice td, #customers th {
            border: 1px solid #ddd;
        }

        /* #invoice tr{background-color: #f2f2f2;}*/

        #invoice tr:hover {
            background-color: #17a2b8;
        }

        #invoice th {
            background-color: #17a2b8;
            color: white;
            border: 1px solid white;
        }
    </style>--}}
</head>
<body>

<div class="container-fluid wrapper">
    <div class="row mt-3">
        <div class="col-sm-6">
            <a class="yuklenir btn btn-info" href="{{ route('backend.dashboard.index') }}">
                Əsas səhifəyə qayıt
            </a>
        </div>
        <div class="col-sm-6 text-right">
            <button id="download-button" class="btn btn-info">
                Məlumatları PDF-ə çıxar
            </button>
        </div>
        <div class="col-sm-12 mt-3 mb-3">
            <table id="invoice" class="table table-bordered">
                <tr class="bg-dark text-white">
                    <th  class="align-middle text-center">Nomre</th>
                    <th style="width: 45%" class="align-middle text-center">Məntəqənin adı</th>
                    <th class="align-middle text-center">User adı</th>
                    <th class="align-middle text-center">Parol</th>
                </tr>
                @foreach($data as $key => $d)
                    <tr>
                        <td class="align-middle text-center">{{ $key + 1 }}</td>
                        <td class="align-middle text-center">{{ \App\Models\Precinct::where('id',$d->precinct_id)->first()->place_name }}</td>
                        <td class="align-middle text-center">{{ $d->username }}</td>
                        <td class="align-middle text-center">{{ $d->parol }}</td>
                    </tr>
                @endforeach
            </table>

        </div>

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
            margin: 1,
            filename: 'users.pdf',
            html2canvas: {scale: 2},
            jsPDF:        { unit: 'in', format: [22,16], orientation: 'portrait' }
        };

        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();
    }

    button.addEventListener('click', generatePDF);
</script>
</body>
</html>
