<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pipeline_id',
        'created_by',
        'workspace_id',
        'order',
    ];

    public function lead()
    {
        if(\Auth::user()->type == 'client')
        {
            return Lead::select('leads.*')->join('client_leads', 'client_leads.lead_id', '=', 'leads.id')->where('client_leads.client_id', '=', \Auth::user()->id)->where('leads.stage_id', '=', $this->id)->orderBy('leads.order')->get();
        }
        else
        {
            return Lead::select('leads.*')->join('user_leads', 'user_leads.lead_id', '=', 'leads.id')->where('user_leads.user_id', '=', \Auth::user()->id)->where('leads.stage_id', '=', $this->id)->orderBy('leads.order')->get();
        }
    }

	public function pipeline()
    {
        return $this->hasOne('Modules\Lead\Entities\Pipeline', 'id', 'pipeline_id');
    }
	
    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\LeadStageFactory::new();
    }

}
