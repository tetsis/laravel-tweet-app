<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Email\SendRequest;
use App\Mail\TestMailFromOutlook;
use Illuminate\Contracts\Mail\Mailer;

class SendController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SendRequest $request, Mailer $mailer)
    {
        $mailer->to($request->email())
               ->send(new TestmailFromOutlook());

        return redirect()->route('email.index')
                         ->with('feedback.success', "メールを送信しました。");
    }
}
