<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpdeskConversion extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'description',
        'attachments',
        'sender'
    ];

    public function replyBy(){
        if($this->sender=='user'){
            return $this->ticket;
        }
        else{
            return $this->hasOne('App\Models\User','id','sender')->first();
        }
    }
    public function ticket(){
        return $this->hasOne(HelpdeskTicket::class,'id','ticket_id');
    }
}
