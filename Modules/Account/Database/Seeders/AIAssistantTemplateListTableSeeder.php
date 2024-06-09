<?php

namespace Modules\Account\Database\Seeders;

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
                'template_module'=>'transfer',
                'prompt'=> "Generate a list of common reasons for employee transfers within an organization. Include reasons such as ##reasons##. Please provide a comprehensive and varied list of reasons that can help employers understand and address employee transfer situations effectively.",
                'field_json'=>'{"field":[{"label":"Transfer reasons","placeholder":"e.g.career development,special projects or initiatives","field_type":"textarea","field_name":"reasons"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'revenues',
                'prompt'=> "generate sutiable and valuable name of document please note name should justify the document description  '##description##' .",
                'field_json'=>'{"field":[{"label":"Document Name","placeholder":"e.g. verification file ","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'payment',
                'prompt'=> "Generate content for confirming a successful payment transfer. Write a message to inform the recipient that the payment transfer has been successfully completed. The content should be concise, informative. Include the necessary  details,##note## to convey the successful transfe information.plase not cotent should be without header,footer",
                'field_json'=>'{"field":[{"label":"Notes","placeholder":"e.g. any notes","field_type":"textarea","field_name":"note"}]}',
                'is_tone'=> 0,
            ],

        ];
        foreach($defaultTemplate as $temp)
        {
            $check = AssistantTemplate::where('template_module',$temp['template_module'])->where('module','Account')->where('template_name',$temp['template_name'])->exists();
            if(!$check)
            {
                AssistantTemplate::create(
                    [
                        'template_name' => $temp['template_name'],
                        'template_module' => $temp['template_module'],
                        'module' => 'Account',
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
