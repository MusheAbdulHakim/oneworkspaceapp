<?php

namespace Modules\SideMenuBuilder\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SideMenuBuilder extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\SideMenuBuilder\Database\factories\SideMenuBuilderFactory::new();
    }

    public static $menus = [
        'mainmenu' => 'Main Menu',
        'submenu' => 'Sub Menu'
    ];


    public static $links = [
        'hash' => 'Hash Link (#)',
        'external' => 'Out Site Link',
        'internal' => 'Site Link',
    ];

    public static $show_window = [
        'same_window' => 'Existing Window/Tab',
        'new_window' => 'Isolated Window/Tab',
        'iframe' => 'Embedded iFrame'
    ];

    public static $hrefs = [
        'http://' => 'http://',
        'https://' => 'https://'
    ];

    public function creatorName()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function getParentIdByName()
    {
        return $this->hasOne(SideMenuBuilder::class, 'id', 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(SideMenuBuilder::class, 'parent_id', 'id')->orderBy('position');
    }
}
