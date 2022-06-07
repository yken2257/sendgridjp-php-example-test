# sendgridjp-php-example-test
[SendGridJPのPHPサンプルコード](https://github.com/SendGridJP/sendgridjp-php-example)の動作確認のためのリポジトリです。

## 概要
CircleCI上でPHPとSendGrid公式ライブラリ(Ver.~8)をインストールし、サンプルコードの動作検証をします。
具体的には、サンプルコードの最後でHTTPレスポンスコード202が返ってくればテスト成功とみなします。

- Sample.php: [サンプルコード](https://github.com/SendGridJP/sendgridjp-php-example/blob/master/sendgrid-php-example.php)をモジュール化したもの
- SampleTest.php: 上記をPHPUnitでテストするためのスクリプト
- .circleci/config.yml: CircleCI設定（環境設定、環境変数設定、テストののち、用いたバージョンを表示します。毎月2日の午前9時に定期実行されます。）

（手動でテストする場合の手順）

```bash
# .envファイルを編集
echo "API_KEY=$SENDGRID_API_KEY" >> .env
echo "TOS=alice@sink.sendgrid.net,bob@sink.sendgrid.net,carol@sink.sendgrid.net" >> .env
echo "FROM=you@example.com" >> .env
composer install
./vendor/bin/phpunit SampleTest.php
# version
php --version
cat composer.lock | jq -r '.packages[] | select(.name == "sendgrid/sendgrid") | .version'
```