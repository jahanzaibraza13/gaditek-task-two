<?php

namespace App\Http\Controllers;

use App\Http\Services\EmailService;
use App\Models\Box;
use App\Models\Email;
use App\Models\EmailFile;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BoxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @param EmailService $emailService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $box = new Box();
        $box->name = $request->box_name;
        $box->user_id = Auth::id();
        $box->save();

        Session::flash('success', 'Box created successfully!');
        return redirect()->route('inbox');
    }

    public function emailBox($id, Request $request)
    {
        $boxId = $request->get('box_id');
        $email = Email::find($id);
        $email->box_id = $boxId;
        $email->save();

        Session::flash('success', 'Email box updated successfully!');
        return redirect()->route('inbox');
    }
}
