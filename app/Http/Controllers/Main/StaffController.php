<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\Request;
use App\Models\User;

class StaffController extends Controller
{
    public function getCustomers(Request $request)
    {
        $customers = User::usertype('Customer')->get();

        $return['customers'] = $customers;
        return $this->resSuccessJson($return);
    }

    public function getCustomersDeleted(Request $request)
    {
        $customers = User::usertype('Customer')->onlyTrashed()->get();

        $return['customers'] = $customers;
        return $this->resSuccessJson($return);
    }

    public function getChatHistory(Request $request)
    {
        $messages = Messages::with('sender')
                    ->where(function ($q) {
                        $q->whereNull('recipient_id')->orWhere('recipient_id', auth()->user()->id);
                    })
                    ->get();

        $return['messages'] = $messages;
        return $this->resSuccessJson($return);
    }

    public function sendMessageToStaff(Request $request)
    {
        $validator = $this->validate($request, [
            'recipient_mail' => 'required|email',
        ]);

        $title = $request->title ?? '';
        $description = $request->description ?? '';
        $recipient_mail = $request->recipient_mail;

        $recipient = User::usertype('Staff')->where('email', $recipient_mail)->first();

        $check = $this->recipientCheck($recipient);
        if (is_string($check)) {
            return $this->resErrorJson([], $check, 400);
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

    public function sendMessageToCustomer(Request $request)
    {
        $validator = $this->validate($request, [
            'recipient_mail' => 'required|email',
        ]);

        $title = $request->title ?? '';
        $description = $request->description ?? '';
        $recipient_mail = $request->recipient_mail;

        $recipient = User::usertype('Customer')->where('email', $recipient_mail)->first();
        
        $check = $this->recipientCheck($recipient);
        if (is_string($check)) {
            return $this->resErrorJson([], $check, 400);
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

    public function deleteCustomer(Request $request)
    {
        $validator = $this->validate($request, [
            'customer_id' => 'required',
        ]);
        
        $customer_id = $request->customer_id;
        
        try {
            $customer = User::usertype('Customer')->where('id', $customer_id)->first();
    
            if (empty($customer)) {
                return $this->resErrorJson([], "User not found", 400);
            }

            $customer->delete();

            return $this->resSuccessJson([]);
        } catch (\Exception $e) {
            return $this->resErrorJson($e->getMessage(), 'Internal server error');
        }
    }

    private function recipientCheck($recipient)
    {
        if (empty($recipient)) {
            return 'User not found';
        }

        if ($recipient->id == auth()->user()->id) {
            return "Can't send to own";
        }

        return true;
    }
}
