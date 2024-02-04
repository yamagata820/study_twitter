<?php
$tweetDateTime = new DateTimeImmutable($tweet['created_at']);
$now = new DateTimeImmutable();
$diff = $now->diff($tweetDateTime);
$userId = $tweet['user_id'];
$tweetId = $tweet['id']
?>
<article class="p-tweet" data-tweet-id="<?= $tweetId ?>">
    <a class="p-tweet__icon" href="<?= BASE_URL.'user/index/'.$userId ?>">
        <img src="<?= BASE_URL ?>public/images/user-icon/<?= $userId ?>.png" alt="アイコン">
    </a>
    <div class="p-tweet__inner">
        <a href="<?= BASE_URL.'tweet/show/'.$tweetId ?>" class="p-tweet__tweet-link">
            <div class="p-tweet__header">
                <p class="p-tweet__user-name"><?= $tweet['user_name'] ?></p>
                <time
                    datetime="<?= $tweetDateTime->format('Y-m-d H:i:s') ?>"
                    class="p-tweet__date-time">
                    <?= $tweetDateTime->format('Y-m-d') ?>
                </time>
            </div>
            <div class="p-tweet__body">
                <?= nl2br(htmlspecialchars($tweet['tweet'], ENT_QUOTES, 'UTF-8')); ?>
            </div>
            <div class="p-tweet__footer">
                <div class="p-tweet__footer-item">
                    <button
                        class="p-tweet__button <?= empty($tweet['is_liked']) ? '' : 'p-tweet__button--is-liked' ?>"
                        data-button-type="like"
                    >
                        いいね
                    </button>
                    <span class="p-tweet__count"><?= empty($tweet['like_count']) ? 0 : $tweet['like_count'] ?></span>いいね
                </div>
                <div class="p-tweet__footer-item">
                    <button
                        class="p-tweet__button <?= empty($tweet['is_retweeted']) ? '' : 'p-tweet__button--is-retweeted' ?>"
                        data-button-type="retweet"
                    >
                        リツイート
                    </button>
                    <span class="p-tweet__count"><?= empty($tweet['retweet_count']) ? 0 : $tweet['retweet_count'] ?></span>リツイート
                </div>
            </div>
        </a>
    </div>
</article>
