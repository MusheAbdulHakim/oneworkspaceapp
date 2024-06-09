<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplates extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emailTemplate = [
            'New User',
            'Customer Invoice Send',
            'Payment Reminder',
            'Invoice Payment Create',
            'Proposal Send',
            'New Helpdesk Ticket',
            'New Helpdesk Ticket Reply',
            'Purchase Send',
            'Purchase Payment Create',
        ];

        $defaultTemplate = [
            'New User' => [
                'subject' => 'Login Detail',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Email": "email",
                    "Password": "password"
                  }',
                  'lang' => [
                    'ar' => '<p>مرحبا،&nbsp;<br>مرحبا بك في {app_name}.</p><p><b>البريد الإلكتروني </b>: {email}<br><b>كلمه السر</b> : {password}</p><p>{app_url}</p><p>شكر،<br>{app_name}</p>',
                    'da' => '<p>Hej,&nbsp;<br>Velkommen til {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Adgangskode</b> : {password}</p><p>{app_url}</p><p>Tak,<br>{app_name}</p>',
                    'de' => '<p>Hallo,&nbsp;<br>Willkommen zu {app_name}.</p><p><b>Email </b>: {email}<br><b>Passwort</b> : {password}</p><p>{app_url}</p><p>Vielen Dank,<br>{app_name}</p>',
                    'en' => '<p>Hello,&nbsp;<br />Welcome to {app_name}</p>
                    <p><strong>Email </strong>: {email}<br /><strong>Password</strong> : {password}</p>
                    <p>{app_url}</p>
                    <p>Thanks,<br />{app_name}</p>',
                    'es' => '<p>Hola,&nbsp;<br>Bienvenido a {app_name}.</p><p><b>Correo electrónico </b>: {email}<br><b>Contraseña</b> : {password}</p><p>{app_url}</p><p>Gracias,<br>{app_name}</p>',
                    'fr' => '<p>Bonjour,&nbsp;<br>Bienvenue à {app_name}.</p><p><b>Email </b>: {email}<br><b>Mot de passe</b> : {password}</p><p>{app_url}</p><p>Merci,<br>{app_name}</p>',
                    'it' => "<p>Ciao,&nbsp;<br>Benvenuto a {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Parola d'ordine</b> : {password}</p><p>{app_url}</p><p>Grazie,<br>{app_name}</p>",
                    'ja' => '<p>こんにちは、&nbsp;<br>へようこそ {app_name}.</p><p><b>Eメール </b>: {email}<br><b>パスワード</b> : {password}</p><p>{app_url}</p><p>おかげで、<br>{app_name}</p>',
                    'nl' => '<p>Hallo,&nbsp;<br>Welkom bij {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Wachtwoord</b> : {password}</p><p>{app_url}</p><p>Bedankt,<br>{app_name}</p>',
                    'pl' => '<p>Witaj,&nbsp;<br>Witamy w {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Hasło</b> : {password}</p><p>{app_url}</p><p>Dzięki,<br>{app_name}</p>',
                    'ru' => '<p>Привет,&nbsp;<br>Добро пожаловать в {app_name}.</p><p><b>Электронное письмо </b>: {email}<br><b>пароль</b> : {password}</p><p>{app_url}</p><p>Спасибо,<br>{app_name}</p>',
                    'pt' => '<p>Ol&aacute;, Bem-vindo a {app_name}.</p>
                    <p>E-mail: {email}</p>
                    <p>Senha: {password}</p>
                    <p>{app_url}</p>
                    <p>&nbsp;</p>
                    <p>Obrigado,</p>
                    <p>{app_name}</p>',
                ],
            ],
            'Customer Invoice Send' => [
                'subject' => 'Customer Invoice Send',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Invoice Name": "invoice_name",
                    "Invoice Number": "invoice_number",
                    "Download Invoice ": "invoice_url",
                    "Pay Invoice" : "pay_invoice_url"
                  }',
                  'lang' => [
                    'ar' => '<p>مرحبا ، { invoice_name }</p>
                    <p>مرحبا بك في { app_name }</p>
                    <p>أتمنى أن يجدك هذا البريد الإلكتروني جيدا برجاء الرجوع الى رقم الفاتورة الملحقة { invoice_number } للخدمة / الخدمة.</p>
                    <p>ببساطة اضغط على الاختيار بأسفل.</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">تحميل فاتورة</strong> </a></span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">دفع الفاتورة</strong> </a></span></p>
                    <p>إشعر بالحرية للوصول إلى الخارج إذا عندك أي أسئلة.</p>
                    <p>شكرا لك</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'da' => '<p>Hej, { invoice_name }</p>
                    <p>Velkommen til { app_name }</p>
                    <p>H&aring;ber denne e-mail finder dig godt! Se vedlagte fakturanummer { invoice_number } for product/service.</p>
                    <p>Klik p&aring; knappen nedenfor.</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Download faktura</strong> </a></span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Betal faktura</strong> </a></span></p>
                    <p>Du er velkommen til at r&aelig;kke ud, hvis du har nogen sp&oslash;rgsm&aring;l.</p>
                    <p>Tak.</p>
                    <p>&nbsp;</p>
                    <p>Med venlig hilsen</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'de' => '<p>Hi, {invoice_name}</p>
                    <p>Willkommen bei {app_name}</p>
                    <p>Hoffe, diese E-Mail findet dich gut! Bitte beachten Sie die beigef&uuml;gte Rechnungsnummer {invoice_number} f&uuml;r Produkt/Service.</p>
                    <p>Klicken Sie einfach auf den Button unten.</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Download Rechnung</strong> </a></span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Rechnung bezahlen</strong> </a></span></p>
                    <p>F&uuml;hlen Sie sich frei, wenn Sie Fragen haben.</p>
                    <p>Vielen Dank,</p>
                    <p>&nbsp;</p>
                    <p>Betrachtet,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>
                    ',
                    'en' => '<p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Hi, {invoice_name}</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Welcome to {app_name}</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Hope this email finds you well! Please see attached invoice number {invoice_number} for product/service.</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Simply click on the button below.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Download Invoice</strong> </a></span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Pay Invoice</strong> </a></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Feel free to reach out if you have any questions.</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Thank You,</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Regards,</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">{company_name}</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">{app_url}</span></p>',
                    'es' => '<p>Hi, {invoice_name}</p>
                    <p>&nbsp;</p>
                    <p>Bienvenido a {app_name}</p>+
                    <p>&nbsp;</p>
                    <p>&iexcl;Espero que este email le encuentre bien! Consulte el n&uacute;mero de factura adjunto {invoice_number} para el producto/servicio.</p>
                    <p>&nbsp;</p>
                    <p>Simplemente haga clic en el bot&oacute;n de abajo.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Descargar factura</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Factura de pago</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Si&eacute;ntase libre de llegar si usted tiene alguna pregunta.</p>
                    <p>&nbsp;</p>
                    <p>Gracias,</p>
                    <p>&nbsp;</p>
                    <p>Considerando,</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'fr' => '<p>Bonjour, { invoice_name }</p>
                    <p>&nbsp;</p>
                    <p>Bienvenue dans { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Jesp&egrave;re que ce courriel vous trouve bien ! Voir le num&eacute;ro de facture { invoice_number } pour le produit/service.</p>
                    <p>&nbsp;</p>
                    <p>Cliquez simplement sur le bouton ci-dessous.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Télécharger la facture</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Payer sa commande</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Nh&eacute;sitez pas &agrave; nous contacter si vous avez des questions.</p>
                    <p>&nbsp;</p>
                    <p>Merci,</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'it' => '<p>Ciao, {invoice_name}</p>
                    <p>&nbsp;</p>
                    <p>Benvenuti in {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Spero che questa email ti trovi bene! Si prega di consultare il numero di fattura collegato {invoice_number} per il prodotto/servizio.</p>
                    <p>&nbsp;</p>
                    <p>Semplicemente clicca sul pulsante sottostante.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Scarica Fattura</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Pagare la fattura</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Sentiti libero di raggiungere se hai domande.</p>
                    <p>&nbsp;</p>
                    <p>Grazie,</p>
                    <p>&nbsp;</p>
                    <p>Riguardo,</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'ja' => '<p>こんにちは、 {invoice_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_name} へようこそ</p>
                    <p>&nbsp;</p>
                    <p>この E メールでよくご確認ください。 製品 / サービスについては、添付された請求書番号 {invoice_number} を参照してください。</p>
                    <p>&nbsp;</p>
                    <p>以下のボタンをクリックしてください。</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">請求書のダウンロード</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">請求書の支払い</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>質問がある場合は、自由に連絡してください。</p>
                    <p>&nbsp;</p>
                    <p>ありがとうございます</p>
                    <p>&nbsp;</p>
                    <p>よろしく</p>
                    <p>&nbsp;</p>
                    <p>{ company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'nl' => '<p>Hallo, { invoice_name }</p>
                    <p>Welkom bij { app_name }</p>
                    <p>Hoop dat deze e-mail je goed vindt! Zie bijgevoegde factuurnummer { invoice_number } voor product/service.</p>
                    <p>Klik gewoon op de knop hieronder.</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Factuur downloaden</strong> </a></span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Betaal factuur</strong> </a></span></p>
                    <p>Voel je vrij om uit te reiken als je vragen hebt.</p>
                    <p>Dank U,</p>
                    <p>Betreft:</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'pl' => '<p>Witaj, {invoice_name }</p>
                    <p>&nbsp;</p>
                    <p>Witamy w aplikacji {app_name }</p>
                    <p>&nbsp;</p>
                    <p>Mam nadzieję, że ta wiadomość znajdzie Cię dobrze! Sprawdź załączoną fakturę numer {invoice_number } dla produktu/usługi.</p>
                    <p>&nbsp;</p>
                    <p>Wystarczy kliknąć na przycisk poniżej.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Pobierz fakturę</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Zapłać fakturę</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Czuj się swobodnie, jeśli masz jakieś pytania.</p>
                    <p>&nbsp;</p>
                    <p>Dziękuję,</p>
                    <p>&nbsp;</p>
                    <p>W odniesieniu do</p>
                    <p>&nbsp;</p>
                    <p>{company_name }</p>
                    <p>&nbsp;</p>
                    <p>{app_url }</p>',
                    'ru' => '<p>Привет, { invoice_name }</p>
                    <p>&nbsp;</p>
                    <p>Вас приветствует { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Надеюсь, это электронное письмо найдет вас хорошо! См. вложенный номер счета-фактуры { invoice_number } для производства/услуги.</p>
                    <p>&nbsp;</p>
                    <p>Просто нажмите на кнопку внизу.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Скачать счет</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Оплатить счет</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Не стеснитесь, если у вас есть вопросы.</p>
                    <p>&nbsp;</p>
                    <p>Спасибо.</p>
                    <p>&nbsp;</p>
                    <p>С уважением,</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'pt' => '<p>Oi, {invoice_name}</p>
                    <p>&nbsp;</p>
                    <p>Bem-vindo a {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Espero que este e-mail encontre voc&ecirc; bem! Por favor, consulte o n&uacute;mero da fatura anexa {invoice_number} para produto/servi&ccedil;o.</p>
                    <p>&nbsp;</p>
                    <p>Basta clicar no bot&atilde;o abaixo.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Baixe o Invoice</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{pay_invoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Fatura de pagamento</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Sinta-se &agrave; vontade para alcan&ccedil;ar fora se voc&ecirc; tiver alguma d&uacute;vida.</p>
                    <p>&nbsp;</p>
                    <p>Obrigado,</p>
                    <p>&nbsp;</p>
                    <p>Considera,</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                ],
            ],
            'Payment Reminder' => [
                'subject' => 'Payment Reminder',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Payment Name": "payment_name",
                    "Invoice Number": "invoice_number",
                    "Payment Due Amount": "payment_dueAmount",
                    "Payment Date": "payment_date"
                  }',
                'lang' => [
                    'ar' => '<p>عزيزي ، { payment_name }</p>
                    <p>آمل أن تكون بخير. هذا مجرد تذكير بأن الدفع على الفاتورة { invoice_number } الاجمالي { payment_dueAmount } ، والتي قمنا بارسالها على { payment_date } مستحق اليوم.</p>
                    <p>يمكنك دفع مبلغ لحساب البنك المحدد على الفاتورة.</p>
                    <p>أنا متأكد أنت مشغول ، لكني أقدر إذا أنت يمكن أن تأخذ a لحظة ونظرة على الفاتورة عندما تحصل على فرصة.</p>
                    <p>إذا كان لديك أي سؤال مهما يكن ، يرجى الرد وسأكون سعيدا لتوضيحها.</p>
                    <p>&nbsp;</p>
                    <p>شكرا&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>
                    <p>&nbsp;</p>',
                    'da' => '<p>K&aelig;re, { payment_name }</p>
                    <p>Dette er blot en p&aring;mindelse om, at betaling p&aring; faktura { invoice_number } i alt { payment_dueAmount}, som vi sendte til { payment_date }, er forfalden i dag.</p>
                    <p>Du kan foretage betalinger til den bankkonto, der er angivet p&aring; fakturaen.</p>
                    <p>Jeg er sikker p&aring; du har travlt, men jeg ville s&aelig;tte pris p&aring;, hvis du kunne tage et &oslash;jeblik og se p&aring; fakturaen, n&aring;r du f&aring;r en chance.</p>
                    <p>Hvis De har nogen sp&oslash;rgsm&aring;l, s&aring; svar venligst, og jeg vil med gl&aelig;de tydeligg&oslash;re dem.</p>
                    <p>&nbsp;</p>
                    <p>Tak.&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>
                    <p>&nbsp;</p>',
                    'de' => '<p>Sehr geehrte/r, {payment_name}</p>
                    <p>Ich hoffe, Sie sind gut. Dies ist nur eine Erinnerung, dass die Zahlung auf Rechnung {invoice_number} total {payment_dueAmount}, die wir gesendet am {payment_date} ist heute f&auml;llig.</p>
                    <p>Sie k&ouml;nnen die Zahlung auf das auf der Rechnung angegebene Bankkonto vornehmen.</p>
                    <p>Ich bin sicher, Sie sind besch&auml;ftigt, aber ich w&uuml;rde es begr&uuml;&szlig;en, wenn Sie einen Moment nehmen und &uuml;ber die Rechnung schauen k&ouml;nnten, wenn Sie eine Chance bekommen.</p>
                    <p>Wenn Sie irgendwelche Fragen haben, antworten Sie bitte und ich w&uuml;rde mich freuen, sie zu kl&auml;ren.</p>
                    <p>&nbsp;</p>
                    <p>Danke,&nbsp;</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>
                    <p>&nbsp;</p>',
                    'en' => '<p>Dear, {payment_name}</p>
                    <p>I hope you&rsquo;re well.This is just a reminder that payment on invoice {invoice_number} total dueAmount {payment_dueAmount} , which we sent on {payment_date} is due today.</p>
                    <p>You can make payment to the bank account specified on the invoice.</p>
                    <p>I&rsquo;m sure you&rsquo;re busy, but I&rsquo;d appreciate if you could take a moment and look over the invoice when you get a chance.</p>
                    <p>If you have any questions whatever, please reply and I&rsquo;d be happy to clarify them.</p>
                    <p>&nbsp;</p>
                    <p>Thanks,&nbsp;</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>
                    <p>&nbsp;</p>',
                    'es' => '<p>Estimado, {payment_name}</p>
                    <p>Espero que est&eacute;s bien. Esto es s&oacute;lo un recordatorio de que el pago en la factura {invoice_number} total {payment_dueAmount}, que enviamos en {payment_date} se vence hoy.</p>
                    <p>Puede realizar el pago a la cuenta bancaria especificada en la factura.</p>
                    <p>Estoy seguro de que est&aacute;s ocupado, pero agradecer&iacute;a si podr&iacute;as tomar un momento y mirar sobre la factura cuando tienes una oportunidad.</p>
                    <p>Si tiene alguna pregunta, por favor responda y me gustar&iacute;a aclararlas.</p>
                    <p>&nbsp;</p>
                    <p>Gracias,&nbsp;</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>
                    <p>&nbsp;</p>',
                    'fr' => '<p>Cher, { payment_name }</p>
                    <p>Jesp&egrave;re que vous &ecirc;tes bien, ce nest quun rappel que le paiement sur facture {invoice_number}total { payment_dueAmount }, que nous avons envoy&eacute; le {payment_date} est d&ucirc; aujourdhui.</p>
                    <p>Vous pouvez effectuer le paiement sur le compte bancaire indiqu&eacute; sur la facture.</p>
                    <p>Je suis s&ucirc;r que vous &ecirc;tes occup&eacute;, mais je vous serais reconnaissant de prendre un moment et de regarder la facture quand vous aurez une chance.</p>
                    <p>Si vous avez des questions, veuillez r&eacute;pondre et je serais heureux de les clarifier.</p>
                    <p>&nbsp;</p>
                    <p>Merci,&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>
                    <p>&nbsp;</p>',
                    'it' => '<p>Caro, {payment_name}</p>
                    <p>Spero che tu stia bene, questo &egrave; solo un promemoria che il pagamento sulla fattura {invoice_number} totale {payment_dueAmount}, che abbiamo inviato su {payment_date} &egrave; dovuto oggi.</p>
                    <p>&Egrave; possibile effettuare il pagamento al conto bancario specificato sulla fattura.</p>
                    <p>Sono sicuro che sei impegnato, ma apprezzerei se potessi prenderti un momento e guardare la fattura quando avrai una chance.</p>
                    <p>Se avete domande qualunque, vi prego di rispondere e sarei felice di chiarirle.</p>
                    <p>&nbsp;</p>
                    <p>Grazie,&nbsp;</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>
                    <p>&nbsp;</p>',
                    'ja' => '<p>ID、 {payment_name}</p>
                    <p>これは、 { payment_dueAmount} の合計 {payment_dueAmount } に対する支払いが今日予定されていることを思い出させていただきたいと思います。</p>
                    <p>請求書に記載されている銀行口座に対して支払いを行うことができます。</p>
                    <p>お忙しいのは確かですが、機会があれば、少し時間をかけてインボイスを見渡すことができればありがたいのですが。</p>
                    <p>何か聞きたいことがあるなら、お返事をお願いしますが、喜んでお答えします。</p>
                    <p>&nbsp;</p>
                    <p>ありがとう。&nbsp;</p>
                    <p>{ company_name}</p>
                    <p>{app_url}</p>
                    <p>&nbsp;</p>',
                    'nl' => '<p>Geachte, { payment_name }</p>
                    <p>Ik hoop dat u goed bent. Dit is gewoon een herinnering dat betaling op factuur { invoice_number } totaal { payment_dueAmount }, die we verzonden op { payment_date } is vandaag verschuldigd.</p>
                    <p>U kunt betaling doen aan de bankrekening op de factuur.</p>
                    <p>Ik weet zeker dat je het druk hebt, maar ik zou het op prijs stellen als je even over de factuur kon kijken als je een kans krijgt.</p>
                    <p>Als u vragen hebt, beantwoord dan uw antwoord en ik wil ze graag verduidelijken.</p>
                    <p>&nbsp;</p>
                    <p>Bedankt.&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>
                    <p>&nbsp;</p>',
                    'pl' => '<p>Drogi, {payment_name }</p>
                    <p>Mam nadzieję, że jesteś dobrze. To jest tylko przypomnienie, że płatność na fakturze {invoice_number } total {payment_dueAmount }, kt&oacute;re wysłaliśmy na {payment_date } jest dzisiaj.</p>
                    <p>Płatność można dokonać na rachunek bankowy podany na fakturze.</p>
                    <p>Jestem pewien, że jesteś zajęty, ale byłbym wdzięczny, gdybyś m&oacute;gł wziąć chwilę i spojrzeć na fakturę, kiedy masz szansę.</p>
                    <p>Jeśli masz jakieś pytania, proszę o odpowiedź, a ja chętnie je wyjaśniam.</p>
                    <p>&nbsp;</p>
                    <p>Dziękuję,&nbsp;</p>
                    <p>{company_name }</p>
                    <p>{app_url }</p>
                    <p>&nbsp;</p>',
                    'ru' => '<p>Уважаемый, { payment_name }</p>
                    <p>Я надеюсь, что вы хорошо. Это просто напоминание о том, что оплата по счету { invoice_number } всего { payment_dueAmount }, которое мы отправили в { payment_date }, сегодня.</p>
                    <p>Вы можете произвести платеж на банковский счет, указанный в счете-фактуре.</p>
                    <p>Я уверена, что ты занята, но я была бы признательна, если бы ты смог бы поглядеться на счет, когда у тебя появится шанс.</p>
                    <p>Если у вас есть вопросы, пожалуйста, ответьте, и я буду рад их прояснить.</p>
                    <p>&nbsp;</p>
                    <p>Спасибо.&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>
                    <p>&nbsp;</p>',
                    'pt' => '<p>Querido, {payment_name}</p>
                    <p>Espero que voc&ecirc; esteja bem. Este &eacute; apenas um lembrete de que o pagamento na fatura {invoice_number} total {payment_dueAmount}, que enviamos em {payment_date} &eacute; devido hoje.</p>
                    <p>Voc&ecirc; pode fazer o pagamento &agrave; conta banc&aacute;ria especificada na fatura.</p>
                    <p>Eu tenho certeza que voc&ecirc; est&aacute; ocupado, mas eu agradeceria se voc&ecirc; pudesse tirar um momento e olhar sobre a fatura quando tiver uma chance.</p>
                    <p>Se voc&ecirc; tiver alguma d&uacute;vida o que for, por favor, responda e eu ficaria feliz em esclarec&ecirc;-las.</p>
                    <p>&nbsp;</p>
                    <p>Obrigado,&nbsp;</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>
                    <p>&nbsp;</p>',
                ],
            ],
            'Invoice Payment Create' => [
                'subject' => 'Invoice Payment Create',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Payment Name": "payment_name",
                    "Invoice Number": "invoice_number",
                    "Payment Amount": "payment_amount",
                    "Payment dueAmount": "payment_dueAmount",
                    "Payment Date": "payment_date"
                  }',
                'lang' => [
                    'ar' => '<p>مرحبا</p>
                    <p>مرحبا بك في { app_name }</p>
                    <p>عزيزي { payment_name }</p>
                    <p>لقد قمت باستلام المبلغ الخاص بك {payment_amount}&nbsp; لبرنامج { invoice_number } الذي تم تقديمه في التاريخ { payment_date }</p>
                    <p>مقدار الاستحقاق { invoice_number } الخاص بك هو {payment_dueAmount}</p>
                    <p>ونحن نقدر الدفع الفوري لكم ونتطلع إلى استمرار العمل معكم في المستقبل.</p>
                    <p>&nbsp;</p>
                    <p>شكرا جزيلا لكم ويوم جيد ! !</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'da' => '<p>Hej.</p>
                    <p>Velkommen til { app_name }</p>
                    <p>K&aelig;re { payment_name }</p>
                    <p>Vi har modtaget din m&aelig;ngde { payment_amount } betaling for { invoice_number } undert.d. p&aring; dato { payment_date }</p>
                    <p>Dit { invoice_number } Forfaldsbel&oslash;b er { payment_dueAmount }</p>
                    <p>Vi s&aelig;tter pris p&aring; din hurtige betaling og ser frem til fortsatte forretninger med dig i fremtiden.</p>
                    <p>Mange tak, og ha en god dag!</p>
                    <p>&nbsp;</p>
                    <p>Med venlig hilsen</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'de' => '<p>Hi,</p>
                    <p>Willkommen bei {app_name}</p>
                    <p>Sehr geehrter {payment_name}</p>
                    <p>Wir haben Ihre Zahlung {payment_amount} f&uuml;r {invoice_number}, die am Datum {payment_date} &uuml;bergeben wurde, erhalten.</p>
                    <p>Ihr {invoice_number} -f&auml;lliger Betrag ist {payment_dueAmount}</p>
                    <p>Wir freuen uns &uuml;ber Ihre prompte Bezahlung und freuen uns auf das weitere Gesch&auml;ft mit Ihnen in der Zukunft.</p>
                    <p>Vielen Dank und habe einen guten Tag!!</p>
                    <p>&nbsp;</p>
                    <p>Betrachtet,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'en' => '<p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Hi,</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Welcome to {app_name}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Dear {payment_name}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">We have recieved your amount {payment_amount} payment for {invoice_number} submited on date {payment_date}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Your {invoice_number} Due amount is {payment_dueAmount}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">We appreciate your prompt payment and look forward to continued business with you in the future.</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Thank you very much and have a good day!!</span></span></p>
                    <p>&nbsp;</p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Regards,</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">{company_name}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">{app_url}</span></span></p>',
                    'es' => '<p>Hola,</p>
                    <p>Bienvenido a {app_name}</p>
                    <p>Estimado {payment_name}</p>
                    <p>Hemos recibido su importe {payment_amount} pago para {invoice_number} submitado en la fecha {payment_date}</p>
                    <p>El importe de {invoice_number} Due es {payment_dueAmount}</p>
                    <p>Agradecemos su pronto pago y esperamos continuar con sus negocios con usted en el futuro.</p>
                    <p>Muchas gracias y que tengan un buen d&iacute;a!!</p>
                    <p>&nbsp;</p>
                    <p>Considerando,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'fr' => '<p>Salut,</p>
                    <p>Bienvenue dans { app_name }</p>
                    <p>Cher { payment_name }</p>
                    <p>Nous avons re&ccedil;u votre montant { payment_amount } de paiement pour { invoice_number } soumis le { payment_date }</p>
                    <p>Votre {invoice_number} Montant d&ucirc; est { payment_dueAmount }</p>
                    <p>Nous appr&eacute;cions votre rapidit&eacute; de paiement et nous attendons avec impatience de poursuivre vos activit&eacute;s avec vous &agrave; lavenir.</p>
                    <p>Merci beaucoup et avez une bonne journ&eacute;e ! !</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'it' => '<p>Ciao,</p>
                    <p>Benvenuti in {app_name}</p>
                    <p>Caro {payment_name}</p>
                    <p>Abbiamo ricevuto la tua quantit&agrave; {payment_amount} pagamento per {invoice_number} subita alla data {payment_date}</p>
                    <p>Il tuo {invoice_number} A somma cifra &egrave; {payment_dueAmount}</p>
                    <p>Apprezziamo il tuo tempestoso pagamento e non vedo lora di continuare a fare affari con te in futuro.</p>
                    <p>Grazie mille e buona giornata!!</p>
                    <p>&nbsp;</p>
                    <p>Riguardo,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'ja' => '<p>こんにちは。</p>
                    <p>{app_name} へようこそ</p>
                    <p>{ payment_name} に出れます</p>
                    <p>{ payment_date} 日付で提出された {請求書番号} の支払金額 } の金額を回収しました。 }</p>
                    <p>お客様の {請求書番号} 予定額は {payment_dueAmount} です</p>
                    <p>お客様の迅速な支払いを評価し、今後も継続してビジネスを継続することを期待しています。</p>
                    <p>ありがとうございます。良い日をお願いします。</p>
                    <p>&nbsp;</p>
                    <p>よろしく</p>
                    <p>{ company_name}</p>
                    <p>{app_url}</p>',
                    'nl' => '<p>Hallo,</p>
                    <p>Welkom bij { app_name }</p>
                    <p>Beste { payment_name }</p>
                    <p>We hebben uw bedrag ontvangen { payment_amount } betaling voor { invoice_number } ingediend op datum { payment_date }</p>
                    <p>Uw { invoice_number } verschuldigde bedrag is { payment_dueAmount }</p>
                    <p>Wij waarderen uw snelle betaling en kijken uit naar verdere zaken met u in de toekomst.</p>
                    <p>Hartelijk dank en hebben een goede dag!!</p>
                    <p>&nbsp;</p>
                    <p>Betreft:</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'pl' => '<p>Witam,</p>
                    <p>Witamy w aplikacji {app_name }</p>
                    <p>Droga {payment_name }</p>
                    <p>Odebrano kwotę {payment_amount } płatności za {invoice_number } w dniu {payment_date }, kt&oacute;ry został zastąpiony przez użytkownika.</p>
                    <p>{invoice_number } Kwota należna: {payment_dueAmount }</p>
                    <p>Doceniamy Twoją szybką płatność i czekamy na kontynuację działalności gospodarczej z Tobą w przyszłości.</p>
                    <p>Dziękuję bardzo i mam dobry dzień!!</p>
                    <p>&nbsp;</p>
                    <p>W odniesieniu do</p>
                    <p>{company_name }</p>
                    <p>{app_url }</p>',
                    'ru' => '<p>Привет.</p>
                    <p>Вас приветствует { app_name }</p>
                    <p>Дорогая { payment_name }</p>
                    <p>Мы получили вашу сумму оплаты {payment_amount} для { invoice_number }, подавшей на дату { payment_date }</p>
                    <p>Ваша { invoice_number } Должная сумма-{ payment_dueAmount }</p>
                    <p>Мы ценим вашу своевременную оплату и надеемся на продолжение бизнеса с вами в будущем.</p>
                    <p>Большое спасибо и хорошего дня!!</p>
                    <p>&nbsp;</p>
                    <p>С уважением,</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'pt' => '<p>Oi,</p>
                    <p>Bem-vindo a {app_name}</p>
                    <p>Querido {payment_name}</p>
                    <p>N&oacute;s recibimos sua quantia {payment_amount} pagamento para {invoice_number} requisitado na data {payment_date}</p>
                    <p>Sua quantia {invoice_number} Due &eacute; {payment_dueAmount}</p>
                    <p>Agradecemos o seu pronto pagamento e estamos ansiosos para continuarmos os neg&oacute;cios com voc&ecirc; no futuro.</p>
                    <p>Muito obrigado e tenha um bom dia!!</p>
                    <p>&nbsp;</p>
                    <p>Considera,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                ],
            ],
            'Proposal Send' => [
                'subject' => 'Proposal Send',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "proposal Name": "proposal_name",
                    "proposal Number": "proposal_number",
                    "proposal Url": "proposal_url"
                  }',
                  'lang' => [
                    'ar' => '<p>مرحبا ، { proposal_name }</p>
                    <p>أتمنى أن يجدك هذا البريد الإلكتروني جيدا برجاء الرجوع الى رقم الاقتراح المرفق { proposal_number } للمنتج / الخدمة.</p>
                    <p>اضغط ببساطة على الاختيار بأسفل</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">عرض</strong> </a></span></p>
                    <p>إشعر بالحرية للوصول إلى الخارج إذا عندك أي أسئلة.</p>
                    <p>شكرا لعملك ! !</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'da' => '<p>Hej, {proposal_name }</p>
                    <p>H&aring;ber denne e-mail finder dig godt! Se det vedh&aelig;ftede forslag nummer { proposal_number } for product/service.</p>
                    <p>klik bare p&aring; knappen nedenfor</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Forslag</strong> </a></span></p>
                    <p>Du er velkommen til at r&aelig;kke ud, hvis du har nogen sp&oslash;rgsm&aring;l.</p>
                    <p>Tak for din virksomhed!</p>
                    <p>&nbsp;</p>
                    <p>Med venlig hilsen</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'de' => '<p>Hi, {proposal_name}</p>
                    <p>Hoffe, diese E-Mail findet dich gut! Bitte sehen Sie die angeh&auml;ngte Vorschlagsnummer {proposal_number} f&uuml;r Produkt/Service an.</p>
                    <p>Klicken Sie einfach auf den Button unten</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Vorschlag</strong> </a></span></p>
                    <p>F&uuml;hlen Sie sich frei, wenn Sie Fragen haben.</p>
                    <p>Vielen Dank f&uuml;r Ihr Unternehmen!!</p>
                    <p>&nbsp;</p>
                    <p>Betrachtet,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'en' => '<p>Hi, {proposal_name}</p>
                    <p>Hope this email ﬁnds you well! Please see attached proposal number {proposal_number} for product/service.</p>
                    <p>simply click on the button below</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Proposal</strong> </a></span></p>
                    <p>Feel free to reach out if you have any questions.</p>
                    <p>Thank you for your business!!</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'es' => '<p>Hi, {proposal_name}</p>
                    <p>&iexcl;Espero que este email le encuentre bien! Consulte el n&uacute;mero de propuesta adjunto {proposal_number} para el producto/servicio.</p>
                    <p>simplemente haga clic en el bot&oacute;n de abajo</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Propuesta</strong> </a></span></p>
                    <p>Si&eacute;ntase libre de llegar si usted tiene alguna pregunta.</p>
                    <p>&iexcl;Gracias por su negocio!!</p>
                    <p>&nbsp;</p>
                    <p>Considerando,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'fr' => '<p>Salut, {proposal_name}</p>
                    <p>Jesp&egrave;re que ce courriel vous trouve bien ! Veuillez consulter le num&eacute;ro de la proposition jointe {proposal_number} pour le produit/service.</p>
                    <p>Il suffit de cliquer sur le bouton ci-dessous</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Proposition</strong> </a></span></p>
                    <p>Nh&eacute;sitez pas &agrave; nous contacter si vous avez des questions.</p>
                    <p>Merci pour votre entreprise ! !</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'it' => '<p>Ciao, {proposal_name}</p>
                    <p>Spero che questa email ti trovi bene! Si prega di consultare il numero di proposta allegato {proposal_number} per il prodotto/servizio.</p>
                    <p>semplicemente clicca sul pulsante sottostante</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Proposta</strong> </a></span></p>
                    <p>Sentiti libero di raggiungere se hai domande.</p>
                    <p>Grazie per il tuo business!!</p>
                    <p>&nbsp;</p>
                    <p>Riguardo,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'ja' => '<p>こんにちは、 {proposal_name}</p>
                    <p>この E メールでよくご確認ください。 製品 / サービスの添付されたプロポーザル番号 {proposal_number} を参照してください。</p>
                    <p>下のボタンをクリックするだけで</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">提案</strong> </a></span></p>
                    <p>質問がある場合は、自由に連絡してください。</p>
                    <p>お客様のビジネスに感謝します。</p>
                    <p>&nbsp;</p>
                    <p>よろしく</p>
                    <p>{ company_name}</p>
                    <p>{app_url}</p>',
                    'nl' => '<p>Hallo, {proposal_name}</p>
                    <p>Hoop dat deze e-mail je goed vindt! Zie bijgevoegde nummer {proposal_number} voor product/service.</p>
                    <p>gewoon klikken op de knop hieronder</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Voorstel</strong> </a></span></p>
                    <p>Voel je vrij om uit te reiken als je vragen hebt.</p>
                    <p>Dank u voor uw bedrijf!!</p>
                    <p>&nbsp;</p>
                    <p>Betreft:</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'pl' => '<p>Witaj, {proposal_name}</p>
                    <p>Mam nadzieję, że ta wiadomość znajdzie Cię dobrze! Proszę zapoznać się z załączonym numerem wniosku {proposal_number} dla produktu/usługi.</p>
                    <p>po prostu kliknij na przycisk poniżej</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Wniosek</strong> </a></span></p>
                    <p>Czuj się swobodnie, jeśli masz jakieś pytania.</p>
                    <p>Dziękujemy za prowadzenie działalności!!</p>
                    <p>&nbsp;</p>
                    <p>W odniesieniu do</p>
                    <p>{company_name }</p>
                    <p>{app_url }</p>',
                    'ru' => '<p>Здравствуйте, {proposal_name}</p>
                    <p>Надеюсь, это электронное письмо найдет вас хорошо! См. вложенное предложение номер {proposal_number} для product/service.</p>
                    <p>просто нажмите на кнопку внизу</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Предложение</strong> </a></span></p>
                    <p>Не стеснитесь, если у вас есть вопросы.</p>
                    <p>Спасибо за ваше дело!</p>
                    <p>&nbsp;</p>
                    <p>С уважением,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'pt' => '<p>Oi, {proposal_name}</p>
                    <p>Espero que este e-mail encontre voc&ecirc; bem! Por favor, consulte o n&uacute;mero da proposta anexada {proposal_number} para produto/servi&ccedil;o.</p>
                    <p>basta clicar no bot&atilde;o abaixo</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{proposal_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Proposta</strong> </a></span></p>
                    <p>Sinta-se &agrave; vontade para alcan&ccedil;ar fora se voc&ecirc; tiver alguma d&uacute;vida.</p>
                    <p>Obrigado pelo seu neg&oacute;cio!!</p>
                    <p>&nbsp;</p>
                    <p>Considera,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                ],
            ],
            'New Helpdesk Ticket' =>
            [
                    'subject' => 'New Helpdesk Ticket',
                    'variables' => '{
                            "App Name": "app_name",
                            "App Url": "app_url",
                            "Ticket Name": "ticket_name",
                            "Email": "email",
                            "Ticket Id" : "ticket_id",
                            "Password": "password",
                            "Ticket Url": "ticket_url"
                      }',
                      'lang' => [
                            'ar' => '<p>مرحبا بك<br />الى { app_name }</p>
                            <p><strong>البريد الالكتروني : { email } </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>كود بطاقة طلب الخدمة: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">تحقق من صلاحية</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Thanks,<br />{app_name}</p>',
                            'da' => '<p>Velkommen<br />to {app_name}</p>
                            <p><strong>E-mail : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>Ticket ID: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Validerer din</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Tak,<br />{app_name}</p>',
                            'de' => '<p>Begrüßung<br />to {app_name}</p>
                            <p><strong>Email : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>Ticket ID: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Überprüfen von</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Vielen Dank,<br />{app_name}</p>',
                            'en' => '<p>Welcome<br />to {app_name}</p>
                            <p><strong>Email : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>Ticket ID: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Validating your</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Thanks,<br />{app_name}</p>',
                            'es' => '<p>Bienvenido<br />to {app_name}</p>
                            <p><strong>Correo electrónico : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>ID de ticket: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Validación de la</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Gracias,<br />{app_name}</p>',
                            'fr' => '<p>Bienvenue<br />to {app_name}</p>
                            <p><strong>Courrier électronique : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>ID de ticket: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Validation de votre</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Merci,<br />{app_name}</p>',
                            'it' => '<p>Benvenuto<br />to {app_name}</p>
                            <p><strong>Email : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>ID ticket: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Convalida del tuo</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Grazie,<br />{app_name}</p>',
                            'ja' => '<p>ようこそ<br />to {app_name}</p>
                            <p><strong>E メール: {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>チケット ID: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">検証しています</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>ありがと,<br />{app_name}</p>',
                            'nl' => '<p>Welkom<br />to {app_name}</p>
                            <p><strong>E-mail : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>Ticket-ID: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Bezig met valideren van uw</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Bedankt,<br />{app_name}</p>',
                            'pl' => '<p>Powitanie<br />to {app_name}</p>
                            <p><strong>Adres e-mail : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>Id. zgłoszenia: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Sprawdzanie poprawności</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Dziękujemy,<br />{app_name}</p>',
                            'ru' => '<p>Приветствие<br />to {app_name}</p>
                            <p><strong>Электронная почта : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>ID паспорта: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Проверка правильности</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Спасибо,<br />{app_name}</p>',
                            'pt' => '<p>Bem-vindo<br />to {app_name}</p>
                            <p><strong>E-mail : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>ID do chamado: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Validando seu</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Obrigado,<br />{app_name}</p>',
                            'tr'=>'<p>Hoş Geldiniz<br />to {app_name}</p>
                            <p><strong>Eposta : {email} </strong><strong><br /><strong><strong>
                            <p><strong><strong><strong> </strong>Bildirim Formu Tanıtıcısı: {ticket_id}<br />
                                <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{ticket_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Sizin</strong> </a></span></p>
                                    </strong> </a></span></span></p>
                            <p>{app_url}</p>
                            <p>Teşekkürler,<br />{app_name}</p>',
                    ],
            ],
            'New Helpdesk Ticket Reply' => [
                'subject' => 'Helpdesk Ticket Reply',
                'variables' => '{
                        "App Name" : "app_name",
                        "Company Name" : "company_name",
                        "App Url": "app_url",
                        "Ticket Name" : "ticket_name",
                        "Ticket Id" : "ticket_id",
                        "Ticket Description" : "reply_description"
                    }',
                'lang' => [
                    'ar' => '<p>مرحبا ، مرحبا بك في { app_name }.</p><p>&nbsp;</p><p>{ ticket_name }</p><p>{ ticket_id }</p><p>&nbsp;</p><p>الوصف : { reply_description }</p><p>&nbsp;</p><p>شكرا</p><p>{ app_name }</p>',
                    'da' => '<p>Hej, velkommen til { app_name }.</p><p>&nbsp;</p><p>{ ticket_name }</p><p>{ ticket_id }</p><p>&nbsp;</p><p>Beskrivelse: { reply_description }</p><p>&nbsp;</p><p>Tak.</p><p>{ app_name }</p>',
                    'de' => '<p>Hallo, Willkommen bei {app_name}.</p><p>&nbsp;</p><p>{ticketname}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Beschreibung: {reply_description}</p><p>&nbsp;</p><p>Danke,</p><p>{Anwendungsname}</p>',
                    'en' => '<p>Hello,&nbsp;<br />Welcome to {app_name}.</p><p>{ticket_name}</p><p>{ticket_id}</p><p><strong>Description</strong> : {reply_description}</p><p>Thanks,<br />{app_name}</p>',
                    'es' => '<p>Hola, Bienvenido a {app_name}.</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Descripci&oacute;n: {reply_description}</p><p>&nbsp;</p><p>Gracias,</p><p>{app_name}</p>',
                    'fr' => '<p>Hola, Bienvenido a {app_name}.</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Descripci&oacute;n: {reply_description}</p><p>&nbsp;</p><p>Gracias,</p><p>{app_name}</p>',
                    'it' => '<p>Ciao, Benvenuti in {app_name}.</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Descrizione: {reply_description}</p><p>&nbsp;</p><p>Grazie,</p><p>{app_name}</p>',
                    'ja' => '<p>こんにちは、 {app_name}へようこそ。</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>説明 : {reply_description}</p><p>&nbsp;</p><p>ありがとう。</p><p>{app_name}</p>',
                    'nl' => '<p>Hallo, Welkom bij { app_name }.</p><p>&nbsp;</p><p>{ ticket_name }</p><p>{ ticket_id }</p><p>&nbsp;</p><p>Beschrijving: { reply_description }</p><p>&nbsp;</p><p>Bedankt.</p><p>{ app_name }</p>',
                    'pl' => '<p>Witaj, Witamy w aplikacji {app_name }.</p><p>&nbsp;</p><p>{ticket_name }</p><p>{ticket_id }</p><p>&nbsp;</p><p>Opis: {reply_description }</p><p>&nbsp;</p><p>Dziękuję,</p><p>{app_name }</p>',
                    'ru' => '<p>Здравствуйте, Добро пожаловать в { app_name }.</p><p>&nbsp;</p><p>Witaj, Witamy w aplikacji {app_name }.</p><p>&nbsp;</p><p>{ticket_name }</p><p>{ticket_id }</p><p>&nbsp;</p><p>Opis: {reply_description }</p><p>&nbsp;</p><p>Dziękuję,</p><p>{app_name }</p>',
                    'pt' => '<p>Ol&aacute;, Bem-vindo a {app_name}.</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Descri&ccedil;&atilde;o: {reply_description}</p><p>&nbsp;</p><p>Obrigado,</p><p>{app_name}</p>',
                    'tr'=>'<p>Merhaba, &nbsp;<br />{ app_name } olanağına hoş geldiniz.</p><p>{ ticket_name }</p><p>{ ticket_id }</p><p><strong>Açıklama</strong> : { reply_description }</p><p>Teşekkürler,<br />{ uyg_adı }</p>',
                ],
            ],
            'Purchase Send' => [
                'subject' => 'Purchase Send',
                'variables' => '{
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "Purchase Name": "purchase_name",
                    "Purchase Number": "purchase_number",
                    "purchase_url": "purchase_url"
                  }',
                  'lang' => [
                    'ar' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">مرحبا ، { purchase_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">مرحبا بك في { app_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">أتمنى أن يجدك هذا البريد الإلكتروني جيدا ! ! برجاء الرجوع الى رقم الفاتورة الملحقة { purchase_number } للحصول على المنتج / الخدمة.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">ببساطة اضغط على الاختيار بأسفل.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">عملية الشراء</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">إشعر بالحرية للوصول إلى الخارج إذا عندك أي أسئلة.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">شكرا لك</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Regards,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{ company_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{ app_url }</span></p>',
                    'da' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hej, { purchase_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Velkommen til { app_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">H&aring;ber denne e-mail finder dig godt! Se vedlagte fakturanummer } { purchase_number } for product/service.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Klik p&aring; knappen nedenfor.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">køb</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Du er velkommen til at r&aelig;kke ud, hvis du har nogen sp&oslash;rgsm&aring;l.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Tak.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Med venlig hilsen</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{ company_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{ app_url }</span></p>',
                    'de' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hi, {purchase_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Willkommen bei {app_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hoffe, diese E-Mail findet dich gut!! Sehen Sie sich die beigef&uuml;gte Rechnungsnummer {purchase_number} f&uuml;r Produkt/Service an.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Klicken Sie einfach auf den Button unten.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Kauf</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">F&uuml;hlen Sie sich frei, wenn Sie Fragen haben.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Vielen Dank,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Betrachtet,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{company_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_url}</span></p>',
                    'en' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hi, {purchase_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Welcome to {app_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hope this email finds you well!! Please see attached Purchase number {purchase_number} for product/service.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Simply click on the button below.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">purchase</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Feel free to reach out if you have any questions.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Thank You,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Regards,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{company_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_url}</span></p>',
                    'es' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hi,&nbsp;{purchase_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Bienvenido a {app_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">&iexcl;Espero que este correo te encuentre bien!! Consulte el n&uacute;mero de factura adjunto {purchase_number} para el producto/servicio.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Simplemente haga clic en el bot&oacute;n de abajo.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">compra</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Si&eacute;ntase libre de llegar si usted tiene alguna pregunta.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Gracias,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Considerando,</span></p>
                    <p><span style="font-family: sans-serif;">{company_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_url}</span></p>',
                    'fr' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Salut,&nbsp;{purchase_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Bienvenue dans { app_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Jesp&egrave;re que ce courriel vous trouve bien ! ! Veuillez consulter le num&eacute;ro de facture {purchase_number}&nbsp;associ&eacute; au produit / service.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Cliquez simplement sur le bouton ci-dessous.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Achat</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Nh&eacute;sitez pas &agrave; nous contacter si vous avez des questions.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Merci,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Regards,</span></p>
                    <p><span style="font-family: sans-serif;">{company_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_url}</span></p>',
                    'it' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Ciao, {purchase_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Benvenuti in {app_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Spero che questa email ti trovi bene!! Si prega di consultare il numero di fattura allegato {purchase_number} per il prodotto/servizio.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Semplicemente clicca sul pulsante sottostante.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">acquisto</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Sentiti libero di raggiungere se hai domande.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Grazie,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Riguardo,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{company_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_url}</span></p>',
                    'ja' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">こんにちは、 {purchase_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_name} へようこそ</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">この E メールによりよく検出されます !! 製品 / サービスの添付された請求番号 {purchase_number} を参照してください。</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">以下のボタンをクリックしてください。</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">購入</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">質問がある場合は、自由に連絡してください。</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">ありがとうございます</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">よろしく</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{ company_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_url}</span></p>',
                    'nl' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hallo, { purchase_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Welkom bij { app_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hoop dat deze e-mail je goed vindt!! Zie bijgevoegde factuurnummer { purchase_number } voor product/service.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Klik gewoon op de knop hieronder.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Aankoop</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Voel je vrij om uit te reiken als je vragen hebt.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Dank U,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Betreft:</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{ company_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{ app_url }</span></p>',
                    'pl' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Witaj,&nbsp;{purchase_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Witamy w aplikacji {app_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Mam nadzieję, że ta wiadomość e-mail znajduje Cię dobrze!! Zapoznaj się z załączonym numerem rachunku {purchase_number } dla produktu/usługi.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Wystarczy kliknąć na przycisk poniżej.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">zakup</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Czuj się swobodnie, jeśli masz jakieś pytania.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Dziękuję,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">W odniesieniu do</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{company_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_url }</span></p>',
                    'ru' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Привет, { purchase_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Вас приветствует { app_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Надеюсь, это письмо найдет вас хорошо! См. прилагаемый номер счета { purchase_number } для product/service.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Просто нажмите на кнопку внизу.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">покупка</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Не стеснитесь, если у вас есть вопросы.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Спасибо.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">С уважением,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{ company_name }</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{ app_url }</span></p>',
                    'pt' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Oi, {purchase_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Bem-vindo a {app_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Espero que este e-mail encontre voc&ecirc; bem!! Por favor, consulte o n&uacute;mero de faturamento conectado {purchase_number} para produto/servi&ccedil;o.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Basta clicar no bot&atilde;o abaixo.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{purchase_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">compra</strong> </a></span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Sinta-se &agrave; vontade para alcan&ccedil;ar fora se voc&ecirc; tiver alguma d&uacute;vida.</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Obrigado,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Considera,</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{company_name}</span></p>
                    <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_url}</span></p>',
                ],
            ],
            'Purchase Payment Create' => [
                'subject' => 'Purchase Payment Create',
                'variables' => '{
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "Payment Name": "payment_name",
                    "Payment Bill": "payment_bill",
                    "Payment Amount": "payment_amount",
                    "Payment Date": "payment_date",
                    "Payment Method": "payment_method"
                  }',
                  'lang' => [
                    'ar' => '<p>مرحبا ، { payment_name }</p>
                    <p>&nbsp;</p>
                    <p>مرحبا بك في { app_name }</p>
                    <p>&nbsp;</p>
                    <p>نحن نكتب لإبلاغكم بأننا قد أرسلنا مدفوعات (payment_bill) } الخاصة بك.</p>
                    <p>&nbsp;</p>
                    <p>لقد أرسلنا قيمتك { payment_amount } لأجل { payment_bill } قمت بالاحالة في التاريخ { payment_date } من خلال { payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>شكرا جزيلا لك وطاب يومك ! !!!</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'da' => '<p>Hej, { payment_name }</p>
                    <p>&nbsp;</p>
                    <p>Velkommen til { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Vi skriver for at informere dig om, at vi har sendt din { payment_bill }-betaling.</p>
                    <p>&nbsp;</p>
                    <p>Vi har sendt dit bel&oslash;b { payment_amount } betaling for { payment_bill } undertvist p&aring; dato { payment_date } via { payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>Mange tak, og ha en god dag!</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'de' => '<p>Hi, {payment_name}</p>
                    <p>&nbsp;</p>
                    <p>Willkommen bei {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Wir schreiben Ihnen mitzuteilen, dass wir Ihre Zahlung von {payment_bill} gesendet haben.</p>
                    <p>&nbsp;</p>
                    <p>Wir haben Ihre Zahlung {payment_amount} Zahlung f&uuml;r {payment_bill} am Datum {payment_date} &uuml;ber {payment_method} gesendet.</p>
                    <p>&nbsp;</p>
                    <p>Vielen Dank und haben einen guten Tag! !!!</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'en' => '<p>Hi, {payment_name}</p>
                    <p>Welcome to {app_name}</p>
                    <p>We are writing to inform you that we has sent your {payment_bill} payment.</p>
                    <p>We has sent your amount {payment_amount} payment for {payment_bill} submited on date {payment_date} via {payment_method}.</p>
                    <p>Thank You very much and have a good day !!!!</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'es' => '<p>Hi, {payment_name}</p>
                    <p>&nbsp;</p>
                    <p>Bienvenido a {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Estamos escribiendo para informarle que hemos enviado su pago {payment_bill}.</p>
                    <p>&nbsp;</p>
                    <p>Hemos enviado su importe {payment_amount} pago para {payment_bill} submitado en la fecha {payment_date} a trav&eacute;s de {payment_method}.</p>
                    <p>&nbsp;</p>
                    <p>Thank You very much and have a good day! !!!</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'fr' => '<p>Salut, { payment_name }</p>
                    <p>&nbsp;</p>
                    <p>Bienvenue dans { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Nous vous &eacute;crivons pour vous informer que nous avons envoy&eacute; votre paiement { payment_bill }.</p>
                    <p>&nbsp;</p>
                    <p>Nous avons envoy&eacute; votre paiement { payment_amount } pour { payment_bill } soumis &agrave; la date { payment_date } via { payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>Merci beaucoup et avez un bon jour ! !!!</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'it' => '<p>Ciao, {payment_name}</p>
                    <p>&nbsp;</p>
                    <p>Benvenuti in {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Scriviamo per informarti che abbiamo inviato il tuo pagamento {payment_bill}.</p>
                    <p>&nbsp;</p>
                    <p>Abbiamo inviato la tua quantit&agrave; {payment_amount} pagamento per {payment_bill} subita alla data {payment_date} tramite {payment_method}.</p>
                    <p>&nbsp;</p>
                    <p>Grazie mille e buona giornata! !!!</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'ja' => '<p>こんにちは、 {payment_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_name} へようこそ</p>
                    <p>&nbsp;</p>
                    <p>{payment_bill} の支払いを送信したことをお知らせするために執筆しています。</p>
                    <p>&nbsp;</p>
                    <p>{payment_date } に提出された {payment_議案} に対する金額 {payment_date} の支払いは、 {payment_method}を介して送信されました。</p>
                    <p>&nbsp;</p>
                    <p>ありがとうございます。良い日をお願いします。</p>
                    <p>&nbsp;</p>
                    <p>{ company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'nl' => '<p>Hallo, { payment_name }</p>
                    <p>&nbsp;</p>
                    <p>Welkom bij { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Wij schrijven u om u te informeren dat wij uw betaling van { payment_bill } hebben verzonden.</p>
                    <p>&nbsp;</p>
                    <p>We hebben uw bedrag { payment_amount } betaling voor { payment_bill } verzonden op datum { payment_date } via { payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>Hartelijk dank en hebben een goede dag! !!!</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'pl' => '<p>Witaj, {payment_name }</p>
                    <p>&nbsp;</p>
                    <p>Witamy w aplikacji {app_name }</p>
                    <p>&nbsp;</p>
                    <p>Piszemy, aby poinformować Cię, że wysłaliśmy Twoją płatność {payment_bill }.</p>
                    <p>&nbsp;</p>
                    <p>Twoja kwota {payment_amount } została wysłana przez użytkownika {payment_bill } w dniu {payment_date } za pomocą metody {payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>Dziękuję bardzo i mam dobry dzień! !!!</p>
                    <p>&nbsp;</p>
                    <p>{company_name }</p>
                    <p>&nbsp;</p>
                    <p>{app_url }</p>',
                    'ru' => '<p>Привет, { payment_name }</p>
                    <p>&nbsp;</p>
                    <p>Вас приветствует { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Мы пишем, чтобы сообщить вам, что мы отправили вашу оплату { payment_bill }.</p>
                    <p>&nbsp;</p>
                    <p>Мы отправили вашу сумму оплаты { payment_amount } для { payment_bill }, подав на дату { payment_date } через { payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>Большое спасибо и хорошего дня! !!!</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'pt' => '<p>Oi, {payment_name}</p>
                    <p>&nbsp;</p>
                    <p>Bem-vindo a {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Estamos escrevendo para inform&aacute;-lo que enviamos o seu pagamento {payment_bill}.</p>
                    <p>&nbsp;</p>
                    <p>N&oacute;s enviamos sua quantia {payment_amount} pagamento por {payment_bill} requisitado na data {payment_date} via {payment_method}.</p>
                    <p>&nbsp;</p>
                    <p>Muito obrigado e tenha um bom dia! !!!</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',

                ],
            ],
        ];

        foreach($emailTemplate as $eTemp)
        {
            $table = EmailTemplate::where('name',$eTemp)->where('module_name','general')->exists();
            if(!$table)
            {
                $emailtemplate=  EmailTemplate::create(
                    [
                        'name' => $eTemp,
                        'from' => 'WorkDo Dash',
                        'module_name' => 'general',
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
