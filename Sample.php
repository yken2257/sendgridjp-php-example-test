<?php
require "vendor/autoload.php";
use SendGrid\Mail\From;
use SendGrid\Mail\HtmlContent;
use SendGrid\Mail\Mail;
use SendGrid\Mail\PlainTextContent;
use SendGrid\Mail\Subject;
use SendGrid\Mail\To;

function sendMail() {
    // .envから環境変数読み込み
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $api_key      = $_ENV["API_KEY"];
    $from         = $_ENV["FROM"];
    $to           = explode(",", $_ENV["TOS"]);

    // リクエストパラメータの構築
    // 送信元
    $from = new From($from, "送信者名");
    // 宛先
    $tos = [
        new To(
            $to[0],
            "田中 太郎",
            [
                "%fullname%" => "田中 太郎",
                "%familyname%" => "田中",
                "%place%" => "中野"
            ]
        ),
        new To(
            $to[1],
            "佐藤 次郎",
            [
                "%fullname%" => "佐藤 次郎",
                "%familyname%" => "佐藤",
                "%place%" => "目黒"
            ]
        ),
        new To(
            $to[2],
            "鈴木 三郎",
            [
                "%fullname%" => "鈴木 三郎",
                "%familyname%" => "鈴木",
                "%place%" => "中野"
            ]
        )
    ];
    // 件名
    $subject = new Subject(
        "[sendgrid-php-example] フクロウのお名前は%fullname%さん"
    );
    // テキストパート
    $plainTextContent = new PlainTextContent(
        "%familyname% さんは何をしていますか？\r\n 彼は%place%にいます。"
    );
    // HTMLパート
    $htmlContent = new HtmlContent(
        "<strong> %familyname% さんは何をしていますか？</strong><br>彼は%place%にいます。"
    );
    // Mailオブジェクト生成
    $email = new Mail(
        $from,
        $tos,
        $subject,
        $plainTextContent,
        $htmlContent
    );
    // カテゴリ
    $email->addCategory("category1");
    // カスタムヘッダ
    $email->addHeader("X-Sent-Using", "SendGrid-API");
    // 添付ファイル
    $data = base64_encode(file_get_contents("./gif.gif"));
    $email->addAttachment(
        $data,
        "image/gif",
        "owl.gif",
        "attachment"
    );

    // デバッグプリント
    //print_r(json_encode($email->jsonSerialize(), JSON_PRETTY_PRINT));

    $sendgrid = new \SendGrid($api_key);
    try {
        // 送信
        $response = $sendgrid->send($email);
        // 結果出力
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
        return $response->statusCode();
    } catch (Exception $e) {
        echo "Caught exception: ".  $e->getMessage(). "\n";
    }
}