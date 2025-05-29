<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class MateriaisLmImport implements WithMultipleSheets, WithCalculatedFormulas
{
    protected $id_lm;

    public function __construct($id_lm)
    {
        $this->id_lm = $id_lm;
    }

    public function sheets(): array
    {
        return [
            'MONT.LISTA' => new MateriaisLmSheetImport($this->id_lm),
        ];
    }
}
