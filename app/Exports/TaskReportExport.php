<?php

namespace App\Exports;

use App\Models\TaskManagement;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TaskReportExport implements WithMultipleSheets
{
    use Exportable;

    protected $dataArray;
    
    public function __construct(array $array)
    {
        $this->dataArray = $array;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->dataArray as $key => $val) {
            $sheets[] = new IndividualReportExport($val);
        }
        return $sheets;
    }
}
