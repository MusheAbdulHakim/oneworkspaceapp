<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalAttechment extends Model
{
    use HasFactory;
    protected $fillable = [
        'proposal_id',
        'file_name',
        'file_path',
        'file_size',
    ];
}
