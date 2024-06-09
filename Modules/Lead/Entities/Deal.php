<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ProductService\Entities\ProductService;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'pipeline_id',
        'stage_id',
        'group_id',
        'sources',
        'products',
        'created_by',
        'notes',
        'labels',
        'phone',
        'permissions',
        'status',
        'is_active',
        'workspace_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\DealFactory::new();
    }
    public static $permissions = [
        'Client View Tasks',
        'Client View Products',
        'Client View Sources',
        'Client View Contacts',
        'Client View Files',
        'Client View Invoices',
        'Client View Custom fields',
        'Client View Members',
        'Client Add File',
        'Client Deal Activity',
    ];

    public static $statues = [
        'Active' => 'Active',
        'Won' => 'Won',
        'Loss' => 'Loss',
    ];
    public function labels()
    {
        if($this->labels)
        {
            return Label::whereIn('id', explode(',', $this->labels))->get();
        }

        return false;
    }

    public static function getDealSummary($deals)
    {
        $total = 0;

        foreach($deals as $deal)
        {
            $total += $deal->price;
        }

        return currency_format_with_sym($total);
    }
    public function pipeline()
    {
        return $this->hasOne('Modules\Lead\Entities\Pipeline', 'id', 'pipeline_id');
    }

    public function stage()
    {
        return $this->hasOne('Modules\Lead\Entities\DealStage', 'id', 'stage_id');
    }

    public function group()
    {
        return $this->hasOne('Modules\Lead\Entities\Group', 'id', 'group_id');
    }

    public function clients()
    {
        return $this->belongsToMany('App\Models\User', 'client_deals', 'deal_id', 'client_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_deals', 'deal_id', 'user_id');
    }

    public function products()
    {
        if($this->products)
        {
            return ProductService::whereIn('id', explode(',', $this->products))->get();
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

    public function files()
    {
        return $this->hasMany('Modules\Lead\Entities\DealFile', 'deal_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany('Modules\Lead\Entities\DealTask', 'deal_id', 'id');
    }

    public function complete_tasks()
    {
        return $this->hasMany('Modules\Lead\Entities\DealTask', 'deal_id', 'id')->where('status', '=', 1);
    }

    public function invoices()
    {
        return $this->hasMany('Modules\Lead\Entities\Invoice', 'deal_id', 'id');
    }

    public function calls()
    {
        return $this->hasMany('Modules\Lead\Entities\DealCall', 'deal_id', 'id');
    }

    public function emails()
    {
        return $this->hasMany('Modules\Lead\Entities\DealEmail', 'deal_id', 'id')->orderByDesc('id');
    }

    public function activities()
    {
        return $this->hasMany('Modules\Lead\Entities\DealActivityLog', 'deal_id', 'id')->orderBy('id', 'desc');
    }

    public function discussions()
    {
        return $this->hasMany('Modules\Lead\Entities\DealDiscussion', 'deal_id', 'id')->orderBy('id', 'desc');
    }


}
