<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pipeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
        'workspace_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\PipelineFactory::new();
    }
    public function dealStages()
    {
        return $this->hasMany('Modules\Lead\Entities\DealStage', 'pipeline_id', 'id')->where('created_by', '=', creatorId())->orderBy('order');
    }

    public function leadStages()
    {
        return $this->hasMany('Modules\Lead\Entities\LeadStage', 'pipeline_id', 'id')->where('created_by', '=', creatorId())->orderBy('order');
    }
}
