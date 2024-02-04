<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= SITE_NAME ?></title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="<?= BASE_URL; ?>public/css/style.css">
    <?php if(!empty($css)): ?>
        <link rel="stylesheet" href="<?= BASE_URL . 'public/' . $css; ?>">
    <?php endif; ?>
</head>
<body>
<div class="l-layout">
    <div class="l-layout__side">
        <nav class="l-nav">
            <ul class="l-nav__list">
                <li class="l-nav__item">
                    <a href="<?= BASE_URL ?>" class="l-nav__label">
                        <span class="material-symbols-outlined l-nav__icon">home</span>
                        ホーム
                    </a>
                </li>
                <li class="l-nav__item">
                    <a href="<?= BASE_URL ?>user/index/<?= CURRENT_USER_ID ?>" class="l-nav__label">
                        <span class="material-symbols-outlined l-nav__icon">person</span>
                        アカウント
                    </a>
                </li>
            </ul>
        </nav>
        <a href="<?= BASE_URL ?>user/tweet" class="c-button l-layout__tweet">ツイート</a>
    </div>
    <main class="l-layout__main">
        <?php require_once($viewFile) ?>
    </main>
</div>
<!-- JavaScript -->
<script>var CURRENT_USER_ID = <?= CURRENT_USER_ID ?> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script defer src="<?= BASE_URL; ?>public/js/app.min.js"></script>
<?php if(!empty($js)): ?>
    <script defer src="<?= BASE_URL . 'public/' . $js; ?>"></script>
<?php endif; ?>
</body>
</html>
