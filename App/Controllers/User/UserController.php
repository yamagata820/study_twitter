<?php

namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Models\Tweet;
use App\Models\User;

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index($id)
    {
        $title = 'アカウントページ';
        $user = $this->userModel->show($id);
        $tweets = new Tweet();
        $tweets = $tweets->getTweetByUserId(CURRENT_USER_ID,$id);

        $data = [
            'title' => $title,
            'user' => $user,
            'tweets' => $tweets
        ];

        // 定義した変数と対応するviewファイル名を渡す
        $this->view('user/user/index', $data);
    }

    public function tweet()
    {
        $title = 'ツイートする';

        $data = [
            'title' => $title,
        ];

        $this->view('user/user/tweet', $data);
    }
}
