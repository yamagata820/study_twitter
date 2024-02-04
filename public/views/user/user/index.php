<?php
$userId = $user['id'];
?>

<div class="p-user">
    <div class="p-user__icon">
        <img src="<?= BASE_URL ?>public/images/user-icon/<?= $userId ?>.png" alt="アイコン">
    </div>
    <p class="p-user__name"><?= $user['user_name'] ?></p>
    <p class="p-user__bio"><?= $user['bio'] ?></p>
    <div class="p-user__info">
        <span class="p-user__following"><?= $user['following_count'] ?>フォロー中</span>
        <span class="p-user__follower"><?= $user['follower_count'] ?>フォロワー</span>
    </div>
    <div class="p-user__follow-button">
        <button <?= (int)$userId === CURRENT_USER_ID ? "disabled" : "" ?> class="c-button">フォロー</button>
    </div>
</div>

<?php foreach ($tweets as $tweet): ?>
    <?php include BASE_PATH.'public/views/parts/tweet.php' ?>
<?php endforeach; ?>
