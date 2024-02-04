<?php
namespace App\Models;

use App\Libraries\Model;

class User extends Model
{
    public function __construct()
    {
        // PDOへの接続処理を実行
        parent::__construct();
    }

    public function show(int $userId)
    {
        $sql = "SELECT users.*,
        COUNT(DISTINCT following.id) AS following_count,
        COUNT(DISTINCT follower.id) AS follower_count
        FROM users
        LEFT JOIN follows AS following ON users.id = following.follower_id
        LEFT JOIN follows AS follower ON users.id = follower.following_id
        WHERE users.id = :userId";

        $this->pdoStatement = $this->pdo->prepare($sql);
        $this->pdoStatement->bindValue(':userId', $userId);

        $this->pdoStatement->execute();

        return $this->pdoStatement->fetch();
    }
}
