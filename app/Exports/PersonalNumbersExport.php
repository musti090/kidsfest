<?php

namespace App\Exports;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;

class PersonalNumbersExport implements FromView, WithColumnWidths, ShouldQueue
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

        return view('backend.exports.personal-numbers', [
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
            'H' => 20,
            'I' => 20,
            'J' => 25,
            'K' => 25,
            'L' => 20
        ];
    }

}
