<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>form01</title>
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
    }
    ?>
</body>

</html>