<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>socket05</title>
</head>

<body>
    <?php
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
        echo $response;
    }

    $query = "http://www.kyoto-su.ac.jp/faculty/cse/index.html";
    httpRequest($query);

    ?>
</body>

</html>