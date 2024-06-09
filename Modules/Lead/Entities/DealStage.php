<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DealStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','pipeline_id','created_by','order','workspace_id'
    ];

    public function deals(){
        if(\Auth::user()->type == 'client'){
            return Deal::select('deals.*')->join('client_deals','client_deals.deal_id','=','deals.id')->where('client_deals.client_id', '=', \Auth::user()->id)->where('deals.stage_id', '=', $this->id)->orderBy('deals.order')->get();
        }else {
            return Deal::select('deals.*')->join('user_deals', 'user_deals.deal_id', '=', 'deals.id')->where('user_deals.user_id', '=', \Auth::user()->id)->where('deals.stage_id', '=', $this->id)->orderBy('deals.order')->get();
        }
    }
    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\DealStageFactory::new();
    }
}
