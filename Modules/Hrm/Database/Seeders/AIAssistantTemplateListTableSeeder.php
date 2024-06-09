<?php

namespace Modules\Hrm\Database\Seeders;

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
                'template_name'=>'leave_reason',
                'template_module'=>'leave',
                'prompt'=> "Generate a comma-separated string of common leave reasons that employees may provide to their employers. Include both personal and professional reasons for taking leave, such only '##type##'. Aim to generate a diverse range of leave reasons that can be used in different situations. Please provide a comprehensive and varied list of leave reasons that can help employers understand and accommodate their employees' needs.",
                'field_json'=>'{"field":[{"label":"Leave Type","placeholder":"e.g.illness, family emergencies,vacation","field_type":"text_box","field_name":"type"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'award',
                'prompt'=> "Generate a description for presenting the Award. The description should highlight ##reasons##. Emphasize the significance of the  Award as a symbol of recognition for employee's remarkable accomplishments and its representation of her '##reasons##' and impact on the organization. Please create a personalized and engaging description that conveys appreciation, pride, and gratitude for employee's contributions to the company's sucess",
                'field_json'=>'{"field":[{"label":"Award reasons","placeholder":"e.g.skilled, focused ,efficiency","field_type":"textarea","field_name":"reasons"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'transfer',
                'prompt'=> "Generate a list of common reasons for employee transfers within an organization. Include reasons such as ##reasons##. Please provide a comprehensive and varied list of reasons that can help employers understand and address employee transfer situations effectively.",
                'field_json'=>'{"field":[{"label":"Transfer reasons","placeholder":"e.g.career development,special projects or initiatives","field_type":"textarea","field_name":"reasons"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'resignation',
                'prompt'=> "Generate a description why an employee might choose to resign and request a transfer to another location within the company. Include both professional and personal reasons that could contribute to this decision. Examples may include ##reasons##. Aim to provide a comprehensive and varied description that can help employers understand and accommodate employees' needs when considering a transfer request.",
                'field_json'=>'{"field":[{"label":"Resignation reasons","placeholder":"e.g.career development,health issues","field_type":"textarea","field_name":"reasons"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'trip',
                'prompt'=> "Generate a description for organizing a company trip. The trip aims to ##aims## . Please provide a diverse description that highlight the benefits and positive outcomes associated with organizing a company trip. Focus on creating an engaging and enjoyable experience for employees while also achieving business objectives and cultivating a positive work environment.",
                'field_json'=>'{"field":[{"label":"Aims","placeholder":"e.g.career development,health issues","field_type":"textarea","field_name":"aims"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'promotion_title',
                'template_module'=>'promotion',
                'prompt'=> "Generate a list of promotion title suggestions for an ##role##. The promotion titles should reflect ##reasons##, and recognition of the ##role##'s accomplishments. Please provide a diverse range of promotion titles that align with ##role## job roles and levels within the company. Aim to create titles that are both professional and descriptive, highlighting the ##role##'s progression and impact within the organization.",
                'field_json'=>'{"field":[{"label":"Job","placeholder":"e.g.doctor, developer","field_type":"text_box","field_name":"role"},{"label":"Promotion Reasons","placeholder":"e.g.increased responsibility, higher position","field_type":"textarea","field_name":"reasons"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'promotion',
                'prompt'=> "Generate a promotion description for this title:##title##. ",
                'field_json'=>'{"field":[{"label":"Promotion Title","placeholder":"e.g.Medical Director","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'title',
                'template_module'=>'complaint',
                'prompt'=> "Generate a list of titles for complaints related to employee and company issues. ##reasons##. Please provide a range of titles that accurately reflect common complaint categories, ensuring they are concise, descriptive, and effective in conveying the nature of the complaint. ",
                'field_json'=>'{"field":[{"label":"Complaint reasons","placeholder":"e.g.unprofessional behavior, harassment,","field_type":"textarea","field_name":"reasons"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'complaint',
                'prompt'=> "Generate a Complaint description for this title:##title##. ",
                'field_json'=>'{"field":[{"label":"Complaint Title","placeholder":"e.g.Unprofessional Behavior Complaint","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'warning',
                'prompt'=> "Generate a warning description for an employee who consistently ##reasons##. The warning should address the employee's ##reasons##, including further disciplinary action or termination of employment. Please provide a clear and firm warning message that encourages the employee to review the policy and make immediate improvements.",
                'field_json'=>'{"field":[{"label":"Warning reasons","placeholder":"e.g.break attendance policy","field_type":"textarea","field_name":"reasons"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'termination',
                'prompt'=> "Generate a termination description for  the reason :##reason##. The description should convey the company's regret over the decision and outline the specific concerns, such as ##reasons##. Please provide a clear and professional message that explains the decision while expressing appreciation for the employee's contributions. Aim to offer guidance for personal and professional growth and provide necessary instructions regarding final paycheck and return of company property.",
                'field_json'=>'{"field":[{"label":"Termination reasons","placeholder":"e.g.power cut,gamimg activities","field_type":"textarea","field_name":"reasons"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'announcement',
                'prompt'=> "Generate an announcement title for ##reasons##. The title should be attention-grabbing and informative, effectively conveying the key message to the intended audience. Please ensure the title is appropriate for the given situation, whether it's about a ##reasons##. Aim to create a title that captures the essence of the announcement and sparks interest or curiosity among the readers.",
                'field_json'=>'{"field":[{"label":"Announcement reasons","placeholder":"e.g.power cut,gamimg activities","field_type":"textarea","field_name":"reasons"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'occasion',
                'template_module'=>'holiday',
                'prompt'=> "Generate a list of holiday occasions for celebrations and gatherings. The occasions should cover a variety of holidays and events throughout the year, such as ##name##. Please provide a diverse range of occasions that can be used for hosting parties, organizing special events, or planning festive activities. Aim to offer unique and creative ideas that cater to different cultures, traditions, and preferences.",
                'field_json'=>'{"field":[{"label":"Any Specific occasions","placeholder":"","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'title',
                'template_module'=>'event',
                'prompt'=> "Generate a creative and engaging event title for an upcoming event. The event can be a ##type##. Please focus on creating a title that captures the essence of the event, sparks curiosity, and encourages attendance. Aim to make the title memorable, intriguing, and aligned with the purpose and theme of the event. Consider the target audience, event objectives, and any specific keywords or ideas you would like to incorporate",
                'field_json'=>'{"field":[{"label":"Specific type of event","placeholder":"e.g.conference, workshop, seminar","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'document',
                'prompt'=>  "Generate a description based on a given document name:##name##. The document name: ##name## represents a specific file or document, and you need a descriptive summary or overview of its contents. Please provide a clear and concise description that captures the main points, purpose, or key information contained within the document. Aim to create a brief but informative description that gives the reader an understanding of what they can expect when accessing or reviewing the document.",
                'field_json'=>'{"field":[{"label":"Document name","placeholder":"","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'title',
                'template_module'=>'company policy',
                'prompt'=> "Generate a suitable title for the company policy regarding ##description##. The title should be clear, concise, and informative, effectively conveying the purpose and scope of the policy. Please ensure that the title reflects the importance of ##description##. Aim to create a title that is professional, easily understandable, and aligned with the company's culture and values.",
                'field_json'=>'{"field":[{"label":"Description of policy","placeholder":"e.g.Leave policies,Performance management","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'joining_content',
                'template_module'=>'joining letter settings',
                'prompt'=> "Generate a joining letter for {employee_name} who has been selected for the position of {designation} at {app_name}. Customize the letter by filling in the appropriate details and placeholders enclosed in {}. Please structure the letter to include the mentioned Variables:{date},{employee_name}{address},{designation},{start_date},{branch},{start_time},{end_time}, {total_hours} in their respective sections. Sign off the letter with the company name and the date. alse add subject ,company conditions for ##conditions##",
                'field_json'=>'{"field":[{"label":"Company  Policy/Condition (comma seperated string)","placeholder":"e.g.leave,holiday,salary","field_type":"textarea","field_name":"conditions"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'experience_content',
                'template_module'=>'experience certificate settings',
                'prompt'=> "Generate an experience letter for {employee_name}, who has served in the position of {designation} at {app_name}. Customize the letter by including the following details:Company Name: {app_name},Date: {date},To Whom It May Concern,This is to certify that {employee_name} has been employed with {app_name} for a duration of {duration}. During this tenure, {employee_name} has held the position of {designation} and has made valuable contributions to the organization.,Payroll Details:,Payroll: {payroll},Roles and Responsibilities:,{Briefly describe the employee's course of employment and highlight their key responsibilities and achievements.},We appreciate {employee_name}'s dedication, professionalism, and commitment to their work. They have been an asset to our organization and have consistently demonstrated their skills and expertise.,We wish {employee_name} continued success in their future endeavors.Sincerely,{employee_name}{designation}{app_name}",
                'field_json'=>'{"field":[{"label":"Company  Policy/Condition (comma seperated string)","placeholder":"e.g.leave,holiday,salary","field_type":"textarea","field_name":"conditions"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'noc_content',
                'template_module'=>'noc settings',
                'prompt'=> "Generate a No Objection Certificate (NoC) letter for {employee_name} who is requesting clearance to join and provide services to another organization. Customize the letter by filling in the appropriate details and placeholders enclosed in {}. Please structure the letter as per the mentioned format.Variables:{date}{employee_name}{app_name}{designation}",
                'field_json'=>'{"field":[{"label":"Company  Policy/Condition (comma seperated string)","placeholder":"e.g.leave,holiday,salary","field_type":"textarea","field_name":"conditions"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'company policy',
                'prompt'=> "Generate a suitable description for the company policy regarding ##title##. The description should be clear, concise, and informative, effectively conveying the purpose and scope of the policy. Please ensure that the description reflects the importance of ##title##. Aim to Generate a description that is professional, easily understandable, and aligned with the company's culture and values.",
                'field_json'=>'{"field":[{"label":"Title of policy","placeholder":"e.g.Leave policies,Performance management","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=> 0,
            ],
        ];
        foreach($defaultTemplate as $temp)
        {
            $check = AssistantTemplate::where('template_module',$temp['template_module'])->where('module','Hrm')->where('template_name',$temp['template_name'])->exists();
            if(!$check)
            {
                AssistantTemplate::create(
                    [
                        'template_name' => $temp['template_name'],
                        'template_module' => $temp['template_module'],
                        'module' => 'Hrm',
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
