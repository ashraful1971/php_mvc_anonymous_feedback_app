<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validation;
use App\Models\Feedback;
use App\Models\User;

class PageController
{
    /**
     * Get home page view
     *
     * @return mixed
     */
    public function index(): mixed
    {
        return Response::view('home');
    }

    /**
     * Get dashboard page view
     *
     * @param Request $request
     * @return mixed
     */
    public function dashboard(Request $request): mixed
    {
        $feedbacks = Feedback::findAll('user_id', $request->user->id);
        return Response::view('dashboard', ['feedbacks' => $feedbacks]);
    }

    /**
     * Get feedback page view
     *
     * @param Request $request
     * @return mixed
     */
    public function feedback(Request $request): mixed
    {
        $user = User::find('id', $request->id);

        if(!$user){
            return Response::redirect('/404');
        }

        return Response::view('feedback', ['user' => $user]);
    }

    /**
     * Store new feedback
     *
     * @param Request $request
     * @return mixed
     */
    public function handleFeedback(Request $request): mixed
    {
        $data = $this->validatedFeedbackData($request->all());

        Feedback::create($data);

        return Response::redirect('/feedback-success');
    }

    /**
     * Get feedback success page view
     *
     * @return mixed
     */
    public function feedbackSuccess(): mixed
    {
        return Response::view('feedback-success');
    }

    /**
     * Get 404 page view
     *
     * @return mixed
     */
    public function pageNotFound(): mixed
    {
        return Response::view('404');
    }
    
    /**
     * Validate and return the validated feedback data
     *
     * @param array $data
     * @return mixed
     */
    private function validatedFeedbackData(array $data): mixed
    {
        $validation = Validation::make($data, [
            'user_id' => ['required'],
            'feedback' => ['required'],
        ]);

        if ($validation->failed()) {
            flash_message('error', $validation->getMessage());
            return Response::redirect('/');
        }

        return $validation->validatedData();
    }
}
