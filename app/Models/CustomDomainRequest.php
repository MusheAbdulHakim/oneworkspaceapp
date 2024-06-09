<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomDomainRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'domain',
        'status',
        'workspace',
        'created_by'
    ];


    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function workspaces()
    {
        return $this->hasOne(WorkSpace::class, 'id', 'workspace');
    }

    public static $statues = [
        'Pending',
        'Approved',
        'Rejected'
    ];
}
