<?php
namespace App\Controllers;

use App\Libraries\Controller;
use App\Models\TimeLine;
use App\Models\Tweet;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'タイムライン';
        $model = new TimeLine();
        $tweets = $model->getTimeLine(CURRENT_USER_ID);

        $data = [
            'title' => $title,
            'tweets' => $tweets
        ];

        // 定義した変数と対応するviewファイル名を渡す
        $this->view('index', $data);
    }
}
