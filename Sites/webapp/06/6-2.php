<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>ensyu6-2</title>
</head>

<body>
    <?php
    class Person
    {
        var $name = "Keisuke";
        var $weight = 56;
        var $height = 163;
        var $bmi;

        function bmi_cal($who = "")
        {
            if ($who == $this->name) {
                $bmi = $this->weight / (2);
                echo $who . "のBMIは「" . $bmi . "」です。<br>";
            } else if ($who) {
                echo $who . "のデータはありません。<br>";
            } else {
                echo "名前が入力されていません。<br>";
            }
        }
    }

    $object = new Person;
    $object->bmi_cal("Keisuke");
    $object->bmi_cal("Ayamoto");
    $object->bmi_cal();
    ?>
</body>

</html>