<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpdeskTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'name',
        'email',
        'category',
        'subject',
        'status',
        'description',
        'attachments',
        'note',
        'user_id',
        'workspace',
        'created_by',
    ];
    public function user()
    {
        return  $this->hasOne(User::class,'id','user_id');
    }
    public function createdBy()
    {
        return  $this->hasOne(User::class,'id','created_by');
    }
    public function conversions()
    {
        return $this->hasMany(HelpdeskConversion::class, 'ticket_id', 'id')->orderBy('id');
    }
    public function tcategory()
    {
        return $this->hasOne(HelpdeskTicketCategory::class, 'id', 'category');
    }
}
