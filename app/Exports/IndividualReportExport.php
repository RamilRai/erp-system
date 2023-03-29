<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class IndividualReportExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    use Exportable;
    protected $detailArray;
    protected $heading;

    public function __construct(array $val)
    {
        $this->heading = ['S.No.', 'Project Name', 'Ticket Number', 'Task Title', 'Task Type', 'Start Date', 'End Date', 'Estimated Hours', 'Priority', 'Task Point', 'Task Status', 'Achieved Point'];
        $this->detailArray = $val;
    }

    public function collection()
    {
        $dataArray = [];
        foreach ($this->detailArray as $key => $val) {
            $data = [];
            $data['sno'] = ++$key;
            $data['project_name'] = $val->project_name;
            $data['ticket_number'] = $val->ticket_number;
            $data['task_title'] = $val->task_title;
            $data['task_type'] = $val->task_type;
            $data['task_start_date_bs'] = $val->task_start_date_bs;
            $data['task_end_date_bs'] = $val->task_end_date_bs;
            $data['estimated_hour'] = $val->estimated_hour;
            $data['priority'] = $val->priority;
            $data['task_point'] = $val->task_point;
            $data['task_status'] = $val->task_status;
            $data['achieved_point'] = $val->achieved_point;
            $dataArray[] = $data;
        }
        return collect($dataArray);
    }

    public function headings(): array
    {        
        return $this->heading;
    }

    public function title(): string
    {
        return $this->detailArray[0]->staffname;
    }
}
