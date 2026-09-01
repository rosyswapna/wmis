<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoiceExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected Builder $query;
    protected array $headings;
    protected array $map;

    public function __construct(
        Builder $query,
        array $headings,
        array $map
    ) {
        $this->query = $query;
        $this->headings = $headings;
        $this->map = $map;
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        $values = []; 
        foreach ($this->map as $field) { 
            $values[] = data_get($row, $field); 
        } 
        
        return $values;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}