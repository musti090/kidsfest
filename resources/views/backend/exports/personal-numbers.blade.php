<table>
    <thead>
    <tr>
        <th style="font-weight: bold;text-align: center">№</th>
        <th style="font-weight: bold;text-align: center">Kod</th>
        <th style="font-weight: bold;text-align: center">FİN</th>
        <th style="font-weight: bold;text-align: center">Ad</th>
        <th style="font-weight: bold;text-align: center">Soyad</th>
        <th style="font-weight: bold;text-align: center">Ata adı</th>
        <th style="font-weight: bold;text-align: center">Valideynin adı</th>
        <th style="font-weight: bold;text-align: center">Valideynin soyadı</th>
        <th style="font-weight: bold;text-align: center">Valideynin ata adı</th>
        <th style="font-weight: bold;text-align: center">Əlaqə nömrəsi 1</th>
        <th style="font-weight: bold;text-align: center">Əlaqə nömrəsi 2</th>
        <th style="font-weight: bold;text-align: center">Yaş kateqoriyası</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $value)
        <tr>
            <td style="text-align: center">{{ $key + 1 }}</td>
            <td style="text-align: center"><b>{{ $value->UIN  }}</b></td>
            <td style="text-align: center">{{ $value->fin_code  }}</td>
            <td style="text-align: center">{{ $value->name }}</td>
            <td style="text-align: center">{{ $value->surname  }}</td>
            <td style="text-align: center">{{ $value->patronymic  }}</td>
            <td style="text-align: center">{{ $value->parent_name }}</td>
            <td style="text-align: center">{{ $value->parent_surname }}</td>
            <td style="text-align: center">{{ $value->parent_patronymic }}</td>
            <td style="text-align: center">{{ "0".$value->first_prefix ." ".$value->first_phone_number }}</td>
            <td style="text-align: center">{{ "0".$value->second_prefix ." ".$value->second_phone_number }}</td>
            <td style="text-align: center">{{ $value->age_category  }}</td>
        </tr>
    @endforeach
    </tbody>
</table>




