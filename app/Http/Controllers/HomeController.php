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

class HomeController extends Controller
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect()->route('inbox');
    }
    /**
     * @param Request $request
     * @return Renderable
     */
    public function inbox(Request $request): Renderable
    {
        $sent = $request->get('sent', false);
        $boxId = $request->get('box_id');
        if ($sent) {
            $emails = Email::with('user')->with('toUser')->where('user_id', '=', Auth::id())
                ->orderByDesc('id')->get();
        } elseif ($boxId) {
            $emails = Email::with('user')->where('box_id', '=', $boxId)
                ->orderByDesc('id')->get();
        } else {
            $emails = Email::with('user')->where('to_user_id', '=', Auth::id())
                ->orderByDesc('id')->get();
        }

        $boxes = Box::where('user_id', '=', Auth::id())->get();
        return view('welcome', ['emails' => $emails, 'boxes' => $boxes]);
    }

    /**
     * @param Request $request
     * @param EmailService $emailService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveEmail(Request $request, EmailService $emailService)
    {
        $toUser = User::where('email', '=', $request->to)->first();
        if (empty($toUser)) {
            Session::flash('alert-class', 'User not found!');
            return redirect()->route('inbox');
        }

        $emailService->saveEmail($request, $toUser);

        Session::flash('success', 'Email sent!');
        return redirect()->route('inbox');
    }

    /**
     * @param $id
     * @param Request $request
     * @param EmailService $emailService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function readEmail($id)
    {
        $email = Email::find($id);
        $boxes = Box::where('user_id', '=', Auth::id())->get();

        return view('read_email', ['email' => $email, 'boxes' => $boxes]);
    }
}
