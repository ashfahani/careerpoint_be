<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmailNotifReject($email_to, $data)
    {
        $subject = "[Notification] Student Career Point Rejection";

        $data['subject'] = $subject;
        $data['template'] = 'mails.notifReject';
        
        $status = Mail::to($email_to)->send(new SendEmail($data));

    }

    public function sendEmailNotifApprove($email_to, $data)
    {
        $subject = "[Notification] Student Career Point Approval";

        $data['subject'] = $subject;
        $data['template'] = 'mails.notifApprove';
        
        $status = Mail::to($email_to)->send(new SendEmail($data));

    }
}
