<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Models\EmailFrame;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    public function sendResetLinkEmail(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);

        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => Str::random(60),
        ]);

        // Tạo thông tin email
        $link = url('/password/reset/' . $passwordReset->token . '?email=' . urlencode($user->email));
        $emailFrame=EmailFrame::where('type','forgot_password')->first();
        if($emailFrame){
            $body= $emailFrame->body . '</br>' . $link;
        }else{
            $emailFrame = new EmailFrame();
            $emailFrame->body=  $link;
            $emailFrame->subject = 'đặt lại mật khẩu';
            $emailFrame->type = 'forgot_password';
            $emailFrame->save();
            $body= $emailFrame->body;

        }
        $emailJob = new SendEmail($emailFrame,$user,$body);
        dispatch($emailJob);
        return response()->json(['message' => 'Password reset link sent successfully']);
    }
}
