<?php

namespace App\Models;

use App\Mail\CommonEmailTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'from',
        'module_name',
        'created_by',
        'workspace_id'
    ];

    public function template()
    {
        return $this->hasOne('App\Models\UserEmailTemplate', 'template_id', 'id')->where('user_id', '=', \Auth::user()->id);
    }

    public static function sendEmailTemplate($emailTemplate, $mailTo, $obj, $user_id = null, $workspace_id = null)
    {
        if (!empty($user_id)) {
            $usr = User::where('id', $user_id)->first();
        } else {
            $usr = Auth::user();
        }

        // unset($mailTo[$usr->id]);
        //Remove Current Login user Email don't send mail to them

        $mailTo = array_values($mailTo);

        // if($usr->type != 'super admin')
        // {

        // find template is exist or not in our record
        $template = EmailTemplate::where('name', $emailTemplate)->first();

        if (isset($template) && !empty($template)) {
            // get email content language base
            $content = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $usr->lang)->first();
            
            $content->from = $template->from;
            
            if (!empty($content->content)) {
                $content->content = self::replaceVariable($content->content, $obj);

               
                // send email
                if (!empty(company_setting('mail_from_address', $user_id, $workspace_id))) {

                    if (!empty($user_id) && empty($workspace_id)) {
                        $setconfing =  SetConfigEmail($user_id);
                    } elseif (!empty($user_id) && !empty($workspace_id)) {
                        $setconfing =  SetConfigEmail($user_id, $workspace_id);
                    } else {
                        $setconfing =  SetConfigEmail();
                    }
                    if ($setconfing ==  true) {
                        try {
                            Mail::to($mailTo)->send(new CommonEmailTemplate($content, $user_id, $workspace_id));
                        } catch (\Exception $e) {
                            $error = $e->getMessage();
                        }
                    } else {
                        $error = __('Something went wrong please try again ');
                    }
                } else {
                    $error = __('E-Mail has been not sent due to SMTP configuration');
                }

                if (isset($error)) {
                    $arReturn = [
                        'is_success' => false,
                        'error' => $error,
                    ];
                } else {
                    $arReturn = [
                        'is_success' => true,
                        'error' => false,
                    ];
                }
            } else {
                $arReturn = [
                    'is_success' => false,
                    'error' => __('Mail not send, email is empty'),
                ];
            }
            return $arReturn;
        } else {
            return [
                'is_success' => false,
                'error' => __('Mail not send, email not found'),
            ];
        }
        // }
    }
    public static function replaceVariable($content, $obj)
    {
        $arrVariable = [
            '{app_name}',
            '{app_url}',
            '{company_name}',

            '{email}',
            '{password}',

            '{leave_status_name}',
            '{leave_status}',
            '{leave_reason}',
            '{leave_start_date}',
            '{leave_end_date}',
            '{total_leave_days}',

            '{award_name}',
            '{award_date}',
            '{award_type}',

            '{transfer_name}',
            '{transfer_date}',
            '{transfer_branch}',
            '{transfer_department}',
            '{transfer_description}',

            '{assign_user}',
            '{resignation_date}',
            '{notice_date}',

            '{employee_trip_name}',
            '{purpose_of_visit}',
            '{start_date}',
            '{end_date}',
            '{place_of_visit}',
            '{trip_description}',

            '{employee_promotion_name}',
            '{promotion_designation}',
            '{promotion_title}',
            '{promotion_date}',

            '{employee_complaints_name}',
            '{complaints_description}',

            '{employee_warning_name}',
            '{warning_subject}',
            '{warning_description}',

            '{employee_termination_name}',
            '{termination_date}',
            '{termination_type}',

            '{name}',
            '{payslip_email}',
            '{salary_month}',
            '{url}',

            '{bill_name}',
            '{bill_number}',
            '{bill_url}',

            '{payment_name}',
            '{payment_bill}',
            '{payment_amount}',
            '{payment_date}',
            '{payment_method}',

            '{invoice_name}',
            '{invoice_number}',
            '{invoice_url}',
            '{pay_invoice_url}',

            '{payment_dueAmount}',

            '{meeting_assign_user}',
            '{meeting_name}',
            '{meeting_start_date}',
            '{meeting_due_date}',
            '{description}',
            '{attendees_contact}',

            '{quote_number}',
            '{billing_address}',
            '{shipping_address}',
            '{date_quoted}',
            '{quote_assign_user}',

            '{salesorder_assign_user}',

            '{invoice_id}',
            '{invoice_client}',
            '{invoice_status}',
            '{invoice_sub_total}',
            '{created_at}',

            '{invoice_recivername}',
            '{salesinvoice_number}',
            '{salesinvoice_url}',

            '{ticket_name}',
            '{ticket_id}',
            '{reply_description}',
            '{ticket_url}',

            '{contract_subject}',
            '{contract_client}',
            '{contract_start_date}',
            '{contract_end_date}',

            '{deal_name}',
            '{deal_pipeline}',
            '{deal_stage}',
            '{deal_status}',
            '{deal_price}',
            '{deal_old_stage}',
            '{deal_new_stage}',


            '{task_name}',
            '{task_priority}',
            '{task_status}',

            '{lead_name}',
            '{lead_email}',
            '{lead_pipeline}',
            '{lead_stage}',
            '{lead_old_stage}',
            '{lead_new_stage}',

            '{proposal_name}',
            '{proposal_number}',
            '{proposal_url}',

            '{retainer_name}',
            '{retainer_number}',
            '{retainer_url}',
            '{payment_retainer}',

            '{revenue_type}',

            '{purchase_name}',
            '{purchase_number}',
            '{purchase_url}',

            '{appointment_status_name}',
            '{appointment_status}',
            '{appointment_date}',
            '{appointment_start_time}',
            '{appointment_end_time}',
            '{appointment_join_url}',

            '{appointment_user_name}',
            '{appointment_user_email}',
            '{appointment_unique_id}',

            '{work_order_id}',
            '{components}',
            '{priority}',
            '{work_order_due_date}',

            '{contact}',

            '{supplier}',
            '{items}',
            '{quantity}',
            '{price}',
            '{purchase_order_date}',
            '{expected_delivery_date}',
            '{pos_description}',

            '{work_request_name}',
            '{problem}',
            '{instruction}',

            '{appointment_name}',
            '{appointment_email}',
            '{appointment_phone}',
            '{appointment_time}',

            '{vehicle_name}',
            '{driver_name}',
            '{vehicle_type}',
            '{customer_name}',
            '{total_price}',
            '{pay_amount}',
            '{total_payment}',

            '{student_name}',
            '{course_name}',
            '{store_name}',
            '{order_url}',

            '{hotel_email}',
            '{hotel_name}',
            '{hotel_contact}',
            '{hotel_customer_email}',
            '{hotel_customer_password}',
            '{invoice_customer}',
            '{invoice_payment_status}',
            '{hotel_customer_name}',
            '{payment_status}',
            '{room_booking_id}',
            '{check_in_date}',
            '{check_out_date}',
            '{room_type}',

            '{doctor_name}',
            '{doctor_email}',
            '{doctor_password}',

            '{patient_name}',
            '{patient_email}',
            '{patient_password}',

            '{bed_number}',
            '{ward_name}',
            '{admission_date}',

            '{invoice_tenant}',

            '{inquiry_status}',
            '{login_link}',
            '{inquiry_user_name}',
            '{parent_email}',
            '{inquiry_unique_id}',

            '{inspector_name}',

            '{employee_name}',

            '{tracking_id}',
            '{tracking_url}',

            '{service_id}',
            '{tracking_url}',

            '{package_name}',
        ];
        $arrValue    = [
            'app_name' => '-',
            'app_url' => '-',
            'company_name' => '-',
            'email' => '-',
            'password' => '-',

            'leave_status_name' => '-',
            'leave_status' => '-',
            'leave_reason' => '-',
            'leave_start_date' => '-',
            'leave_end_date' => '-',
            'total_leave_days' => '-',

            'award_name' => '-',
            'award_date' => '-',
            'award_type' => '-',

            'transfer_name' => '-',
            'transfer_date' => '-',
            'transfer_branch' => '-',
            'transfer_department' => '-',
            'transfer_description' => '-',

            'assign_user' => '-',
            'resignation_date' => '-',
            'notice_date' => '-',

            'employee_trip_name' => '-',
            'purpose_of_visit' => '-',
            'start_date' => '-',
            'end_date' => '-',
            'place_of_visit' => '-',
            'trip_description' => '-',

            'employee_promotion_name' => '-',
            'promotion_designation' => '-',
            'promotion_title' => '-',
            'promotion_date' => '-',

            'employee_complaints_name' => '-',
            'complaints_description' => '-',

            'employee_warning_name' => '-',
            'warning_subject' => '-',
            'warning_description' => '-',

            'employee_termination_name' => '-',
            'termination_date' => '-',
            'termination_type' => '-',

            'name' => '-',
            'payslip_email' => '-',
            'salary_month' => '-',
            'url' => '-',

            'bill_name' => '-',
            'bill_number' => '-',
            'bill_url' => '-',

            'payment_name' => '-',
            'payment_bill' => '-',
            'payment_amount' => '-',
            'payment_date' => '-',
            'payment_method' => '-',

            'invoice_name' => '-',
            'invoice_number' => '-',
            'invoice_url' => '-',
            'pay_invoice_url' => '-',

            'payment_dueAmount' => '-',

            'meeting_assign_user' => '-',
            'meeting_name' => '-',
            'meeting_start_date' => '-',
            'meeting_due_date' => '-',
            'description' => '-',
            'attendees_contact' => '-',

            'quote_number' => '-',
            'billing_address' => '-',
            'shipping_address' => '-',
            'date_quoted' => '-',
            'quote_assign_user' => '-',

            'salesorder_assign_user' => '-',

            'invoice_id' => '-',
            'invoice_client' => '-',
            'invoice_status' => '-',
            'invoice_sub_total' => '-',
            'created_at' => '-',

            'invoice_recivername' => '-',
            'salesinvoice_number' => '-',
            'salesinvoice_url' => '-',

            'ticket_name' => '-',
            'ticket_id' => '-',
            'reply_description' => '-',
            'ticket_url' => '-',

            'contract_subject' => '-',
            'contract_client' => '-',
            'contract_start_date' => '-',
            'contract_end_date' => '-',

            'deal_name' => '-',
            'deal_pipeline' => '-',
            'deal_stage' => '-',
            'deal_status' => '-',
            'deal_price' => '-',
            'deal_old_stage' => '-',
            'deal_new_stage' => '-',

            'task_name' => '-',
            'task_priority' => '-',
            'task_status' => '-',

            'lead_name' => '-',
            'lead_email' => '-',
            'lead_pipeline' => '-',
            'lead_stage' => '-',
            'lead_old_stage' => '-',
            'lead_new_stage' => '-',

            'proposal_name' => '-',
            'proposal_number' => '-',
            'proposal_url' => '-',

            'retainer_name' => '-',
            'retainer_number' => '-',
            'retainer_url' => '-',
            'payment_retainer' => '-',

            'revenue_type' => '-',

            'purchase_name' => '-',
            'purchase_number' => '-',
            'purchase_url' => '-',

            'appointment_status_name' => '-',
            'appointment_status' => '-',
            'appointment_date' => '-',
            'appointment_start_time' => '-',
            'appointment_end_time' => '-',
            'appointment_join_url' => '-',

            'appointment_user_name' => '-',
            'appointment_user_email' => '-',
            'appointment_unique_id' => '-',

            'work_order_id' => '-',
            'components' => '-',
            'priority' => '-',
            'work_order_due_date' => '-',

            'contact' => '-',

            'supplier' => '-',
            'items' => '-',
            'quantity' => '-',
            'price' => '-',
            'purchase_order_date' => '-',
            'expected_delivery_date' => '-',
            'pos_description' => '',

            'work_request_name' => '-',
            'problem' => '-',
            'instruction' => '-',

            'appointment_name' => '-',
            'appointment_email' => '-',
            'appointment_phone' => '-',
            'appointment_time' => '-',

            'vehicle_name' => '-',
            'driver_name' => '-',
            'vehicle_type' => '-',
            'customer_name' => '-',
            'total_price' => '-',
            'pay_amount' => '-',
            'total_payment' => '-',

            'student_name' => '-',
            'course_name' => '-',
            'store_name' => '-',
            'order_url' => '-',

            'hotel_email' => '-',
            'hotel_name' => '-',
            'hotel_contact' => '-',
            'hotel_customer_email' => '-',
            'hotel_customer_password' => '-',
            'invoice_customer' => '-',
            'invoice_payment_status' => '-',
            'hotel_customer_name' => '-',
            'payment_status' => '-',
            'room_booking_id' => '-',
            'check_in_date' => '-',
            'check_out_date' => '-',
            'room_type' => '-',

            'doctor_name' => '-',
            'doctor_email' => '-',
            'doctor_password' => '-',

            'patient_name' => '-',
            'patient_email' => '-',
            'patient_password' => '-',

            'bed_number' => '-',
            'ward_name' => '-',
            'admission_date' => '-',

            'invoice_tenant' => '-',

            'inquiry_status' => '-',
            'login_link' => '-',
            'inquiry_user_name' => '-',
            'parent_email' => '-',
            'inquiry_unique_id' => '-',

            'inspector_name' => '-',

            'employee_name' => '-',

            'tracking_id' => '-',
            'tracking_url' => '-',

            'service_id' => '-',
            'tracking_url' => '-',

            'package_name' => '-',
        ];
        foreach ($obj as $key => $val) {

            $arrValue[$key] = $val;
        }
        $arrValue['app_name']     = env('APP_NAME');
        $arrValue['company_name'] = (isset($arrValue['company_name']) && !empty($arrValue['company_name'])) ? $arrValue['company_name'] : '--';
        $arrValue['app_url']      = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';
        return str_replace($arrVariable, array_values($arrValue), $content);
    }

    public static $email_settings = [
        "custom" => "Custom",
        "smtp" => "SMTP",
        "gmail" => "Gmail",
        "outlook" => "Outlook/Office 365",
        "yahoo" => "Yahoo",
        "sendgrid" => "SendGrid",
        "amazon" => "Amazon SES ",
        "mailgun" => "Mailgun",
        "smtp.com" => "SMTP.com",
        "zohomail" => "Zoho Mail",
        "mandrill" => "Mandrill",
        "mailtrap" => "Mailtrap",
        "sparkpost" => "SparkPost"
    ];
}
