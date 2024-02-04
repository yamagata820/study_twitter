<?php
namespace App\Models;

use App\Libraries\Model;

class Tweet extends Model
{
    public function __construct()
    {
        // PDOへの接続処理を実行
        parent::__construct();
    }

    public function tweet(int $currentUserId, string $tweet)
    {
        $sql = "INSERT INTO tweets
                (user_id,tweet)
                values(:currentUserId,:tweet)";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);
        $this->pdoStatement->bindValue(':tweet', $tweet);

        $this->pdoStatement->execute();
    }

    public function replay(int $currentUserId, string $tweet, int $replyTo)
    {
        $sql = "INSERT INTO tweets
                (user_id,tweet,reply_to)
                values(:currentUserId,:tweet,:replayTo)";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);
        $this->pdoStatement->bindValue(':tweet', $tweet);
        $this->pdoStatement->bindValue(':replayTo', $replyTo);

        $this->pdoStatement->execute();
    }

    public function show(int $currentUserId,int $tweetId)
    {
        $sql = "SELECT tweets.*,
        users.user_name,
        users.id AS user_id,
        COUNT(DISTINCT likes.id) like_count,
        COUNT(DISTINCT retweets.id) retweet_count,
        MAX(CASE WHEN likes.user_id = :currentUserId THEN 1 ELSE 0 END) AS is_liked,
        MAX(CASE WHEN retweets.user_id = :currentUserId THEN 1 ELSE 0 END) AS is_retweeted
        FROM tweets
        JOIN users ON tweets.user_id = users.id
        LEFT JOIN likes ON tweets.id = likes.tweet_id
        LEFT JOIN retweets ON tweets.id = retweets.tweet_id
        WHERE tweets.id = :tweet_id
        GROUP BY tweets.id";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);
        $this->pdoStatement->bindValue(':tweet_id', $tweetId);

        $this->pdoStatement->execute();

        return $this->pdoStatement->fetch();
    }

    public function getTweetByUserId(int $currentUserId,int $userId)
    {
        $sql = "SELECT tweets.*,
        users.user_name,
        users.id AS user_id,
        COUNT(DISTINCT likes.id) like_count,
        COUNT(DISTINCT retweets.id) retweet_count,
        MAX(CASE WHEN likes.user_id = :currentUserId THEN 1 ELSE 0 END) AS is_liked,
        MAX(CASE WHEN retweets.user_id = :currentUserId THEN 1 ELSE 0 END) AS is_retweeted
        FROM tweets
        JOIN users ON tweets.user_id = users.id
        LEFT JOIN likes ON tweets.id = likes.tweet_id
        LEFT JOIN retweets ON tweets.id = retweets.tweet_id
        WHERE (tweets.user_id = :userId
        OR retweets.user_id = :userId)
        AND tweets.reply_to IS NULL
        GROUP BY tweets.id
        ORDER BY tweets.created_at DESC;";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);
        $this->pdoStatement->bindValue(':userId', $userId);

        $this->pdoStatement->execute();

        return $this->pdoStatement->fetchAll();
    }

    public function getReplayByTweetId(int $currentUserId,int $tweetId)
    {
        $sql = "SELECT tweets.*,
        users.user_name,
        users.id AS user_id,
        COUNT(DISTINCT likes.id) like_count,
        COUNT(DISTINCT retweets.id) retweet_count,
        MAX(CASE WHEN likes.user_id = :currentUserId THEN 1 ELSE 0 END) AS is_liked,
        MAX(CASE WHEN retweets.user_id = :currentUserId THEN 1 ELSE 0 END) AS is_retweeted
        FROM tweets
        JOIN users ON tweets.user_id = users.id
        LEFT JOIN likes ON tweets.id = likes.tweet_id
        LEFT JOIN retweets ON tweets.id = retweets.tweet_id
        WHERE tweets.reply_to = :tweetId
        GROUP BY tweets.id
        ORDER BY tweets.created_at DESC;";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);
        $this->pdoStatement->bindValue(':tweetId', $tweetId);

        $this->pdoStatement->execute();

        return $this->pdoStatement->fetchAll();
    }

    public function addLike(int $currentUserId,int $tweetId)
    {
        $sql = "INSERT INTO likes
                (user_id,tweet_id)
                values(:currentUserId,:tweetId)";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);
        $this->pdoStatement->bindValue(':tweetId', $tweetId);

        $this->pdoStatement->execute();
    }

    public function removeLike(int $currentUserId,int $tweetId)
    {
        $sql = "DELETE FROM likes WHERE
                user_id = :currentUserId AND 
                tweet_id = :tweetId;";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);
        $this->pdoStatement->bindValue(':tweetId', $tweetId);

        $this->pdoStatement->execute();
    }

    public function addReTweet(int $currentUserId,int $tweetId)
    {
        $sql = "INSERT INTO retweets
                (user_id,tweet_id)
                values(:currentUserId,:tweetId)";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);
        $this->pdoStatement->bindValue(':tweetId', $tweetId);

        $this->pdoStatement->execute();
    }

    public function removeReTweet(int $currentUserId,int $tweetId)
    {
        $sql = "DELETE FROM retweets WHERE
                user_id = :currentUserId AND 
                tweet_id = :tweetId;";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);
        $this->pdoStatement->bindValue(':tweetId', $tweetId);

        $this->pdoStatement->execute();
    }
}
