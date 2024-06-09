<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ProductService\Entities\ProductService;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'user_id',
        'pipeline_id',
        'stage_id',
        'sources',
        'products',
        'notes',
        'labels',
        'order',
        'phone',
        'created_by',
        'is_active',
        'date',
        'workspace_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\LeadFactory::new();
    }
    public function stage()
    {
        return $this->hasOne('Modules\Lead\Entities\LeadStage', 'id', 'stage_id');
    }
    public function labels()
    {
        if($this->labels)
        {
            return Label::whereIn('id', explode(',', $this->labels))->get();
        }

        return false;
    }


    public function files()
    {
        return $this->hasMany('Modules\Lead\Entities\LeadFile', 'lead_id', 'id');
    }

    public function pipeline()
    {
        return $this->hasOne('Modules\Lead\Entities\Pipeline', 'id', 'pipeline_id');
    }

    public function products()
    {
        if($this->products)
        {
            return \Modules\ProductService\Entities\ProductService::whereIn('id', explode(',', $this->products))->get();
        }

        return [];
    }

    public function sources()
    {
        if($this->sources)
        {
            return Source::whereIn('id', explode(',', $this->sources))->get();
        }

        return [];
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_leads', 'lead_id', 'user_id');
    }

    public function activities()
    {
        return $this->hasMany('Modules\Lead\Entities\LeadActivityLog', 'lead_id', 'id')->orderBy('id', 'desc');
    }

    public function discussions()
    {
        return $this->hasMany('Modules\Lead\Entities\LeadDiscussion', 'lead_id', 'id')->orderBy('id', 'desc');
    }

    public function calls()
    {
        return $this->hasMany('Modules\Lead\Entities\LeadCall', 'lead_id', 'id');
    }

    public function emails()
    {
        return $this->hasMany('Modules\Lead\Entities\LeadEmail', 'lead_id', 'id')->orderByDesc('id');
    }

    public function tasks()
    {
        return $this->hasMany('Modules\Lead\Entities\LeadTask', 'lead_id', 'id');
    }

    public function complete_tasks()
    {
        return $this->hasMany('Modules\Lead\Entities\LeadTask', 'lead_id', 'id')->where('status', '=', 1);
    }

}
