<?php
$files = [
    // 設定ファイル
    'config.php',
    // helperがあればここで読み込む
];

foreach ($files as $file) {
    require_once $file;
}

// autoloader
// 未定義のclassが呼ばれた時に引数のcallbackが実行される。$classNameには呼ばれたclass名が入る
spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);

    require_once BASE_PATH . "{$className}.php";
});
