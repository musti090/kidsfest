<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgeCategoryController extends Controller
{

    public function personal()
    {
        $all_data = DB::table('personal_users')
            ->leftJoin('personal_user_card_information', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
            ->leftJoin('personal_user_parents', 'personal_user_parents.personal_user_id', '=', 'personal_users.id')
            ->where('personal_user_card_information.age_category', '=', null)
            //->where('personal_user_card_information.age', '=', 18)
            ->take(1000)
            ->get();

        foreach ($all_data as $value) {

            if ($value->age >= 6 && $value->age <= 9) {
                DB::table('personal_user_card_information')->where('personal_user_id', $value->id)->update(['age_category' => '6-9']);
            } elseif ($value->age >= 10 && $value->age <= 13) {
                DB::table('personal_user_card_information')->where('personal_user_id', $value->id)->update(['age_category' => '10-13']);
            } elseif ($value->age >= 14 && $value->age <= 17) {
                DB::table('personal_user_card_information')->where('personal_user_id', $value->id)->update(['age_category' => '14-17']);
            } else {
                echo "Yaş kategoriyası boşdur!!!<br>";
            }

        }
        /*foreach ($data as $value) {

            if($value->age >=6 && $value->age <= 9){
                echo "Yaş: ".$value->age." - Yaş kategoriyası: 6-9 <br>";
            }
            elseif($value->age >=10 && $value->age <= 13){
                echo "Yaş: ".$value->age." - Yaş kategoriyası: 10-13 <br>";
            }
            elseif($value->age >=14 && $value->age <= 17){
                echo "Yaş: ".$value->age." - Yaş kategoriyası: 14-17 <br>";
            }
            else{
                echo "Yaş kategoriyası boşdur!!!<br>";
            }

        }*/

        return "Ela";
    }

    public function collective()
    {

        $all_data = DB::table('collectives')->select('id')->where('age_category', null)->get();

        /*        foreach ($all_data as $value) {

                    if($value->age >=6 && $value->age <= 9){
                        DB::table('personal_user_card_information')->where('personal_user_id', $value->id)->update(['age_category' => '6-9']);
                    }
                    elseif($value->age >=10 && $value->age <= 13){
                        DB::table('personal_user_card_information')->where('personal_user_id', $value->id)->update(['age_category' => '10-13']);
                    }
                    elseif($value->age >=14 && $value->age <= 17){
                        DB::table('personal_user_card_information')->where('personal_user_id', $value->id)->update(['age_category' => '14-17']);
                    }
                    else{
                        echo "Yaş kategoriyası boşdur!!!<br>";
                    }

                }*/
        foreach ($all_data as $value) {

            $ages = DB::table('collective_teenagers')
                ->select('collective_id', 'age')
                ->where('collective_id', $value->id)
                ->pluck('age')
                ->toArray();
            foreach ($ages as $age) {
                echo $age . "<br>";
            }
            // Təkrarlanma sayını hesablayırıq
            $counts = array_count_values($ages);

            // Ən çox təkrarlanan ədədlərin maksimum sayını tapırıq
            $maxFrequency = max($counts);

            // Eyni sayda olan bütün ədədləri tapırıq
            $mostFrequentNumbers = array_keys(array_filter($counts, function ($count) use ($maxFrequency) {
                return $count == $maxFrequency;
            }));

            // Ən kiçik/boyuk ədədi tapırıq
            $smallestMostFrequentNumber = max($mostFrequentNumbers);


                if ($smallestMostFrequentNumber >= 6 && $smallestMostFrequentNumber <= 9) {
                    echo "Yaş: " . $smallestMostFrequentNumber . " - Yaş kategoriyası: 6-9 <br>";
                    DB::table('collectives')->where('id', $value->id)->update(['age_category' => '6-9']);

                } elseif ($smallestMostFrequentNumber >= 10 && $smallestMostFrequentNumber <= 13) {
                    echo "Yaş: " . $smallestMostFrequentNumber . " - Yaş kategoriyası: 10-13 <br>";
                    DB::table('collectives')->where('id', $value->id)->update(['age_category' => '10-13']);
                } elseif ($smallestMostFrequentNumber >= 14 && $smallestMostFrequentNumber <= 17) {
                    echo "Yaş: " . $smallestMostFrequentNumber . " - Yaş kategoriyası: 14-17 <br>";
                    DB::table('collectives')->where('id', $value->id)->update(['age_category' => '14-17']);

                } else {
                    echo "Yaş kategoriyası boşdur!!!<br>";
                }


            echo "<hr>";

        }

        //   return "Ela";
    }
}
