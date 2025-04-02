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

    public function __construct($data)
    {
        $this->data = $data;

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {

        return view('backend.exports.personal', [
            'data' => $this->data
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
            'I' => 50,
            'J' => 30,
            'K' => 100,
            'L' => 100,
            'M' => 30,
            'N' => 80,
            'O' => 20,
            'P' => 80,
            'Q' => 200,
            'R' => 20,
            'S' => 20,
            'T' => 20,
            'U' => 20,
            'V' => 20,
            'W' => 20,
            'X' => 50,
            'Y' => 15,
            'Z' => 10,
            'AA' => 20
        ];
    }

}
