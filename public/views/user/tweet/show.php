<?php
$tweetDateTime = new DateTimeImmutable($tweet['created_at']);
$now = new DateTimeImmutable();
$diff = $now->diff($tweetDateTime);
$userId = $tweet['user_id'];
$tweetId = $tweet['id']
?>

<?php include BASE_PATH . 'public/views/parts/tweet.php' ?>

<form method="POST" action="<?= BASE_URL ?>tweet/replay" class="p-replay-form">
    <p class="p-replay-form__title">リプライを送信</p>
    <input name="reply_to" hidden value="<?= $tweetId ?>">
    <textarea name="tweet" class="p-replay-form__textarea" rows="3"></textarea>
    <button class="c-button p-replay-form__button">送信</button>
</form>

<?php if (!empty($replays)): ?>
    <?php $tweet = [] ?>
    <div class="p-replay-list">
        <p class="p-replay-list__title">リプライ</p>
        <?php foreach ($replays as $tweet): ?>
            <?php include BASE_PATH . 'public/views/parts/tweet.php' ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
