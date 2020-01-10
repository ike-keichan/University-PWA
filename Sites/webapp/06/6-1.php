<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>ensyu6-1</title>
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
            $bmi = $this->weight / (2);
            echo $who . "のBMIは「" . $bmi . "」です。<br>";
        }
    }

    $object = new Person;
    $object->bmi_cal("Keisuke");
    ?>
</body>

</html>