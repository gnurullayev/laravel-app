<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentConfiguration extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string; mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "field_seq" => $this->field_seq,
            "is_mandatory" => $this->is_mandatory,
            "field_type" => $this->field_type,
            "field_name" => $this->field_name,
            "document_id" => $this->document_id,
            "select_values" => $this->select_values
        ];
    }
}
