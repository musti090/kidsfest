<table>
    <thead>
    <tr>
        <th style="font-weight: bold;text-align: center">№</th>
        <th style="font-weight: bold;text-align: center">Kollektivin kodu</th>
        <th style="font-weight: bold;text-align: center">Kollektiv rəhbərinin FİN-i</th>
        <th style="font-weight: bold;text-align: center">Kollektivin adı</th>
        <th style="font-weight: bold;text-align: center">Yarandığı il</th>
        <th style="font-weight: bold;text-align: center">Kollektiv rəhbərinin adı</th>
        <th style="font-weight: bold;text-align: center">Kollektiv rəhbərinin soyadı</th>
        <th style="font-weight: bold;text-align: center">Kollektiv rəhbərinin ata adı</th>
        <th style="font-weight: bold;text-align: center">Müraciət etdiyi nominasiya</th>
        <th style="font-weight: bold;text-align: center">Müraciət etdiyi şəhər</th>
        <th style="font-weight: bold;text-align: center">Müraciət etdiyi rayon</th>
        <th style="font-weight: bold;text-align: center"> I telefon nömrəsi</th>
        <th style="font-weight: bold;text-align: center"> II telefon nömrəsi</th>
        <th style="font-weight: bold;text-align: center"> Email</th>
        <th style="font-weight: bold;text-align: center"> Qeydiyyat tarixi</th>

    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $value)
        <tr>
            <td style="text-align: center">{{ $key + 1 }}</td>
            <td style="text-align: center"><b>{{ $value->UIN  }}</b></td>
            <td style="text-align: center">{{ $value->director_fin_code  }}</td>
            <td style="text-align: center">{{ $value->collective_name  }}</td>
            <td style="text-align: center">{{ $value->collective_created_date  }}</td>
            <td style="text-align: center">{{ $value->director_name }}</td>
            <td style="text-align: center">{{ $value->director_surname  }}</td>
            <td style="text-align: center">{{ $value->director_patronymic  }}</td>
            <td style="text-align: center">{{ \Illuminate\Support\Facades\DB::table('nominations')->select('name')->where('id',$value->collective_nomination_id)->first()->name }}</td>
            <td style="text-align: center">{{ \Illuminate\Support\Facades\DB::table('all_cities')->select('city_name')->where('id',$value->collective_city_id)->first()->city_name }}</td>
            <td style="text-align: center">{{ \Illuminate\Support\Facades\DB::table('m_n_regions')->select('name')->where('id',$value->collective_mn_region_id)->first()->name }}</td>
            <td style="text-align: center">{{ "0".$value->first_prefix ." ".$value->first_phone_number }}</td>
            <td style="text-align: center">{{ "0".$value->second_prefix ." ".$value->second_phone_number }}</td>
            <td style="text-align: center">{{ $value->email  }}</td>
            <td style="text-align: center">{{  \Carbon\Carbon::parse($value->created_at )->format('d.m.Y H:i:s')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>




