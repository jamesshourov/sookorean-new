<?php

namespace App\Http\Controllers;

use App\Mail\EmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        return view('email.add');
    }

    public function send(Request $request)
    {
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            if ($user->language == 'ja') {
                if ($request->hasFile('attachment')) {
                    $mailData = array(
                        'attachment' => $request->file('attachment'),
                        'subject' => $request->title_japanese,
                        'message' => $request->description_japanese
                    );
                    try {
                        $email = $user->email;
                        Mail::to($email)->send(new EmailNotification($mailData));
                    } catch (\Exception) {
                        continue;
                    }
                } else {
                    $mailData = array(
                        'subject' => $request->title_japanese,
                        'message' => $request->description_japanese
                    );
                    try {
                        $email = $user->email;
                        Mail::to($email)->send(new EmailNotification($mailData));
                    } catch (\Exception) {
                        continue;
                    }
                }
            } elseif ($user->language == 'fr') {
                if ($request->hasFile('attachment')) {
                    $mailData = array(
                        'attachment' => $request->file('attachment'),
                        'subject' => $request->title_japanese,
                        'message' => $request->description_japanese
                    );
                    try {
                        $email = $user->email;
                        Mail::to($email)->send(new EmailNotification($mailData));
                    } catch (\Exception) {
                        continue;
                    }
                } else {
                    $mailData = array(
                        'subject' => $request->title_french,
                        'message' => $request->description_french
                    );
                    try {
                        $email = $user->email;
                        Mail::to($email)->send(new EmailNotification($mailData));
                    } catch (\Exception) {
                        continue;
                    }
                }
            }elseif ($user->language == 'sp') {
                if ($request->hasFile('attachment')) {
                    $mailData = array(
                        'attachment' => $request->file('attachment'),
                        'subject' => $request->title_spanish,
                        'message' => $request->description_spanish
                    );
                    try {
                        $email = $user->email;
                        Mail::to($email)->send(new EmailNotification($mailData));
                    } catch (\Exception) {
                        continue;
                    }
                } else {
                    $mailData = array(
                        'subject' => $request->title_spanish,
                        'message' => $request->description_spanish
                    );
                    try {
                        $email = $user->email;
                        Mail::to($email)->send(new EmailNotification($mailData));
                    } catch (\Exception) {
                        continue;
                    }
                }
            }else {
                if ($request->hasFile('attachment')) {
                    $mailData = array(
                        'attachment' => $request->file('attachment'),
                        'subject' => $request->title_english,
                        'message' => $request->description_english
                    );
                    try {
                        $email = $user->email;
                        Mail::to($email)->send(new EmailNotification($mailData));
                    } catch (\Exception) {
                        continue;
                    }
                } else {
                    $mailData = array(
                        'subject' => $request->title_english,
                        'message' => $request->description_english
                    );
                    try {
                        $email = $user->email;
                        Mail::to($email)->send(new EmailNotification($mailData));
                    } catch (\Exception) {
                        continue;
                    }
                }
            }
        }
        $status = true;
        $message = 'Ticket created';
        return response()->json(compact('status', 'message'));
    }
}
