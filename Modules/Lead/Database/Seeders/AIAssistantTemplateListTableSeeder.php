<?php

namespace Modules\Lead\Database\Seeders;

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
                'template_name'=>'name',
                'template_module'=>'lead',
                'prompt'=>"generate lead  name form this description : ##description##",
                'field_json'=>'{"field":[{"label":"Lead description","placeholder":"e.g. example website ","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'subject',
                'template_module'=>'lead',
                'prompt'=> "Generate a lead subject line for a marketing campaign targeting potential customers for a software development company specializing in web and mobile applications.",
                'field_json'=>'{"field":[{"label":"Description","placeholder":"e.g.","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'notes',
                'template_module'=>'lead',
                'prompt'=> "Generate short description Note for lead ##description##",
                'field_json'=>'{"field":[{"label":"Lead description","placeholder":"e.g. create notes for lead user ","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'subject',
                'template_module'=>'lead_call',
                'prompt'=> "generate call for title that justify this resoan ##description## for the call.  please note that this call is for lead",
                'field_json'=>'{"field":[{"label":"Call Resoan","placeholder":"e.g.Time Management Strategy Call","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'lead_call',
                'prompt'=> "Generate a short note summarizing the key points discussed during a lead '##name##' call. The purpose of the note is to capture important details and action items discussed with the ##name## lead. Please structure the note in a concise and organized manner.",
                'field_json'=>'{"field":[{"label":"Lead name","placeholder":"e.g.  ","field_type":"textarea","field_name":"name"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'notes',
                'template_module'=>'lead_notes',
                'prompt'=> "Generate short description Note for lead ##description##",
                'field_json'=>'{"field":[{"label":"Lead description","placeholder":"e.g. create notes for lead user ","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'subject',
                'template_module'=>'lead_email',
                'prompt'=> "Write 10 catchy email subject lines for this email Description: ##description##  ",
                'field_json'=>'{"field":[{"label":"Email Description","placeholder":"","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'lead_email',
                'prompt'=> "Generate a short note summarizing the key points discussed during a lead '##name##' email. The purpose of the note is to capture important details and action items discussed with the ##name## lead. Please structure the note in a concise and organized manner.",
                'field_json'=>'{"field":[{"label":"Lead name","placeholder":"e.g.  ","field_type":"textarea","field_name":"name"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'deal',
                'prompt'=>"generate deal name for this proposal description ##description##" ,
                'field_json'=>'{"field":[{"label":"Proposal Description","placeholder":"e.g.","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'subject',
                'template_module'=>'deal_call',
                'prompt'=> "generate call for tile that justify this resoan ##description## for the call ",
                'field_json'=>'{"field":[{"label":"Call Resoan","placeholder":"e.g.Time Management Strategy Call","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'deal_call',
                'prompt'=> "Generate a short note summarizing the key points discussed during a lead '##name##' call. The purpose of the note is to capture important details and action items discussed with the ##name## lead. Please structure the note in a concise and organized manner.",
                'field_json'=>'{"field":[{"label":"Lead name","placeholder":"e.g.  ","field_type":"textarea","field_name":"name"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'notes',
                'template_module'=>'deal_notes',
                'prompt'=> "Generate short description Note for lead ##description##",
                'field_json'=>'{"field":[{"label":"Lead description","placeholder":"e.g. create notes for lead user ","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 1,
            ],

            [
                'template_name'=>'subject',
                'template_module'=>'deal_email',
                'prompt'=> "Write 10 catchy email subject lines for this email Description: ##description##  ",
                'field_json'=>'{"field":[{"label":"Email Description","placeholder":"","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'deal_email',
                'prompt'=> "Generate a short note summarizing the key points discussed during a lead '##name##' email. The purpose of the note is to capture important details and action items discussed with the ##name## lead. Please structure the note in a concise and organized manner.",
                'field_json'=>'{"field":[{"label":"Lead name","placeholder":"e.g.  ","field_type":"textarea","field_name":"name"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'deal task',
                'prompt'=>"Generate a task name for this decription that specifically related to ##instruction##.",
                'field_json'=>'{"field":[{"label":"Task Instruction","placeholder":"e.g.","field_type":"textarea","field_name":"instruction"}]}',
                'is_tone'=> 0,
            ],

        ];
        foreach($defaultTemplate as $temp)
        {
            $check = AssistantTemplate::where('template_module',$temp['template_module'])->where('module','Lead')->where('template_name',$temp['template_name'])->exists();
            if(!$check)
            {
                AssistantTemplate::create(
                    [
                        'template_name' => $temp['template_name'],
                        'template_module' => $temp['template_module'],
                        'module' => 'Lead',
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
