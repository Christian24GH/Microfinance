<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Spatie\Activitylog\Traits\LogsActivity;

class Workflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function stages(): HasMany
    {
        return $this->hasMany(WorkflowStage::class)->orderBy('stage_order');
    }

    public function getNextStage($currentStageOrder)
    {
        return $this->stages()
            ->where('stage_order', '>', $currentStageOrder)
            ->orderBy('stage_order')
            ->first();
    }

    public function getPreviousStage($currentStageOrder)
    {
        return $this->stages()
            ->where('stage_order', '<', $currentStageOrder)
            ->orderBy('stage_order', 'desc')
            ->first();
    }

    public function getFirstStage()
    {
        return $this->stages()->orderBy('stage_order')->first();
    }

    public function getLastStage()
    {
        return $this->stages()->orderBy('stage_order', 'desc')->first();
    }

    public function isFirstStage($stageOrder)
    {
        return $this->getFirstStage()?->stage_order === $stageOrder;
    }

    public function isLastStage($stageOrder)
    {
        return $this->getLastStage()?->stage_order === $stageOrder;
    }
}
