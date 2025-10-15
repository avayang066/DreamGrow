<?php

use Illuminate\Support\Facades\Mail;

class MailNotificationService
{
    private $request;
    private $response;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function sendMail()
    {
        // $to = $this->request->input('to_mail');
        // $subject = $this->request->input('subject', '預設主旨');
        // $body = $this->request->input('body', '預設內容');

        $to ='zxc5566230@gmail.com';
        $subject = '您已經很久沒有記錄日誌了';
        $body = '測試信件';

        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)
                    ->subject($subject);
        });

        $this->response = [
            'status' => 'success',
            'message' => 'Email sent successfully'
        ];
        return $this;
    }

    public function getResponse()
    {
        return response()->json($this->response);
    }
}