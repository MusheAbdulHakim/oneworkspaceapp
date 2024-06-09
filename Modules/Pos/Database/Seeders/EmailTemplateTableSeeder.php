<?php

namespace Modules\Pos\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;

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
            'Purchase Send',
            'Purchase Payment Create'
        ];
        $defaultTemplate = [
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
            $table = EmailTemplate::where('name',$eTemp)->where('module_name','Pos')->exists();
            if(!$table)
            {
                $emailtemplate=  EmailTemplate::create(
                    [
                        'name' => $eTemp,
                        'from' => 'Pos',
                        'module_name' => 'Pos',
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
