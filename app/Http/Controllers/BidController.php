<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Bid_message;
use App\Models\crop_import;
use App\Models\farmer_register;
use App\Models\user_register;
use App\Models\pay_confirm_message;
use App\Models\pay_info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Illuminate\Foundation\Validation\ValidatesRequests;

class BidController extends Controller
{
    use ValidatesRequests;
    /**
     * Show bid model for a crop
     */
    public function Bid_model($id)
    {
        $crop   = crop_import::findOrFail($id);
        $owners = Bid_message::where('crop_id', $crop->id)->max('bid_price');

        return view('buyer.Bid_model', compact('crop', 'owners'));
    }

    /**
     * Save a new bid and send notification to farmer
     */
    public function bid_msg_save(Request $request)
    {
        $this->validate($request, [
            // add real validation rules later if needed
            'crop_id'      => 'required|integer',
            'crop_name'    => 'required|string',
            'f_username'   => 'required|string',
            'cust_username'=> 'required|string',
            'name'         => 'required|string',
            'bid_price'    => 'required|numeric',
        ]);

        if (empty($request->input('message'))) {
            $request->merge(['message' => 'null']);
        }

        $result = new Bid_message();
        $result->crop_id       = $request->crop_id;
        $result->crop_name     = $request->crop_name;
        $result->f_username    = $request->f_username;
        $result->cust_username = $request->cust_username;
        $result->name          = $request->name;
        $result->bid_price     = $request->bid_price;
        $result->message       = $request->message;
        $result->save();

        $farm  = farmer_register::where('username', $request->f_username)->first();
        $data  = $result->toArray();
        $data2 = $farm ? $farm->toArray() : [];

        if ($farm) {
            // Make sure view exists: resources/views/farmer/Bid_notification.blade.php
            Mail::send('farmer.Bid_notification', ['val' => $data], function ($mail) use ($data2) {
                $mail->to($data2['email']);
                $mail->subject('Bid_notification');
            });
        }

        return redirect('/')->with('msg', 'Your bid was sent successfully');
    }

    /**
     * Save a new bid but return to crop details page
     */
    public function bid_msg_saved(Request $request)
    {
        $this->validate($request, [
            'crop_id'      => 'required|integer',
            'crop_name'    => 'required|string',
            'f_username'   => 'required|string',
            'cust_username'=> 'required|string',
            'name'         => 'required|string',
            'bid_price'    => 'required|numeric',
        ]);

        if (empty($request->input('message'))) {
            $request->merge(['message' => 'null']);
        }

        $result = new Bid_message();
        $result->crop_id       = $request->crop_id;
        $result->crop_name     = $request->crop_name;
        $result->f_username    = $request->f_username;
        $result->cust_username = $request->cust_username;
        $result->name          = $request->name;
        $result->bid_price     = $request->bid_price;
        $result->message       = $request->message;
        $result->save();

        // If you want to send mail here, uncomment and follow same as above
        // ...

        return redirect()->route('crop_details', ['id' => $request->crop_id])
                         ->with('msg', 'Your bid was sent successfully');
    }

    /**
     * Delete a bid for a crop
     */
    public function bid_delete($id, $crop_id)
    {
        $bid = Bid_message::findOrFail($id);
        $bid->delete();

        $crop     = crop_import::findOrFail($crop_id);
        $bids_msg = Bid_message::where('crop_id', $crop_id)->get();

        return view('home.crop_details', [
            'crop'     => $crop,
            'bids_msg' => $bids_msg
        ])->with('msg', 'Bid deleted successfully');
    }

    /**
     * Save payment confirmation message
     */
    public function pay_confirm_message(Request $request)
    {
        $this->validate($request, [
            'account_id'   => 'regex:/^((01|8801)[3456789]\d{8})$/',
            'crop_id'      => 'required|integer',
            'f_username'   => 'required|string',
            'crop_name'    => 'required|string',
            'cust_username'=> 'required|string',
            'account_type' => 'required|string',
            'confirm_price'=> 'required|numeric',
        ]);

        if (empty($request->input('message'))) {
            $request->merge(['message' => 'null']);
        }

        $pay_info = new pay_confirm_message();
        $pay_info->crop_id       = $request->crop_id;
        $pay_info->f_username    = $request->f_username;
        $pay_info->crop_name     = $request->crop_name;
        $pay_info->cust_username = $request->cust_username;
        $pay_info->account_type  = $request->account_type;
        $pay_info->account_id    = $request->account_id;
        $pay_info->confirm_price = $request->confirm_price;
        $pay_info->message       = $request->message;
        $pay_info->save();

        // If you want to send email, use similar Mail::send() as above

        return redirect()->back()->with('msg', 'Your confirm message was sent successfully');
    }
}
