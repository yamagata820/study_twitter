<?php
namespace App\Models;

use App\Libraries\Model;

class TimeLine extends Model
{
    public function __construct()
    {
        // PDOへの接続処理を実行
        parent::__construct();
    }

    public function getTimeLine(int $currentUserId)
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
        JOIN follows ON tweets.user_id = follows.following_id
        LEFT JOIN likes ON tweets.id = likes.tweet_id
        LEFT JOIN retweets ON tweets.id = retweets.tweet_id
        WHERE (follows.follower_id = :currentUserId
        OR tweets.user_id = :currentUserId)
        AND tweets.reply_to IS NULL
        GROUP BY tweets.id
        ORDER BY tweets.created_at DESC;";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':currentUserId', $currentUserId);

        $this->pdoStatement->execute();

        return $this->pdoStatement->fetchAll();
    }
}
