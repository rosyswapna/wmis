<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorkersReportExport implements FromQuery, WithHeadings, WithMapping
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
        $mapKeys = [];

        foreach ($this->map as $m) {
            $mapKeys[] = $row->$m;
        }

        return $mapKeys;
    }
}