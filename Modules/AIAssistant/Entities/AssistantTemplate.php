<?php

namespace Modules\AIAssistant\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssistantTemplate extends Model
{
    use HasFactory;
    protected $table='assistant_templates';
    protected $fillable = [
            'template_name',
            'template_module',
            'module',
            'prompt',
            'field_json',
            'is_tone'
    ];

    protected static function newFactory()
    {
        return \Modules\AIAssistant\Database\factories\AssistantTemplateFactory::new();
    }

    public static function flagOfCountry(){
        $arr = [
            'ar' => '🇦🇪 ar',
            'da' => '🇩🇰 ad',
           'de' => '🇩🇪 de',
            'es' => '🇪🇸 es',
            'fr' => '🇫🇷 fr',
           'it'	=>  '🇮🇹 it',
            'ja' => '🇯🇵 ja',
            'nl' => '🇳🇱 nl',
            'pl'=> '🇵🇱 pl',
            'ru' => '🇷🇺 ru',
            'pt' => '🇵🇹 pt',
            'en' => '🇮🇳 en',
            'tr' => '🇹🇷 tr',
            'pt-br' => '🇧🇷 pt-br',
        ];
        return $arr;
    }
}
