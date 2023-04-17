<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class IndividualExtraReportExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    use Exportable;
    protected $detailArray;
    protected $heading;

    public function __construct(array $v)
    {
        $this->heading = ['S.No.', 'Project Name', 'Ticket Number', 'Task Title', 'Task Type', 'Started Date', 'Completed Date', 'Task Status', 'Achieved Point'];
        $this->detailArray = $v;
    }

    public function collection()
    {
        $dataArray = [];
        foreach ($this->detailArray as $key => $val) {
            $data = [];
            $data['sno'] = ++$key;
            $data['project_name'] = $val->project->project_name;
            $data['ticket_no'] = $val->ticket_no;
            $data['task_title'] = $val->task_title;
            $data['task_type'] = $val->task_type;
            $createdDate = Carbon::parse($val->task_created_date_bs);
            $data['task_created_date_bs'] = $createdDate->format('F j, Y, g:i a');
            if (!empty($val->task_completed_date_bs)) {
                $completedDate = Carbon::parse($val->task_completed_date_bs);
                $data['task_completed_date_bs'] = $completedDate->format('F j, Y, g:i a');
            } else {
                $data['task_completed_date_bs'] = "Not Completed";
            }
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
        return $this->detailArray[0]->createdBy->first_name . ' ' . $this->detailArray[0]->createdBy->middle_name . ' ' . $this->detailArray[0]->createdBy->last_name . '-Extra Task';
    }
}
