<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>yahoo05</title>
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <p>
            <input type="text" name="query" />
            <input type="submit" value="送信" />
        </p>
    </form>
    <?php

    $q_query = $_POST["query"];
    if (isset($q_query)) {
        echo "<h1>" . $q_query . "</h1><hr>";

        function httpRequest($url)
        {
            $purl = parse_url($url);
            $psheme = $purl["scheme"];
            $phost = $purl["host"];

            if (!isset($purl["port"])) {
                $pport = 80;
            } else {
                $pport = $purl["port"];
            }

            if (!isset($purl["path"])) {
                $ppath = "/";
            } else {
                $ppath = $purl["path"];
            }

            echo "プロトコル：" . $psheme . "<br>";
            echo "ホスト名：" . $phost . "<br>";
            echo "ポート：" . $pport . "<br>";
            echo "パス：" . $ppath . "<br> <hr>";

            $hostname = $phost;
            $fp = fsockopen($hostname, $pport, $errno, $errstr);
            socket_set_timeout($fp, 10);

            $request = "GET " . $ppath . " HTTP/1.0\r\n\r\n";
            fputs($fp, $request);

            $response = "";
            while (!feof($fp)) {
                $response .= fgets($fp, 4096);
            }

            fclose($fp);

            $DATA = explode("\r\n\r\n", $response, 2);
            return $DATA;
        }

        function yahoo_mecab($data, $hinshi)
        {
            $api_key = "APIのID";
            $res = "surface,reading,pos,feature";

            $url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=" . $api_key . "&response=" . $res . "&sentence=" . urlencode($data);

            //リクエスト送信&レスポンス取得 
            $rss = file_get_contents($url);

            //取得したXMLを解析
            $xml = simplexml_load_string($rss);

            foreach ($xml->ma_result->word_list->word as $item) {
                $word = $item->feature;
                if (mb_ereg($hinshi, $word) == 1) {
                    echo $item->surface . "|" . $word;
                    echo "<br>";
                }
            }
        }

        list($head, $data) = httpRequest($q_query);
        //タグ除去
        $data = strip_tags($data);
        $hinshi = "名詞";

        $start = 0;
        //100KB=1024*100/2=51200文字だがfile_get_contents()制限の為 
        $size = 512;
        $end = mb_strlen($data);
        do {
            $data2 = mb_substr($data, $start - $end, $size);
            yahoo_mecab($data2, $hinshi);
            $start += $size;
        } while (($start - $size) <= $end);
    }

    ?>
</body>

</html>