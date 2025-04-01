<table>
    <thead>
    <tr>
        <th style="font-weight: bold;text-align: center">№</th>
        <th style="font-weight: bold;text-align: center">Kod</th>
        <th style="font-weight: bold;text-align: center">FİN</th>
        <th style="font-weight: bold;text-align: center">Ad</th>
        <th style="font-weight: bold;text-align: center">Soyad</th>
        <th style="font-weight: bold;text-align: center">Ata adı</th>
        <th style="font-weight: bold;text-align: center">Doğum tarixi</th>
        <th style="font-weight: bold;text-align: center">Cinsi</th>
        <th style="font-weight: bold;text-align: center">Müraciət etdiyi nominasiya</th>
        <th style="font-weight: bold;text-align: center">Müraciət etdiyi şəhər/rayon</th>
        <th style="font-weight: bold;text-align: center">Qeydiyyat ünvanı</th>
        <th style="font-weight: bold;text-align: center">Faktiki yaşayış ünvanı	</th>
        <th style="font-weight: bold;text-align: center">Təhsil müəssisəsinin növü	</th>
        <th style="font-weight: bold;text-align: center">Təhsil müəssisəsinin adı	</th>
        <th style="font-weight: bold;text-align: center">Peşəkar/Həvəskar</th>
        <th style="font-weight: bold;text-align: center">Xüsusi incəsənət təhsili</th>
        <th style="font-weight: bold;text-align: center">Təltiflər</th>
        <th style="font-weight: bold;text-align: center">Valideynin FİN-i</th>
        <th style="font-weight: bold;text-align: center">Valideynin adı</th>
        <th style="font-weight: bold;text-align: center">Valideynin soyadı</th>
        <th style="font-weight: bold;text-align: center">Valideynin ata adı</th>
        <th style="font-weight: bold;text-align: center">I telefon nömrəsi</th>
        <th style="font-weight: bold;text-align: center">II telefon nömrəsi</th>
        <th style="font-weight: bold;text-align: center">Email</th>
        <th style="font-weight: bold;text-align: center">Yaş kateqoriyası</th>
        <th style="font-weight: bold;text-align: center">Yaş</th>
        <th style="font-weight: bold;text-align: center">Qeydiyyat tarixi</th>
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
            <td style="text-align: center">{{  \Carbon\Carbon::parse($value->birth_date )->format('d.m.Y')}}</td>
            <td style="text-align: center">{{  $value->gender == 'MALE' ? 'Kişi' : 'Qadın' }}</td>
            <td style="text-align: center">{{ \Illuminate\Support\Facades\DB::table('nominations')->select('name')->where('id',$value->nomination_id)->first()->name }}</td>
            <td style="text-align: center">{{ \Illuminate\Support\Facades\DB::table('all_cities')->select('city_name')->where('id',$value->all_city_id)->first()->city_name }}</td>
            <td style="text-align: center">{{ $value->registration_address  }}</td>
            <td style="text-align: center">{{ $value->live_address  }}</td>
            <td style="text-align: center">{{ \Illuminate\Support\Facades\DB::table('education_schools')->select('school_type')->where('id',$value->school_type_id)->first()->school_type }}</td>
            <td style="text-align: center">
                @if($value->created_at < '2025-03-15 01:28:00')
                    {{ \Illuminate\Support\Facades\DB::table('education_school_names')->select('name')->where('id',$value->school_id)->first()->name ?? null }}
                @else
                    {{ \Illuminate\Support\Facades\DB::table('education_school_new_names')->select('name')->where('id',$value->school_id)->first()->name ?? null }}
                @endif
            </td>
            <td style="text-align: center">
                @if( $value->art_type == 1 && $value->art_education != null)
                  Peşəkar
                @elseif( $value->art_type == 2 && $value->art_education != null)
                    Peşəkar
                @else
                    Həvəskar
                @endif
            </td>
            <td style="text-align: center">{{ $value->art_education ?? null }}</td>
            <td style="text-align: center">
                @php
                    $awards = \Illuminate\Support\Facades\DB::table('personal_awards')->where('personal_user_id',$value->id)->select('awards_name')->get();
                @endphp
                @if( count($awards) > 0)
                    @foreach($awards as $key => $award)
                        {{ $award->awards_name }} {{ $key >= 1 ? ',' : '' }}
                    @endforeach
                @endif
            </td>
            <td style="text-align: center">{{ $value->parent_fin_code }}</td>
            <td style="text-align: center">{{ $value->parent_name }}</td>
            <td style="text-align: center">{{ $value->parent_surname }}</td>
            <td style="text-align: center">{{ $value->parent_patronymic }}</td>
            <td style="text-align: center">{{ "0".$value->first_prefix ." ".$value->first_phone_number }}</td>
            <td style="text-align: center">{{ "0".$value->second_prefix ." ".$value->second_phone_number }}</td>
            <td style="text-align: center">{{ $value->email }}</td>
            <td style="text-align: center">{{ $value->age_category  }}</td>
            <td style="text-align: center">{{ $value->age  }}</td>
            <td style="text-align: center">{{  \Carbon\Carbon::parse($value->created_at )->format('d.m.Y H:i:s')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>




