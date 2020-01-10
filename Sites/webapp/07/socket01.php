<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>socket01</title>
</head>

<body>
    <?php
    $hostname = "www.google.co.jp";
    $fp = fsockopen($hostname, 80, $errno, $errstr);
    socket_set_timeout($fp, 10);

    $request = "GET /index.html HTTP/1.0\r\n\r\n";
    fputs($fp, $request);

    $response = "";
    while (!feof($fp)) {
        $response .= fgets($fp, 4096);
    }

    fclose($fp);

    echo $response;

    ?>
</body>

</html>