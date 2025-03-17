<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PersonalExport implements FromView,WithColumnWidths
{

    protected $data;
   // protected $parent_data;

    public function __construct($data/*,$parent_data*/)
    {
        $this->data = $data;
      //  $this->parent_data = $parent_data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('backend.exports.personal', [
            'data' => $this->data
           // 'parent_data' => $this->parent_data,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 10,
            'D' => 10,
            'E' => 15,
            'F' => 20,
            'G' => 15,
            'H' => 10,
            'I' => 30,
            'J' => 30,
            'K' => 100,
            'L' => 100,
            'M' => 30,
            'N' => 80,
            'O' => 20,
            'P' => 80,
            'Q' => 20
        ];
    }

}
