<?php

namespace App\Imports;

use App\Models\MateriasLm;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class MateriaisLmSheetImport implements ToModel, WithHeadingRow, WithMapping, WithCalculatedFormulas
{
    protected $id_lm;

    public function __construct($id_lm)
    {
        $this->id_lm = $id_lm;
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function map($row): array
    {
        return [
            'descricao'   => $row['descritiva'],
            'quantidade'  => $row['quant.'],
            'unidade'     => $row['un.'],
        ];
    }

    public function model(array $row)
    {
        return new MateriasLm([
            'id_status'             => 1,
            'descricao'             => $row['descricao'],
            'quantidade'            => $row['quantidade'],
            'unidade'               => $row['unidade'],
            'id_lm'                 => $this->id_lm,
            'liberado_almoxarife'   => 0
        ]);
    }
}
