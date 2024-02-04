<?php
// file読み込み系の処理
require_once '../App/bootstrap.php';

// session_startやcsrfの処理、URLに対応したコントローラ呼び出し
new App\Libraries\Core();
