<h3>SQLiteとの接続テスト</h3>
<?php
try{
    $pdh = new PDO('sqlite:test.db');
    $sth = $pdh->prepare("select * from students");
    $sth->execute();

    while ($row = $sth->fetch()){
        echo $row['id']. $row['name']. "<br>";
    }
} Catch (PDOException $e) {
    print "error! ". $e->getMessage(). "<br>";
    die();
}

?>
