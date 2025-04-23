<!DOCTYPE html>
<html lang="en">
<head>
    <title>Statistika</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        th {
            width: 35%;
        }

        @page {
            margin: 0; /* Header və footer-i silir */
        }

        .mark-textarea{
            width: 100%;
        }

        .textarea-box{
            padding: 0 50px;
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
</head>
<body>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-right">
                    <button class="btn btn-secondary no-print" onclick="window.print()">Çap et</button>
                </div>
            </div>
        </div>
    </div>
    <div  id="invoice" class="container-fluid">
        <div class="col-sm-12 border-right">
            <h2 class="text-center mt-5 mb-5">Rayon üzrə say</h2>
            <h2 class="text-center mt-5 mb-5">Tarix: {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</h2>
            <table class="table table-bordered bg-info">
                <thead>
                <tr>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                    <th class="text-center" colspan="4">Fərdi iştirakçılar üzrə say</th>
                    <th class="text-center" colspan="4">Kollektiv üzrə say</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th class="text-center">№</th>
                    <th class="text-center">Rayon adı</th>
                    <th class="text-center">Ümumi</th>
                    <th class="text-center">İştirak edənlər</th>
                    <th class="text-center">Gəlməyənlər</th>
                    <th class="text-center">Sistemə daxil edilməyənlər</th>
                    <th class="text-center">Ümumi</th>
                    <th class="text-center">İştirak edənlər</th>
                    <th class="text-center">Gəlməyənlər</th>
                    <th class="text-center">Sistemə daxil edilməyənlər</th>
                </tr>
                @foreach( $district_count as $key => $dc)
                    <tr>
                        <td class="text-center"><h5>{{ $key + 1 }}</h5></td>
                        <td class="text-center"><h5>{{ $dc[8] }}</h5></td>
                        <td class="text-center"><h4>{{ $dc[0] }}</h4></td>
                        <td class="text-center"><h4>{{ $dc[1] }}</h4></td>
                        <td class="text-center"><h4>{{ $dc[2] }}</h4></td>
                        <td class="text-center"><h4>{{ $dc[3] }}</h4></td>
                        <td class="text-center"><h4>{{ $dc[4] }}</h4></td>
                        <td class="text-center"><h4>{{ $dc[5] }}</h4></td>
                        <td class="text-center"><h4>{{ $dc[6] }}</h4></td>
                        <td class="text-center"><h4>{{ $dc[7] }}</h4></td>
                    </tr>
                @endforeach


                </tbody>
            </table>
        </div>

    </div>
</section>
</body>













