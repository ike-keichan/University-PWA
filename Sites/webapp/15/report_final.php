<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        <!--
        .title {
            ont-size: 1.2em;
            background: #5fc2f5;
            padding: 4px;
            color: #FFF;
            font-weight: bold;
            letter-spacing: 0.05em;
        }

        .report-textbox {
            margin: 2em 0;
            background: #f1f1f1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.22);
        }

        .report_table {
            width: auto;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .network_analysis_table {
            width: auto;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .word_analysis_table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .word_analysis_table th,
        .word_analysis_table td {
            padding: 10px 0;
            text-align: center;
        }

        .word_analysis_table tr:nth-child(odd) {
            background-color: #eee
        }
        -->
    </style>
    <title>report_final</title>
</head>

<body>
    <h1 class="title">解析ページ(´・ω・｀)</h1>

    <div class="report-textbox">
        <table class="report_table">
            <tr>
                <td>学生証番号</td>
                <td>：</td>
                <td>あああああ</td>
            </tr>
            <tr>
                <td>名前</td>
                <td>：</td>
                <td>あああああ</td>
            </tr>
            <tr>
                <td>到達番号</td>
                <td>：</td>
                <td>⑥</td>
            </tr>
            <tr>
                <td>最終更新日</td>
                <td>：</td>
                <td>2019/1/20</td>
            </tr>
            <tr>
                <td>入力URL</td>
                <td>：</td>
                <td>「http://www.cc.kyoto-su.ac.jp/~atsushi/index-j.html」</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>「http://ylb.jp/」</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>「https://www.kyoto-su.ac.jp/entrance/index-ksu.html」</td>
            </tr>
            <tr>
                <td>補足</td>
                <td>：</td>
                <td>tf-idf値Top10のもののみを表示した場合、同率1位でtf-idf値が0のものが多数出現し、正しくtf-idf値を計算できているかわからなかったため全ての単語を出力しています。<br>一応、tf-idf値順にはソートできていますので勝手ながら課題⑥を終了したと判断しました。</td>
            </tr>
            <tr>
                <td>感想</td>
                <td>：</td>
                <td>半年間ありがとうございました。Webプログラミング楽しかったです。</td>
            </tr>
        </table>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <p>
            <input type="text" name="query" placeholder="解析するページのURL" />
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

            echo "<table class=\"network_analysis_table\">";
            echo "<tr> <td>プロトコル</td> <td>：</td> <td>" . $psheme . " </td> <tr>";
            echo "<tr> <td>ホスト名</td> <td>：</td> <td>" . $phost . " </td> <tr>";
            echo "<tr> <td>ポート</td> <td>：</td> <td>" . $pport . " </td> <tr>";
            echo "<tr> <td>パス</td> <td>：</td> <td>" . $ppath . " </td> <tr>";
            echo "</table> <hr>";

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
            $api_key = "APIのID";
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

        foreach ($word as $value) {
            $keyword = array_search($value, $word);
            if ($keyword === false) {
                $list_count_of_word["$value"] = 1;
                $list_count_of_document["$value"] = 1;
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
        $count_of_all_word = array_sum($list_count_of_word);
    ?>
        <table class="word_analysis_table">
            <tr>
                <th>順位（tf-idf値順）</th>
                <th>単語</th>
                <th>tf-idf値</th>
                <th>tf値</th>
                <th>df値</th>
                <th>idf値</th>
                <th>現在解析したページ内での出現回数</th>
                <th>解析した単語が出現したページ数</th>
            </tr>
        <?php

        foreach ($list_count_of_word as $keyword => $value) {
            $list_term_frequency["$keyword"] = $value / $count_of_all_word;
            $list_document_frequency["$keyword"] = log($list_count_of_document["$keyword"] / $count_of_all_document);

            if ($list_document_frequency["$keyword"] != 0) {
                $list_inverse_document_frequency["$keyword"] = 1 / $list_document_frequency["$keyword"];
            } else {
                $list_inverse_document_frequency["$keyword"] = 0;
            }

            $list_of_idf_df["$keyword"] = $list_term_frequency["$keyword"] * $list_inverse_document_frequency["$keyword"];
        }

        arsort($list_of_idf_df);
        $rank = 1;
        $count = 1;
        $prev_value = 1;

        foreach ($list_of_idf_df as $keyword => $value) {

            if ($value != $prev_value) {
                $rank = $count;
            }

            echo "<tr>";
            echo "<td>" . $rank . "</td>";
            echo "<td>" . $keyword . "</td>";
            echo "<td>" . $value . "</td>";
            echo "<td>" . $list_term_frequency["$keyword"] . "</td>";
            echo "<td>" . $list_document_frequency["$keyword"] . "</td>";
            echo "<td>" . $list_inverse_document_frequency["$keyword"] . "</td>";
            echo "<td>" . $list_count_of_word["$keyword"] . "</td>";
            echo "<td>" . $list_count_of_document["$keyword"] . "</td>";
            echo "</tr>";

            $count++;
            $prev_value = $value;
        }
    }
        ?>
        </table>
</body>

</html>