<?php

namespace Modules\ProductService\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\AIAssistant\Entities\AssistantTemplate;

class AIAssistantTemplateListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $defaultTemplate = [
            [
                'template_name'=>'description',
                'template_module'=>'product',
                'prompt'=> "Write a long creative product description for: ##title## \n\nTarget audience is: ##audience## \n\nUse this description: ##description## \n\nTone of generated text must be:\n ##tone_language## \n\n",
                'field_json'=>'{"field":[{"label":"Product name","placeholder":"e.g. VR, Honda","field_type":"text_box","field_name":"title"},{"label":"Audience","placeholder":"e.g. Women, Aliens","field_type":"text_box","field_name":"audience"},{"label":"Product Description","placeholder":"e.g. VR is an innovative device that can allow you to be part of virtual world","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 1,
            ],

        ];
        foreach($defaultTemplate as $temp)
        {
            $check = AssistantTemplate::where('template_module',$temp['template_module'])->where('module','ProductService')->where('template_name',$temp['template_name'])->exists();
            if(!$check)
            {
                AssistantTemplate::create(
                    [
                        'template_name' => $temp['template_name'],
                        'template_module' => $temp['template_module'],
                        'module' => 'ProductService',
                        'prompt' => $temp['prompt'],
                        'field_json' => $temp['field_json'],
                        'is_tone' => $temp['is_tone'],
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
            }
        }
    }
}
