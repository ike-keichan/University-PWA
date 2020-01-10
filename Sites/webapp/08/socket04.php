<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>socket04</title>
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
        echo "パス：" . $ppath . "<br>";
    }

    $query = "http: //www. kyoto-su. ac. jp";
    httpRequest($query);

    ?>
</body>

</html>