<?php

namespace App\Exports;

use App\Models\Configuration;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConfigurationExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array
    {
        $model = new Configuration();
        $exportables = $model->get_exportable();
        return $exportables;
    }

    public function map($configuration): array
    {
        $model = new Configuration();
        $exportables = $model->get_exportable();
        $data = array();
        foreach($exportables as $exportable){
            $data[$exportable] = $configuration->$exportable;
        }
        return $data;
    }

    public function collection()
    {
        return Configuration::all();
    }
}