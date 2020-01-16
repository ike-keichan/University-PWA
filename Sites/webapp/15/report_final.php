<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>report_final</title>
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <p>
            <input type="text" name="query" />
            <input type="submit" value="送信" />
        </p>
    </form>
    <?php

    //送信されたURLを変数に格納
    $q_query = $_POST["query"];

    if (isset($q_query)) {
        echo "<h1>" . $q_query . "</h1><hr>";

        //レスポンスを受けて解析を行う関数
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

            $fp = fsockopen($phost, $pport, $errno, $errstr);
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

        //送信されたURLと解析した単語をデータベースに格納
        function insert_sql($table, $url, $contents)
        {
            try {
                $dbh = new PDO('sqlite:test.db', '', '');
                $sql = "insert into $table (url, contents) values (?, ?)";
                $sth = $dbh->prepare($sql);
                $sth->execute(array($url, $contents));

                $q = " \"%t%\" ";
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
            $query_url = $url;
            $api_key = "dj00aiZpPXNSeWx4V2h5cjh0RiZzPWNvbnN1bWVyc2VjcmV0Jng9YmY-";
            $res = "surface,reading,pos,feature";

            $url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=" . $api_key . "&response=" . $res . "&sentence=" . urlencode($data);

            //リクエスト送信&レスポンス取得 
            $rss = file_get_contents($url);

            //取得したXMLを解析
            $xml = simplexml_load_string($rss);

            foreach ($xml->ma_result->word_list->word as $item) {
                $check_word = $item->feature;
                if (mb_ereg($hinshi, $check_word) == 1) {
                    //$contentsにsurfaceを代入 
                    $contents = $item->surface;
                    //insert_sqlの呼出し
                    insert_sql("list", $query_url, $contents);
                    $tango[] = "$contents";
                }
            }

            return $tango;
        }

        //関数の引数をリストに格納
        list($head, $data) = httpRequest($q_query);

        //タグ除去
        $data = strip_tags($data);
        $start = 0;
        $end = mb_strlen($data);
        //100KB=1024*100/2=51200文字だがfile_get_contents()制限の為 
        $size = 512;

        do {
            $data2 = mb_substr($data, $start - $end, $size);
            $tango = yahoo_mecab($data2, "名詞", $q_query);
            $start += $size;
            foreach ($tango as $val) {
                $word["$val"] = $val;
            }
        } while (($start - $size) <= $end);

        foreach ($word as $v) {
            $keyword = array_search($v, $word);
            if ($keyword === false) {
                $list_count_of_word["$v"] = 1;
                $list_count_of_document["$v"] = 1;
            }
        }

        try {
            $dbh = new PDO("sqlite:test.db", '', '');
            foreach ($word as $keyword => $value) {
                $q = "\"" . $keyword . "\"";
                $sql = "select count(*) from list where contents like $q";
                $sth = $dbh->prepare($sql);
                $sth->execute();
                $count_of_word = $sth->fetchColumn();
                $list_count_of_word["$keyword"] = $count_of_word;

                $sql = "select count(distinct url) from list where contents = $q";
                $sth = $dbh->prepare($sql);
                $sth->execute();
                $count_of_document = $sth->fetchColumn();
                $list_count_of_document["$keyword"] = $count_of_document;
            }

            $sql = "select count(distinct url) from list";
            $sth = $dbh->prepare($sql);
            $sth->execute();
            $count_of_all_document = $sth->fetchColumn();
        } catch (PDOException $e) {
            print "エラー!: " . $e->getMessage() . "<br/>";
            die();
        }

        arsort($list_count_of_word);
        $rank = 1;
        $count_of_all_word = array_sum($list_count_of_word);
    ?>
        <table>
            <tr>
                <th>順位</th>
                <th>単語</th>
                <th>解析ページでの出現回数</th>
                <th>tf値</th>
                <th>df値</th>
                <th>idf値</th>
                <th>tf-idf値</th>
            </tr>
        <?php

        foreach ($list_count_of_word as $k => $v) {
            $term_frequency = $v / $count_of_all_word;
            $document_frequency = log($list_count_of_document["$k"] / $count_of_all_document);

            if ($document_frequency != 0) {
                $inverse_document_frequency = 1 / $document_frequency;
            } else {
                $inverse_document_frequency = 0;
            }

            $list_of_idf_df["$k"] = $term_frequency * $inverse_document_frequency;

            echo "<tr>";
            echo "<td>" . $rank . "</td>";
            echo "<td>" . $k . "</td>";
            echo "<td>" . $v . "</td>";
            echo "<td>" . $term_frequency . "</td>";
            echo "<td>" . $document_frequency . "</td>";
            echo "<td>" . $inverse_document_frequency . "</td>";
            echo "<td>" . $list_of_idf_df["$k"] . "</td>";
            echo "</tr>";
            $rank++;
            if ($rank > 10) {
                break;
            }
        }
    }
        ?>
        </table>
</body>

</html>