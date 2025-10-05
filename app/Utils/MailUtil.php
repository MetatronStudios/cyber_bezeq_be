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
        if ( env('MAIL_DISABLED') ) {
            return;
        }

        Mail::send($view, $data, function ($m) use ($email, $subject) {
            $fromEmail =env('PROJECT_EMAILS_FROM_MAIL')?env('PROJECT_EMAILS_FROM_MAIL'):'do-not-reply@mail4b2c.com';
            $name = env('PROJECT_EMAILS_FROM_NAME')?env('PROJECT_EMAILS_FROM_NAME'):'מערכת החידון';
            $m->from($fromEmail, $name);
            $m->replyTo('do-not-reply@mail4b2c.com',$name);
            $m->subject($subject);
            $m->to($email);
        });
    }
}
