<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Messages;
use App\Models\User;

class CustomerController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validator = $this->validate($request, [
            'recipient_mail' => 'required|email',
        ]);

        $title = $request->title ?? '';
        $description = $request->description ?? '';
        $recipient_mail = $request->recipient_mail;

        $recipient = User::where('email', $recipient_mail)->first();
        if (empty($recipient)) {
            return $this->resErrorJson([], 'User not found', 400);
        }

        if ($recipient->id == auth()->user()->id) {
            return $this->resErrorJson([], "Can't send to own", 400);
        }

        try {
            $message = new Messages();
            $message->recipient_id = $recipient->id;
            $message->message_type = 'chat';
            $message->title = $title;
            $message->description = $description;
            $message->status = 'sent';
            $message->created_by = auth()->user()->id;
            $message->save();

            $return['message'] = $message;
            return $this->resSuccessJson($return, 'Message sent');
        } catch (\Exception $e) {
            return $this->resErrorJson($e->getMessage(), 'Internal server error');
        }
    }

    public function sendFeedback(Request $request)
    {
        $title = $request->title ?? '';
        $description = $request->description ?? '';

        try {
            $message = new Messages();
            $message->recipient_id = null;
            $message->message_type = 'feedback';
            $message->title = $title;
            $message->description = $description;
            $message->status = 'sent';
            $message->created_by = auth()->user()->id;
            $message->save();

            $return['message'] = $message;
            return $this->resSuccessJson($return, 'Message sent');
        } catch (\Exception $e) {
            return $this->resErrorJson($e->getMessage(), 'Internal server error');
        }
    }

    public function getChatHistory(Request $request)
    {
        $user_id = auth()->user()->id;
        $messages = Messages::with('recipient')->where('created_by', $user_id)->get();

        $return['messages'] = $messages;
        return $this->resSuccessJson($return);
    }
}
