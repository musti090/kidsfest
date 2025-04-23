<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siyahı</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <style>
        * {
            margin-block-start: 0em;
            margin-block-end: 0em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-family: 'Inter';
        }

        body {
            width: 1440px;
            margin: auto;
        }

        span {
            color: #000;
        }

        .head {
            color: #8B8A6D;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 50px;
        }

        .line {
            height: 3px;
            width: 400px;
            background-color: #8B8A6D;
        }

        .flex {
            display: flex;
        }

        .between {
            justify-content: space-between;
            align-items: center;
        }

        .head div {
            margin: 15px 0;
        }

        .logos {
            display: flex;
            gap: 40px;
        }

        .logos img {
            height: 60px;
        }

        .logo img {
            width: 160px;
        }

        tr {
            border: 1px solid #8B8A6D;
        }

        td {
            border: 1px solid #8B8A6D;
        }

        th {
            border: 1px solid #8B8A6D;
        }

        table {
            border: 1px solid #8B8A6D;
            border-spacing: 0px;
            width: 99%;
            text-align: center;
            border-radius: 12px;
            overflow: hidden;
            font-weight: 600;
        }

        table * {
            padding: 10px 0px;
        }

        thead tr th {
            background-color: #8B8A6D;
            color: #fff;
            border-right: 1px solid #fff;
            border-left: 1px solid #fff;
        }

        thead tr th:first-child {
            border-left: 0px solid #fff;
        }

        thead tr th:last-child {
            border-right: 0px solid #fff;
        }

        .signs {
            margin-top: 40px;
            margin-bottom: 40px;
            font-size: 18px;
            color: #8B8A6D;
            font-weight: 600;
        }

        .logo p {
            font-size: 15px;
            transform: translate(15%, -40%);
        }

        .button {
            background-color: #8B8A6D;
            border: none;
            color: white;
            padding: 10px 30px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 10px;
        }

        .button-div {
            margin-top: 20px;
            margin-bottom: 40px;
        }

        @page {
            margin: 0; /* Header və footer-i silir */
        }

        @media print {
            body {
                padding: 0 40px;
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

<div class="button-div">
    <a href="{{ route('backend.judges') }}" class="button no-print">
        Əsas səhifəyə dön
    </a>
    <button class="button no-print" onclick="window.print()">Çap et</button>
</div>

<main id="invoice">

    <div class="head">

        <h1 style="text-align: center"> Qiymətləndirmə cədvəli</h1>
        <div class="flex between">
            <div class="flex">
                <p>Münsif:&nbsp; </p><span> {{ $judge_full_name }}</span>
            </div>
            <div class="logos">
                <img src="{{ asset('backend/images/mn_logo.15bef67872586bc95d75.png') }}" alt="mn_logo">
                <img src="{{ asset('backend/images/heydar_aliyev_center_logo.svg') }}" alt="hac_logo">
                <img src="{{ asset('backend/images/education_ministry_logo.svg') }}" alt="em_logo">
            </div>
        </div>

        <div class="flex between">
            <div>
                <div class="flex">
                    <p>Nominasiya:&nbsp; </p><span
                        @if($nomination->id == 18) style="width: 35%;display: inline-block" @endif> {{ $nomination->name }}</span>
                </div>
                <div class="flex">
                    <p>Tarix/Saat: &nbsp; </p>
                    <span>{{ \Carbon\Carbon::parse($date)->format('d.m.Y')." ".$time  }}</span>
                </div>
                <div class="flex">
                    <p>Məkan:&nbsp; </p><span> {{ $precinct_name }}</span>
                </div>
                <div class="flex">
                    <p style="font-size: 16px">*Qiymətləndirmədə hər kriteriya üzrə 1-10 bal arası verilməlidir: minimum
                        - 1 bal, maksimum - 10 bal</p></span>
                </div>

                <div class="flex">
                    <p style="font-size: 16px">*P - peşəkar iştirakçı, H - həvəskar iştirakçı</p></span>
                </div>

            </div>
            <div class="logo flex">
                <img src="{{ asset('backend/images/kids_art_fest_logo_3.3b433ba95b8d09df14e5.png') }}" alt="kaf_logo">
            </div>
        </div>
    </div>
    <table>
        <thead>
        <tr>
            <th style="width: 5%">No</th>
            @if($nomination->id < 20)
                <th style="width: 18%">İŞTİRAKÇININ ADI</th>
            @else
                <th style="width: 18%">KOLLEKTİVİN ADI</th>
                {{--<th style="width: 18%">KOLLEKTİV RƏHBƏRİNİN  ADI</th>--}}
            @endif
            <th style="width: 10%">KODU</th>
            @if($nomination->id < 20)
                <th style="width: 10%">P/H</th>
            @endif
            <th colspan="5">KRİTERİYALAR ÜZRƏ VERİLƏN BALLAR</th>
            <th>QEYD</th>
        </tr>
        </thead>
        <tbody>
        <tr style="color: #8B8A6D;">
            <td></td>
            <td></td>
            <td></td>
            @if($nomination->id < 20)
                <td></td>
            @endif
            @foreach($nomination->nomination_has_criterion as $nc)
                <td style="width: 10%;text-transform: uppercase" lang="az">{{ $nc->name }}</td>
            @endforeach
        </tr>
        @foreach($data as $k => $d)
            <tr>
                <td>{{ $k + 1 }}</td>
                @if($nomination->id < 20)
                    <td>


                        {{ $d->personal_user_form_information->name." ".$d->personal_user_form_information->surname }}

                    </td>
                @else
                    <td>
                        @if($d->collective_information->collective_name  != null)
                            {{--    {{ $d->parent_card_name." ".$d->parent_card_surname }}--}}
                            {{ $d->collective_information->collective_name }}
                        @endif
                    </td>
                @endif
                <td><b>{{ $d->UIN }}</b></td>
                @php
                    if($nomination->id < 20){
                     $art_type = $d->personal_user_form_information->art_type;
                     $art_education = $d->personal_user_form_information->art_education;
                   }
                @endphp
                @if($nomination->id < 20)
                    <td>
                        @if($art_type == 1)
                            P
                      @elseif($art_type == 2 && $art_education != null)
                            P
                        @else
                            H
                        @endif
                    </td>
                @endif
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach

        </tbody>
    </table>
    <div class="flex between signs">
        <div>
            <p>Verilən balları imzamla təsdiqləyirəm.</p>
        </div>
        <div class="flex" style="gap: 20px;">
            <p>Tarix:</p><span>______________</span>
            <p>Ad, Soyad:</p><span>___________________________________________</span>
            <p>İmza:</p><span>____________</span>
        </div>
    </div>
</main>
<!-- html2pdf CDN link -->
<script src="{{ asset('backend/assets/myCustom/js/html2pdf.bundle.min.js') }}"></script>
<script>
    const button = document.getElementById('download-button');

    function generatePDF() {
        // Choose the element that your content will be rendered to.
        const element = document.getElementById('invoice');
        var opt = {
            margin: 1,
            filename: 'siyahi.pdf',
            html2canvas: {scale: 2},
            jsPDF: {unit: 'in', format: [22, 16], orientation: 'portrait'}
        };

        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();
    }

    button.addEventListener('click', generatePDF);
</script>
</body>

</html>



