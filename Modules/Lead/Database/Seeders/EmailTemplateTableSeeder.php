<?php

namespace Modules\Lead\Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $emailTemplate = [
            'Deal Assigned',
            'Deal Moved',
            'New Task',
            'Lead Assigned',
            'Lead Moved',
        ];
        $defaultTemplate = [
            'Deal Assigned' => [
                'subject' => 'New Deal Assign',
                'variables' => '{
                    "Deal Name": "deal_name",
                    "Deal Pipeline": "deal_pipeline",
                    "Deal Stage": "deal_stage",
                    "Deal Status": "deal_status",
                    "Deal Price": "deal_price",
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name ":"company_name",
                    "Email" : "email"
                  }',
                'lang' => [
                    'ar' => '<p><span style="font-family: sans-serif;">مرحبا،</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">تم تعيين صفقة جديدة لك.</span></p><p><span style="font-family: sans-serif;"><b>اسم الصفقة</b> : {deal_name}<br><b>خط أنابيب الصفقة</b> : {deal_pipeline}<br><b>مرحلة الصفقة</b> : {deal_stage}<br><b>حالة الصفقة</b> : {deal_status}<br><b>سعر الصفقة</b> : {deal_price}</span></p>',
                    'da' => '<p><span style="font-family: sans-serif;">Hej,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal er blevet tildelt til dig.</span></p><p><span style="font-family: sans-serif;"><b>Deal Navn</b> : {deal_name}<br><b>Deal Pipeline</b> : {deal_pipeline}<br><b>Deal Fase</b> : {deal_stage}<br><b>Deal status</b> : {deal_status}<br><b>Deal pris</b> : {deal_price}</span></p>',
                    'de' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal wurde Ihnen zugewiesen.</span></p><p><span style="font-family: sans-serif;"><b>Geschäftsname</b> : {deal_name}<br><b>Deal Pipeline</b> : {deal_pipeline}<br><b>Deal Stage</b> : {deal_stage}<br><b>Deal Status</b> : {deal_status}<br><b>Ausgehandelter Preis</b> : {deal_price}</span></p>',
                    'en' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal has been Assign to you.</span></p><p><span style="font-family: sans-serif;"><b>Deal Name</b> : {deal_name}<br><b>Deal Pipeline</b> : {deal_pipeline}<br><b>Deal Stage</b> : {deal_stage}<br><b>Deal Status</b> : {deal_status}<br><b>Deal Price</b> : {deal_price}</span></p>',
                    'es' => '<p><span style="font-family: sans-serif;">Hola,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal ha sido asignado a usted.</span></p><p><span style="font-family: sans-serif;"><b>Nombre del trato</b> : {deal_name}<br><b>Tubería de reparto</b> : {deal_pipeline}<br><b>Etapa de reparto</b> : {deal_stage}<br><b>Estado del acuerdo</b> : {deal_status}<br><b>Precio de oferta</b> : {deal_price}</span></p>',
                    'fr' => '<p><span style="font-family: sans-serif;">Bonjour,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Le New Deal vous a été attribué.</span></p><p><span style="font-family: sans-serif;"><b>Nom de l`accord</b> : {deal_name}<br><b>Pipeline de transactions</b> : {deal_pipeline}<br><b>Étape de l`opération</b> : {deal_stage}<br><b>Statut de l`accord</b> : {deal_status}<br><b>Prix ​​de l  offre</b> : {deal_price}</span></p>',
                    'it' => '<p><span style="font-family: sans-serif;">Ciao,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal è stato assegnato a te.</span></p><p><span style="font-family: sans-serif;"><b>Nome dell`affare</b> : {deal_name}<br><b>Pipeline di offerte</b> : {deal_pipeline}<br><b>Stage Deal</b> : {deal_stage}<br><b>Stato dell`affare</b> : {deal_status}<br><b>Prezzo dell`offerta</b> : {deal_price}</span></p>',
                    'ja' => '<p><span style="font-family: sans-serif;">こんにちは、</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">新しい取引が割り当てられました。</span></p><p><span style="font-family: sans-serif;"><b>取引名</b> : {deal_name}<br><b>取引パイプライン</b> : {deal_pipeline}<br><b>取引ステージ</b> : {deal_stage}<br><b>取引状況</b> : {deal_status}<br><b>取引価格</b> : {deal_price}</span></p>',
                    'nl' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal is aan u toegewezen.</span></p><p><span style="font-family: sans-serif;"><b>Dealnaam</b> : {deal_name}<br><b>Deal Pipeline</b> : {deal_pipeline}<br><b>Deal Stage</b> : {deal_stage}<br><b>Dealstatus</b> : {deal_status}<br><b>Deal prijs</b> : {deal_price}</span></p>',
                    'pl' => '<p><span style="font-family: sans-serif;">Witaj,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Nowa oferta została Ci przypisana.</span></p><p><span style="font-family: sans-serif;"><b>Nazwa oferty</b> : {deal_name}<br><b>Deal Pipeline</b> : {deal_pipeline}<br><b>Etap transakcji</b> : {deal_stage}<br><b>Status oferty</b> : {deal_status}<br><b>Cena oferty</b> : {deal_price}</span></p>',
                    'ru' => '<p><span style="font-family: sans-serif;">Привет,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Новый курс был назначен вам.</span></p><p><span style="font-family: sans-serif;"><b>Название сделки</b> : {deal_name}<br><b>Трубопровод сделки</b> : {deal_pipeline}<br><b>Этап сделки</b> : {deal_stage}<br><b>Статус сделки</b> : {deal_status}<br><b>Цена сделки</b> : {deal_price}</span></p>',
                    'pt' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal has been Assign to you.</span></p><p><span style="font-family: sans-serif;"><b>Deal Name</b> : {deal_name}<br><b>Deal Pipeline</b> : {deal_pipeline}<br><b>Deal Stage</b> : {deal_stage}<br><b>Deal Status</b> : {deal_status}<br><b>Deal Price</b> : {deal_price}</span></p>',
                ],
            ],
            'Deal Moved' => [
                'subject' => 'Deal has been Moved',
                'variables' => '{
                    "Deal Name": "deal_name",
                    "Deal Pipeline": "deal_pipeline",
                    "Deal Stage": "deal_stage",
                    "Deal Status": "deal_status",
                    "Deal Price": "deal_price",
                    "Deal Old Stage": "deal_old_stage",
                    "Deal New Stage": "deal_new_stage",
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name ":"company_name",
                    "Email" : "email",
                    "Password" : "password"
                  }',
                'lang' => [
                    'ar' => '<p><span style="font-family: sans-serif;">مرحبا،</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">تم نقل صفقة من {deal_old_stage} إلى&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">اسم الصفقة</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">خط أنابيب الصفقة</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">مرحلة الصفقة</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">حالة الصفقة</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">سعر الصفقة</span>&nbsp;: {deal_price}</span></p>',
                    'da' => '<p><span style="font-family: sans-serif;">Hej,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">En aftale er flyttet fra {deal_old_stage} til&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Deal Navn</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Deal Fase</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Deal status</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Deal pris</span>&nbsp;: {deal_price}</span></p>',
                    'de' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Ein Deal wurde verschoben {deal_old_stage} zu&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Geschäftsname</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Deal Stage</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Deal Status</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Ausgehandelter Preis</span>&nbsp;: {deal_price}</span></p>',
                    'en' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">A Deal has been move from {deal_old_stage} to&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Deal Name</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Deal Stage</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Deal Status</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Deal Price</span>&nbsp;: {deal_price}</span></p>',
                    'es' => '<p><span style="font-family: sans-serif;">Hola,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Se ha movido un acuerdo de {deal_old_stage} a&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Nombre del trato</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Tubería de reparto</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Etapa de reparto</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Estado del acuerdo</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Precio de oferta</span>&nbsp;: {deal_price}</span></p>',
                    'fr' => '<p><span style="font-family: sans-serif;">Bonjour,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Un accord a été déplacé de {deal_old_stage} à&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Nom de l`accord</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Pipeline de transactions</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Étape de l`opération</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Statut de l`accord</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Prix ​​de l`offre</span>&nbsp;: {deal_price}</span></p>',
                    'it' => '<p><span style="font-family: sans-serif;">Ciao,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Un affare è stato spostato da {deal_old_stage} per&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Nome dell`affare</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Pipeline di offerte</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Stage Deal</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Stato dell`affare</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Prezzo dell`offerta</span>&nbsp;: {deal_price}</span></p>',
                    'ja' => '<p><span style="font-family: sans-serif;">こんにちは、</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">取引は {deal_old_stage} に&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">取引名</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">取引パイプライン</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">取引ステージ</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">取引状況</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">取引価格</span>&nbsp;: {deal_price}</span></p>',
                    'nl' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Een deal is verplaatst van {deal_old_stage} naar&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Dealnaam</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Deal Stage</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Dealstatus</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Deal prijs</span>&nbsp;: {deal_price}</span></p>',
                    'pl' => '<p><span style="font-family: sans-serif;">Witaj,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Umowa została przeniesiona {deal_old_stage} do&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Nazwa oferty</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Etap transakcji</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Status oferty</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Cena oferty</span>&nbsp;: {deal_price}</span></p>',
                    'ru' => '<p><span style="font-family: sans-serif;">Привет,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Сделка была перемещена из {deal_old_stage} в&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Название сделки</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Трубопровод сделки</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Этап сделки</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Статус сделки</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Цена сделки</span>&nbsp;: {deal_price}</span></p>',
                    'pt' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">A Deal has been move from {deal_old_stage} to&nbsp; {deal_new_stage}.</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Deal Name</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Deal Stage</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Deal Status</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Deal Price</span>&nbsp;: {deal_price}</span></p>',
                ],
            ],
            'New Task' => [
                'subject' => 'New Task Assign',
                'variables' => '{
                    "Task Name": "task_name",
                    "Task Priority": "task_priority",
                    "Task Status": "task_status",
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name ":"company_name",
                    "Email" : "email",
                    "Password" : "password"
                  }',
                'lang' => [
                    'ar' => '<p><span style="font-family: sans-serif;">مرحبا،</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">تم تعيين مهمة جديدة لك.</span></p><p><span style="font-family: sans-serif;"><b>اسم المهمة</b> : {task_name}<br><b>أولوية المهمة</b> : {task_priority}<br><b>حالة المهمة</b> : {task_status}<br><b>صفقة المهمة</b> : {deal_name}</span></p>',
                    'da' => '<p><span style="font-family: sans-serif;">Hej,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Ny opgave er blevet tildelt til dig.</span></p><p><span style="font-family: sans-serif;"><b>Opgavens navn</b> : {task_name}<br><b>Opgaveprioritet</b> : {task_priority}<br><b>Opgavestatus</b> : {task_status}<br><b>Opgave</b> : {deal_name}</span></p>',
                    'de' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Neue Aufgabe wurde Ihnen zugewiesen.</span></p><p><span style="font-family: sans-serif;"><b>Aufgabennname</b> : {task_name}<br><b>Aufgabenpriorität</b> : {task_priority}<br><b>Aufgabenstatus</b> : {task_status}<br><b>Task Deal</b> : {deal_name}</span></p>',
                    'en' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Task has been Assign to you.</span></p><p><span style="font-family: sans-serif;"><b>Task Name</b> : {task_name}<br><b>Task Priority</b> : {task_priority}<br><b>Task Status</b> : {task_status}<br><b>Task Deal</b> : {deal_name}</span></p>',
                    'es' => '<p><span style="font-family: sans-serif;">Hola,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Nueva tarea ha sido asignada a usted.</span></p><p><span style="font-family: sans-serif;"><b>Nombre de la tarea</b> : {task_name}<br><b>Prioridad de tarea</b> : {task_priority}<br><b>Estado de la tarea</b> : {task_status}<br><b>Reparto de tarea</b> : {deal_name}</span></p>',
                    'fr' => '<p><span style="font-family: sans-serif;">Bonjour,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Une nouvelle tâche vous a été assignée.</span></p><p><span style="font-family: sans-serif;"><b>Nom de la tâche</b> : {task_name}<br><b>Priorité des tâches</b> : {task_priority}<br><b>Statut de la tâche</b> : {task_status}<br><b>Deal Task</b> : {deal_name}</span></p>',
                    'it' => '<p><span style="font-family: sans-serif;">Ciao,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">La nuova attività è stata assegnata a te.</span></p><p><span style="font-family: sans-serif;"><b>Nome dell`attività</b> : {task_name}<br><b>Priorità dell`attività</b> : {task_priority}<br><b>Stato dell`attività</b> : {task_status}<br><b>Affare</b> : {deal_name}</span></p>',
                    'ja' => '<p><span style="font-family: sans-serif;">こんにちは、</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">新しいタスクが割り当てられました。</span></p><p><span style="font-family: sans-serif;"><b>タスク名</b> : {task_name}<br><b>タスクの優先度</b> : {task_priority}<br><b>タスクのステータス</b> : {task_status}<br><b>タスク取引</b> : {deal_name}</span></p>',
                    'nl' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Nieuwe taak is aan u toegewezen.</span></p><p><span style="font-family: sans-serif;"><b>Opdrachtnaam</b> : {task_name}<br><b>Taakprioriteit</b> : {task_priority}<br><b>Taakstatus</b> : {task_status}<br><b>Task Deal</b> : {deal_name}</span></p>',
                    'pl' => '<p><span style="font-family: sans-serif;">Witaj,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Nowe zadanie zostało Ci przypisane.</span></p><p><span style="font-family: sans-serif;"><b>Nazwa zadania</b> : {task_name}<br><b>Priorytet zadania</b> : {task_priority}<br><b>Status zadania</b> : {task_status}<br><b>Zadanie Deal</b> : {deal_name}</span></p>',
                    'ru' => '<p><span style="font-family: sans-serif;">Привет,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Новая задача была назначена вам.</span></p><p><span style="font-family: sans-serif;"><b>Название задачи</b> : {task_name}<br><b>Приоритет задачи</b> : {task_priority}<br><b>Состояние задачи</b> : {task_status}<br><b>Задача</b> : {deal_name}</span></p>',
                    'pt' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Task has been Assign to you.</span></p><p><span style="font-family: sans-serif;"><b>Task Name</b> : {task_name}<br><b>Task Priority</b> : {task_priority}<br><b>Task Status</b> : {task_status}<br><b>Task Deal</b> : {deal_name}</span></p>',
                ],
            ],
            'Lead Assigned' => [
                'subject' => 'New Lead Assign',
                'variables' => '{
                    "Lead Name": "lead_name",
                    "Lead Email": "lead_email",
                    "Lead Pipeline": "lead_pipeline",
                    "Lead Stage": "lead_stage",
                    "Lead Old Stage": "lead_old_stage",
                    "Lead New Stage": "lead_new_stage",
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name ":"company_name",
                    "Email" : "email",
                    "Password" : "password"
                }',
                'lang' => [
                    'ar' => '<p><span style="font-family: sans-serif;">مرحبا،</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">تم تعيين عميل جديد لك.</span></p><p><span style="font-family: sans-serif;"><b>اسم العميل المحتمل</b> : {lead_name}<br><b>البريد الإلكتروني الرئيسي</b> : {lead_email}<br><b>خط أنابيب الرصاص</b> : {lead_pipeline}<br><b>مرحلة الرصاص</b> : {lead_stage}</span></p>',
                    'da' => '<p><span style="font-family: sans-serif;">Hej,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Ny bly er blevet tildelt dig.</span></p><p><span style="font-family: sans-serif;"><b>Blynavn</b> : {lead_name}<br><b>Lead-e-mail</b> : {lead_email}<br><b>Blyrørledning</b> : {lead_pipeline}<br><b>Lead scenen</b> : {lead_stage}</span></p>',
                    'de' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Neuer Lead wurde Ihnen zugewiesen.</span></p><p><span style="font-family: sans-serif;"><b>Lead Name</b> : {lead_name}<br><b>Lead-E-Mail</b> : {lead_email}<br><b>Lead Pipeline</b> : {lead_pipeline}<br><b>Lead Stage</b> : {lead_stage}</span></p>',
                    'en' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Lead has been Assign to you.</span></p><p><span style="font-family: sans-serif;"><b>Lead Name</b> : {lead_name}<br><b>Lead Email</b> : {lead_email}<br><b>Lead Pipeline</b> : {lead_pipeline}<br><b>Lead Stage</b> : {lead_stage}</span></p>',
                    'es' => '<p><span style="font-family: sans-serif;">Hola,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Se le ha asignado un nuevo plomo.</span></p><p><span style="font-family: sans-serif;"><b>Nombre principal</b> : {lead_name}<br><b>Correo electrónico principal</b> : {lead_email}<br><b>Tubería de plomo</b> : {lead_pipeline}<br><b>Etapa de plomo</b> : {lead_stage}</span></p>',
                    'fr' => '<p><span style="font-family: sans-serif;">Bonjour,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Un nouveau prospect vous a été attribué.</span></p><p><span style="font-family: sans-serif;"><b>Nom du responsable</b> : {lead_name}<br><b>Courriel principal</b> : {lead_email}<br><b>Pipeline de plomb</b> : {lead_pipeline}<br><b>Étape principale</b> : {lead_stage}</span></p>',
                    'it' => '<p><span style="font-family: sans-serif;">Ciao,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Lead è stato assegnato a te.</span></p><p><span style="font-family: sans-serif;"><b>Nome del lead</b> : {lead_name}<br><b>Lead Email</b> : {lead_email}<br><b>Conduttura di piombo</b> : {lead_pipeline}<br><b>Lead Stage</b> : {lead_stage}</span></p>',
                    'ja' => '<p><span style="font-family: sans-serif;">こんにちは、</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">新しいリードが割り当てられました。</span></p><p><span style="font-family: sans-serif;"><b>リード名</b> : {lead_name}<br><b>リードメール</b> : {lead_email}<br><b>リードパイプライン</b> : {lead_pipeline}<br><b>リードステージ</b> : {lead_stage}</span></p>',
                    'nl' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Nieuwe lead is aan u toegewezen.</span></p><p><span style="font-family: sans-serif;"><b>Lead naam</b> : {lead_name}<br><b>E-mail leiden</b> : {lead_email}<br><b>Lead Pipeline</b> : {lead_pipeline}<br><b>Hoofdfase</b> : {lead_stage}</span></p>',
                    'pl' => '<p><span style="font-family: sans-serif;">Witaj,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Nowy potencjalny klient został do ciebie przypisany.</span></p><p><span style="font-family: sans-serif;"><b>Imię i nazwisko</b> : {lead_name}<br><b>Główny adres e-mail</b> : {lead_email}<br><b>Ołów rurociągu</b> : {lead_pipeline}<br><b>Etap prowadzący</b> : {lead_stage}</span></p>',
                    'ru' => '<p><span style="font-family: sans-serif;">Привет,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Новый Лид был назначен вам.</span></p><p><span style="font-family: sans-serif;"><b>Имя лидера</b> : {lead_name}<br><b>Ведущий Email</b> : {lead_email}<br><b>Ведущий трубопровод</b> : {lead_pipeline}<br><b>Ведущий этап</b> : {lead_stage}</span></p>',
                    'pt' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Lead has been Assign to you.</span></p><p><span style="font-family: sans-serif;"><b>Lead Name</b> : {lead_name}<br><b>Lead Email</b> : {lead_email}<br><b>Lead Pipeline</b> : {lead_pipeline}<br><b>Lead Stage</b> : {lead_stage}</span></p>',
                ],
            ],
            'Lead Moved' => [
                'subject' => 'Lead has been Moved',
                'variables' => '{
                    "Lead Name": "lead_name",
                    "Lead Email": "lead_email",
                    "Lead Pipeline": "lead_pipeline",
                    "Lead Stage": "lead_stage",
                    "Lead Old Stage": "lead_old_stage",
                    "Lead New Stage": "lead_new_stage",
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name ":"company_name",
                    "Email" : "email",
                    "Password" : "password"
                  }',
                'lang' => [
                    'ar' => '<p><span style="font-family: sans-serif;">مرحبا،</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">تم نقل العميل المحتمل من {lead_old_stage} إلى&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">اسم العميل المحتمل</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">البريد الإلكتروني الرئيسي</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">خط أنابيب الرصاص</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">مرحلة الرصاص</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'da' => '<p><span style="font-family: sans-serif;">Hej,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">En leder er flyttet fra {lead_old_stage} til&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Blynavn</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead-e-mail</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Blyrørledning</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead scenen</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'de' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Ein Lead wurde verschoben von {lead_old_stage} zu&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Lead Name</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead-E-Mail</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Pipeline</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Stage</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'en' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">A Lead has been move from {lead_old_stage} to&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Lead Name</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Email</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Pipeline</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Stage</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'es' => '<p><span style="font-family: sans-serif;">Hola,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Un plomo ha sido movido de {lead_old_stage} a&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Nombre principal</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Correo electrónico principal</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Tubería de plomo</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Etapa de plomo</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'fr' => '<p><span style="font-family: sans-serif;">Bonjour,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Un lead a été déplacé de {lead_old_stage} à&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Nom du responsable</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Courriel principal</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Pipeline de plomb</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Étape principale</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'it' => '<p><span style="font-family: sans-serif;">Ciao,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">È stato spostato un lead {lead_old_stage} per&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Nome del lead</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Email</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Conduttura di piombo</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Stage</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'ja' => '<p><span style="font-family: sans-serif;">こんにちは、</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">リードが移動しました {lead_old_stage} に&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">リード名</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">リードメール</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">リードパイプライン</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">リードステージ</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'nl' => '<p><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Er is een lead verplaatst van {lead_old_stage} naar&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Lead naam</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">E-mail leiden</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Pipeline</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Hoofdfase</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'pl' => '<p><span style="font-family: sans-serif;">Witaj,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Prowadzenie zostało przeniesione {lead_old_stage} do&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Imię i nazwisko</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Główny adres e-mail</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Ołów rurociągu</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Etap prowadzący</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'ru' => '<p><span style="font-family: sans-serif;">Привет,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Свинец был двигаться от {lead_old_stage} в&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Имя лидера</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Ведущий Email</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Ведущий трубопровод</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Ведущий этап</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                    'pt' => '<p><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">A Lead has been move from {lead_old_stage} to&nbsp; {lead_new_stage}.</span></p><p><span style="font-weight: bolder; font-family: sans-serif;">Lead Name</span><span style="font-family: sans-serif;">&nbsp;: {lead_name}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Email</span><span style="font-family: sans-serif;">&nbsp;: {lead_email}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Pipeline</span><span style="font-family: sans-serif;">&nbsp;: {lead_pipeline}</span><br style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: sans-serif;">Lead Stage</span><span style="font-family: sans-serif;">&nbsp;: {lead_stage}</span><span style="font-family: sans-serif;"><br></span></p>',
                ],
            ],
        ];
        foreach($emailTemplate as $eTemp)
        {
            $table = EmailTemplate::where('name',$eTemp)->where('module_name','Lead')->exists();
            if(!$table)
            {
                $emailtemplate=  EmailTemplate::create(
                    [
                    'name' => $eTemp,
                    'from' => 'Lead',
                    'module_name' => 'Lead',
                    'created_by' => 1,
                    'workspace_id' => 0
                    ]
                );
                foreach($defaultTemplate[$eTemp]['lang'] as $lang => $content)
                {
                    EmailTemplateLang::create(
                        [
                            'parent_id' => $emailtemplate->id,
                            'lang' => $lang,
                            'subject' => $defaultTemplate[$eTemp]['subject'],
                            'variables' => $defaultTemplate[$eTemp]['variables'],
                            'content' => $content,
                        ]
                    );
                }
            }
        }
    }
}
