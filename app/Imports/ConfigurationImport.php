<?php

namespace App\Imports;

use App\Models\Configuration;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
// use Maatwebsite\Excel\Events\AfterImport;

class ConfigurationImport implements ToModel, WithHeadingRow, WithEvents
{
    use Importable;

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                // Configuration::truncate();
                \DB::table('configurations')->whereRaw("JSON_CONTAINS(configurations.facilityId, '".\Common::getCurrentFacilityId()."')")->delete();
            },
            /*AfterImport::class => function(AfterImport $event) {
                \Auth::logout();
            },*/
        ];
    }

    public function model(array $row)
    {
        return new Configuration($row);
    }
}