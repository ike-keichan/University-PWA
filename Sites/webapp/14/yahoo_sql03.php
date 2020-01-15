<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>yahoo_sql03</title>
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

        function insert_sql($table, $url, $contents)
        {
            try {
                $dbh = new PDO('sqlite:test.db', '', '');
                $sql = 'insert into list (url, contents) values (?, ?)';
                $sth = $dbh->prepare($sql);
                $sth->execute(array($url, $contents));

                $q = " '%t%' ";
                $sql = "select * from $table where contents like $q";
                $sth = $dbh->prepare($sql);
                $sth->execute();
            } catch (PDOException $e) {
                print "エラー!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        function yahoo_mecab($data, $hinshi, $url)
        {
            $q_url = $url;
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
                    //$contentsにsurfaceを代入 
                    $contents = $item->surface;
                    //insert_sqlの呼出し
                    insert_sql("list", $q_url, $contents);
                    $tango[] = "$contents";
                }
            }

            return $tango;
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
            $tango = yahoo_mecab($data2, $hinshi, $query);
            $start += $size;
            foreach ($tango as $val) {
                $word["$val"] = $val;
            }
        } while (($start - $size) <= $end);

        foreach ($word as $v) {
            $keyword = array_search($v, $word);
            if ($keyword === false) {
                $list_word["$v"] = 1;
            }
        }

        try {
            $dbh = new PDO("sqlite:test.db", '', '');
            foreach ($word as $keyword => $value) {
                $q = "\"" . $keyword . "\"";
                $sql = "select count(*) from list where contents like $q";
                $sth = $dbh->prepare($sql);
                $sth->execute();
                $cnt = $sth->fetchColumn();
                $list_word["$keyword"] = $cnt;
            }
        } catch (PDOException $e) {
            print "エラー!: " . $e->getMessage() . "<br/>";
            die();
        }

        arsort($list_word);
        foreach ($list_word as $k => $v) {
            echo $k . "|" . $v . "<br/>";
        }
    }

    ?>
</body>

</html>