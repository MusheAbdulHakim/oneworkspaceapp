<?php

namespace Modules\AIAssistant\Http\Controllers;

use App\Models\ApikeySetiings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\AIAssistant\Entities\AssistantTemplate;
use Orhanerday\OpenAi\OpenAi;

class AIAssistantController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('aiassistant::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($template_module,$module)
    {
        $templateName = AssistantTemplate::where('template_module', $template_module)->where('module', $module)->get();

        return view('aiassistant::ai.generate',compact('templateName'));
    }
    public function vcard_create_business($template_module,$module,$id)
    {
        $business_id=$id;
        $templateName = AssistantTemplate::where('template_module',$template_module)->where('module',$module)->get();
        return view('aiassistant::ai.generate_vcard_business',compact('templateName','business_id'));
    }
    public function vcard_create_service($template_module,$module,$id)
    {
        $serviceid=$id;
        $templateName = AssistantTemplate::where('template_module',$template_module)->where('module',$module)->get();
        return view('aiassistant::ai.generate_vcard_service',compact('templateName','serviceid'));
    }

    public function vcard_create_testimonial($template_module,$module,$id)
    {
        $testimonial_id=$id;
        $templateName = AssistantTemplate::where('template_module',$template_module)->where('module',$module)->get();
        return view('aiassistant::ai.generate_vcard_testimonial',compact('templateName','testimonial_id'));
    }

    public function cmms_create($template_module,$module)
    {
        $templateName = AssistantTemplate::where('template_module',$template_module)->where('module',$module)->get();

        return view('aiassistant::ai.cmms_generate',compact('templateName'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('aiassistant::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('aiassistant::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    public function GetKeywords(Request $request, $id)
    {

        $template = AssistantTemplate::find($id);
        $field_data = json_decode($template->field_json);

        $html = "";
        foreach ($field_data->field as  $value) {
            $html .= '<div class="form-group col-md-12">
                    <label class="form-label ">' . $value->label . '</label>';
            if ($value->field_type == "text_box") {

                $html .= '<input type="text" class="form-control" name="' . $value->field_name . '" value="" placeholder="' . $value->placeholder . '" required">';
            }
            if ($value->field_type == "textarea") {
                $html .= '<textarea type="text" rows=3 class="form-control " id="description" name="' . $value->field_name . '" placeholder="' . $value->placeholder . '" required></textarea>';
            }
            $html .= '</div>';
        }
        return response()->json(
            [
                'success' => true,
                'template' => $html,
                'tone'=>$template->is_tone
            ]
        );
    }
    public function AiGenerate(Request $request)
    {
        if ($request->ajax()) {

            $post = $request->all();

            unset($post['_token'], $post['template_name'], $post['tone'], $post['ai_creativity'], $post['num_of_result'], $post['result_length']);
            $data = array();

            $key_data= ApikeySetiings::inRandomOrder()->limit(1)->first();
            if(!empty($key_data))
            {
                $open_ai = new OpenAi($key_data->key);
            }
            else
            {
                $data['status'] = 'error';
                $data['message'] = __('Please set proper configuration for Api Key');
                return $data;
            }

            $prompt = '';
            $model = isset($settings['chatgpt_model']) ? $settings['chatgpt_model'] : 'gpt-3.5-turbo-instruct';
            $text = '';
            $ai_token = '';
            $counter = 1;


            $template = AssistantTemplate::where('id', $request->template_name)->first();

            if ($request->template_name)
            {

                $required_field = array();
                $data_field = json_decode($template->field_json);
                foreach ($data_field->field as  $val) {
                    $validator = \Validator::make(
                        $request->all(),
                        [
                            $val->field_name => 'required|string',
                        ]
                    );
                }
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    $data['status'] = 'error';
                    $data['message'] = $messages->first();
                    return $data;
                }

                $prompt = $template->prompt;
                foreach ($data_field->field as  $field) {

                    $text_rep = "##" . $field->field_name . "##";
                    if (strpos($prompt, $text_rep) !== false) {
                        $field->value = $post[$field->field_name];
                        $prompt = str_replace($text_rep, $post[$field->field_name], $prompt);
                    }
                    if ($template->is_tone == 1) {
                        $tone = $request->tone;
                        $param = "##tone_language##";
                        $prompt = str_replace($param, $tone, $prompt);
                    }
                }
            }
            $lang_text = "Provide response in " . $request->language . " language.\n\n ";
            $ai_token = (int)$request->result_length;

            $max_results = (int)$request->num_of_result;
            $ai_creativity = (float)$request->ai_creativity;
            $complete = $open_ai->completion([
                'model' => $model,
                'prompt' => $prompt . ' ' . $lang_text,
                'temperature' => $ai_creativity,
                'max_tokens' => $ai_token,
                'n' => $max_results
            ]);
            $response = json_decode($complete, true);
            if (isset($response['choices'])) {
                if (count($response['choices']) > 1) {
                    foreach ($response['choices'] as $value) {
                        $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
                        $counter++;
                    }
                } else {
                    $text = $response['choices'][0]['text'];
                }

                $tokens = $response['usage']['completion_tokens'];
                $data = trim($text);
                return $data;
            } else {
                $data['status'] = 'error';
                $data['message'] = __('Text was not generated due to Invalid API Key');
                return $data;
            }
        }
    }
}
