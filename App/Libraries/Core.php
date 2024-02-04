<?php

namespace App\Libraries;

class Core {
    public function __construct()
    {
        // sessionを使用する場合は、ここでsession_startを宣言する

        // POSTリクエストであっても、クエリ文字列はGETで取れる
        $url = filter_input(INPUT_GET, 'url');

        // $urlをサニタイズした上で配列にする
        $url = $this->formatAndSanitizeUrl($url);

        // 定義済みルート一覧を取得
        $routes = $this->getRoutes();

        // URLによって呼び出すコントローラメソッドを特定
        $funcWithParams = $this->getControllerFromUrl($url, $routes);

        // controllerをインスタンス化し、methodに（あれば）paramsを渡して呼び出す
        call_user_func_array(
            [
                new $funcWithParams['controller'],
                $funcWithParams['method']
            ],
            $funcWithParams['params']
        );
    }

    /**
     * 1.URL末尾の/を削除
     * 2.値をサニタイズ（例えば日本語など、無効な文字を取り除く）
     * 3.配列に分割
     *
     * @param string|null $url
     * @return array
     */
    private function formatAndSanitizeUrl($url): array
    {
        // root（http://my_framework/）へのアクセスの場合は$urlがnullなので、空配列を返す
        if (!$url) return [];

        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        return $url;
    }

    /**
     * 定義済みルート一覧を定義
     *
     * tweet/indexへのgetリクエストであれば、User/TweetController/indexを取得することを想定
     *
     * @return array
     */
    private function getRoutes(): array
    {
        $routes = [
            'user' => [
                'tweet' => [
                    'get' => [
                        'show' => 'User/TweetController/show',
                    ],
                    'post' => [
                        'tweet' => 'User/TweetController/tweet',
                        'replay' => 'User/TweetController/replay',
                        'addLike' => 'User/TweetController/addLike',
                        'removeLike' => 'User/TweetController/removeLike',
                        'addReTweet' => 'User/TweetController/addReTweet',
                        'removeReTweet' => 'User/TweetController/removeReTweet',
                    ]
                ],
                'user' => [
                    'get' => [
                        'tweet' => 'User/UserController/tweet',
                        'index' => 'User/UserController/index',
                    ],
                ],
            ],
        ];

        return $routes;
    }

    /**
     * URLを取得し、対応するclassのmethodを呼び出す基幹処理
     *
     * ・$urlが空なら、homeページへ
     * ・$urlが定義済みルートにが存在しないurlなら、404を表示（今回は未実装）
     *
     * @return array
     */
    private function getControllerFromUrl(array $url, array $routes): array
    {
        // $urlが空ならrootなので、TOPページをリターン（今回はHomeControllerは未定義）
        if (count($url) === 0) {
            return [
                'controller' => "App\\Controllers\\HomeController",
                'method' => 'index',
                'params' => [],
            ];
        }

        // 今後管理画面やapiリクエストを追加することを想定し、namespaceに対応できるようにしておく
        // namespaceがurlにない場合はuser側と判断し、user namespaceを挿入
        if (!in_array($url[0], ['api', 'admin'], true)) {
            array_unshift($url, 'user');
        }

        // getもしくはpost
        $requestMethod = strtolower($_SERVER["REQUEST_METHOD"]);

        $namespace = $url[0] ?? null;
        $controller = $url[1] ?? null;
        $method = $url[2] ?? null;

        // 定義済みルートと照合
        $route = $routes[$namespace][$controller][$requestMethod][$method] ?? null;

        // 合致したルートに紐づくコントローラ・メソッドを取得
        if ($route) {
            [$namespace, $controller, $method] = explode('/', $route);

            return [
                'controller' => "App\\Controllers\\{$namespace}\\{$controller}",
                'method' => $method,
                'params' => array_slice($url, 3),
            ];
        } else {
            // 合致するルートがない場合は404（今回はErrorControllerは未定義）
            return [
                'controller' => "App\\Controllers\\User\\ErrorController",
                'method' => 'response404',
                'params' => [],
            ];
        }
    }
}
