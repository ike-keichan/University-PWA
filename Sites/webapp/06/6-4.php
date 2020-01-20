<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>6-4</title>
</head>

<body>
    <?php
    class Person
    {
        var $name, $weight, $height, $bmi;

        function __Person($input_name, $input_weight, $input_height)
        {
            $this->name = $input_name;
            $this->weight = $input_weight;
            $this->height = $input_height;
        }

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

    class Personal_info extends Person
    {
        var $age;

        function bmi_age($age = " ")
        {
            if ($this->age < 30 && $this->bmi >= 24.0) {
                echo "メタボ！";
            } else if ($this->age >= 30 && $this->bmi >= 27.0) {
                echo "メタボ！";
            } else {
                echo "メタボじゃないです！";
            }
        }
    }

    $object = new Personal_info("Taro", 65, 1.7);
    $object->bmi_cal("Taro");
    $object->bmi_age(22);

    ?>
</body>

</html>