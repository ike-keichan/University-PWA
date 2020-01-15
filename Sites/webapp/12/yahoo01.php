<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>yahoo01</title>
</head>

<body>
    <?php
    $api_key = "APIのID";
    $query = "冬の京都も美しい。";

    $url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=" . $api_key . "&sentence=" . urlencode($query);

    //これだと日本語を取得できない。「https://developer.yahoo.co.jp/sample/jlp/sample2.html」を見たらダメみたい。
    //$url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=" . $api_key . "&sentence=" . $query;

    //リクエスト送信&レスポンス取得 
    $rss = file_get_contents($url);

    //取得したXMLを解析
    $xml = simplexml_load_string($rss);

    foreach ($xml->ma_result->word_list->word as $item) {
        echo $item->surface . "|" . $item->pos;
        echo "<br>";
    }
    ?>
</body>

</html>