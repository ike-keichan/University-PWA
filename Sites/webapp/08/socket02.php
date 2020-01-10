<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>socket02</title>
</head>

<body>
    <?php
    function httpRequest($url)
    {
        $purl = parse_url($url);
        print_r($purl);
    }

    $query = "http: //www. kyoto-su. ac. jp: 80/faculty/cse/index. html";
    httpRequest($query);

    ?>
</body>

</html>