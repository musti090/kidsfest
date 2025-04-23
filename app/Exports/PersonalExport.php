<?php

namespace App\Exports;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;

class PersonalExport implements FromView, WithColumnWidths, ShouldQueue
{
    use Exportable;

    protected $data;
    protected  $cities;

    protected  $regions;

    protected  $nominations;
    protected $education_schools;
    protected $education_school_names;
    protected $education_new_school_names;
    protected $awards;


    public function __construct($data,$cities,$regions,$nominations,$education_schools,$education_school_names,$education_new_school_names)
    {
        $this->data = $data;
        $this->cities = $cities;
        $this->regions = $regions;
        $this->nominations = $nominations;
        $this->education_schools = $education_schools;
        $this->education_school_names = $education_school_names;
        $this->education_new_school_names = $education_new_school_names;
       // $this->awards = $awards;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {

        return view('backend.exports.personal', [
            'data' => $this->data,
            'cities' => $this->cities,
            'regions' => $this->regions,
            'nominations' => $this->nominations,
            'education_schools' => $this->education_schools,
            'education_school_names' => $this->education_school_names,
            'education_new_school_names' => $this->education_new_school_names
          //  'awards' => $this->awards
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 15,
            'D' => 15,
            'E' => 20,
            'F' => 20,
            'G' => 15,
            'H' => 10,
            'I' => 80,
            'J' => 30,
            'K' => 30,
            'L' => 100,
            'M' => 80,
            'N' => 50,
            'O' => 150,
            'P' => 80,
            'Q' => 50,
            'R' => 20,
            'S' => 20,
            'T' => 20,
            'U' => 20,
            'V' => 20,
            'W' => 50,
            'X' => 50,
            'Y' => 15,
            'Z' => 50,
            'AA' => 20
        ];
    }

}
