<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Validation;
use App\Models\Feedback;
use App\Models\User;

class PageController
{
    public function index()
    {
        return Response::view('home');
    }

    public function dashboard(Request $request)
    {
        if (!Auth::isAuthenticated()) {
            return Response::redirect('/login');
        }

        $feedbacks = Feedback::findAll('user_id', $request->user->id);
        return Response::view('dashboard', ['feedbacks' => $feedbacks]);
    }

    public function feedback(Request $request)
    {
        $user = User::find('id', $request->id);
        return Response::view('feedback', ['user' => $user]);
    }

    public function handleFeedback(Request $request)
    {
        $validation = Validation::make($request->all(), [
            'user_id' => ['required'],
            'feedback' => ['required'],
        ]);

        if ($validation->failed()) {
            flash_message('error', $validation->getMessage());
            return Response::redirect('/');
        }

        Feedback::create($validation->validatedData());

        return Response::redirect('/feedback-success');
    }

    public function feedbackSuccess()
    {
        return Response::view('feedback-success');
    }
    
    public function pageNotFound()
    {
        return Response::view('404');
    }
}
