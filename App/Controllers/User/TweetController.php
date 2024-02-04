<?php

namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Models\Tweet;

class TweetController extends Controller
{

    private Tweet $tweetModel;

    public function __construct()
    {
        $this->tweetModel = new Tweet();
    }

    private function checkTweet(string $tweet)
    {
        return strlen($tweet) >= 280;
    }

    public function tweet()
    {
        $tweet = $_POST['tweet'];
        if($this->checkTweet($tweet)) {
            return false;
        }

        $this->tweetModel->tweet(CURRENT_USER_ID,$tweet);
        $move = BASE_URL;
        header("Location: $move");
    }

    public function replay()
    {
        $tweet = $_POST['tweet'];
        $replyTo = $_POST['reply_to'];
        if($this->checkTweet($tweet)) {
            return false;
        }

        $this->tweetModel->replay(CURRENT_USER_ID,$tweet,$replyTo);
        $move = BASE_URL."tweet/show/$replyTo";
        header("Location: $move");
    }

    public function show($id)
    {
        $title = 'ツイート';

        $tweet = $this->tweetModel->show(CURRENT_USER_ID,$id);
        $replays = new Tweet();
        $replays = $replays->getReplayByTweetId(CURRENT_USER_ID,$id);

        $data = [
            'title' => $title,
            'tweet' => $tweet,
            'replays' => $replays
        ];

        $this->view('user/tweet/show', $data);
    }

    public function addLike()
    {
        $tweetId = $_POST['tweet_id'];
        $this->tweetModel->addLike(CURRENT_USER_ID,$tweetId);
    }

    public function removeLike()
    {
        $tweetId = $_POST['tweet_id'];
        $this->tweetModel->removeLike(CURRENT_USER_ID,$tweetId);
    }

    public function addReTweet()
    {
        $tweetId = $_POST['tweet_id'];
        $this->tweetModel->addReTweet(CURRENT_USER_ID,$tweetId);
    }

    public function removeReTweet()
    {
        $tweetId = $_POST['tweet_id'];
        $this->tweetModel->removeReTweet(CURRENT_USER_ID,$tweetId);
    }
}
