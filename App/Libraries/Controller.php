<?php

namespace App\Libraries;

class Controller
{
    /**
     * viewを読み込む
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    public function view($view, $data = []):void
    {
        // 共通テンプレート内で読み込む
        $viewFile = BASE_PATH . "public/views/{$view}.php";

        // view側で$data['title']ではなく$titleと書けるよう、変数を定義
        foreach ($data as $key => $value) {
            ${$key} = $value;
        }

        // $dataはもう不要なので、view側で参照できないよう削除
        unset($data);

        // 共通テンプレートファイルを読み込む
        require_once BASE_PATH . 'public/views/template.php';
    }
}
