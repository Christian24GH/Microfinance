<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeRecordResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'policy_id' => $this->policy_id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'reason' => $this->reason,
            'tasks_completed' => $this->tasks_completed,
            'status' => $this->status,
            'total_hours' => $this->total_hours,
            'compensation' => $this->compensation,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
