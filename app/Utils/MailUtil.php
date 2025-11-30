<?php
namespace App\Utils;

use Mail;
use Config;

class MailUtil
{
    /**
     * @codeCoverageIgnore
     */
    public static function sendMail( $view, $data, $email, $subject='הודעה ממערכת' )
    {
        if (config('app.mail_disabled')) {
            return;
        }

        Mail::send($view, $data, function ($m) use ($email, $subject) {
            $fromEmail = config('app.project_emails_from_mail', 'do-not-reply@mail4b2c.com');
            $name = config('app.project_emails_from_name', 'מערכת החידון');
            $m->from($fromEmail, $name);
            $m->replyTo('do-not-reply@mail4b2c.com',$name);
            $m->subject($subject);
            $m->to($email);
        });
    }
}
