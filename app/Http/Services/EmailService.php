<?php


namespace App\Http\Services;

use App\Models\Email;
use App\Models\EmailFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailService
{
    const FILES_DIR = "email_files";

    /**
     * @param Request $request
     * @param $toUser
     */
   public function saveEmail(Request $request, $toUser)
   {
       $email = new Email();

       $email->user_id = Auth::id();
       $email->to_user_id = $toUser->id;
       $email->subject = $request->subject;
       $email->content = $request->content;

       $email->save();

       $attachment = $request->file('attachment');
       if ($attachment) {
           $emailFile = new EmailFile();
           $fileNameToStore = rand(1,99) . '_' . $attachment->getClientOriginalName();
           $attachment->storeAs('email-files', $fileNameToStore);

           $emailFile->path = self::FILES_DIR . '/' . $fileNameToStore;
           $emailFile->email_id = $email->id;

           $emailFile->save();
       }
   }
}
