<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpresaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id"                       => $this->id,
            "nome_empresa"             => $this->nome_empresa,
            "cnpj"                     => $this->cnpj,
            "inscricao_estadual"       => $this->inscricao_estadual,
            "filial"                   => $this->filial
        ];
    }
}
