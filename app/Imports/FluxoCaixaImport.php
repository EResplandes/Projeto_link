<?php

namespace App\Imports;

use App\Models\ControleCaixa;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class FluxoCaixaImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ControleCaixa([
            'id_caixa'           => $row[0],
            'dt_lancamento'      => Date::excelToDateTimeObject($row[1]),
            'discriminacao'      => $row[2],
            'debito'             => $row[3],
            'credito'            => $row[4],
            'observacao'         => $row[5],
            'tipo_caixa'         => $row[6],
        ]);
    }
}
