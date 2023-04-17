<?php

namespace App\Exports;

use App\Models\TaskManagement;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TaskReportExport implements WithMultipleSheets
{
    use Exportable;

    protected $dataArray;
    protected $extraReportArray;

    public function __construct(array $array, array $extraReportArray)
    {
        $this->dataArray = $array;
        $this->extraReportArray = $extraReportArray;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->dataArray as $val) {
            if (!empty($val[0]->project_name)) {
                $sheets[] = new IndividualReportExport($val);
            }
        }

        foreach ($this->extraReportArray as $k => $v) {
            $sheets[] = new IndividualExtraReportExport($v);
        }
        return $sheets;
    }
}
