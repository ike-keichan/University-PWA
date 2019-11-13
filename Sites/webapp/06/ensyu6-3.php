<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>ensyu6-3</title>
</head>
<body>
    <?php
        class Person{
            var $name, $weight, $height, $bmi;

            function __Person($inputName, $inputWeight, $inputHeight){
                $this -> name = $inputName;
                $this -> weight = $inputWeight;
                $this -> height = $inputHeight;
            }

            function bmi_cal($who = ""){
                if($who == $this -> name){
                    $bmi = $this->weight / (2);
                    echo $who."のBMIは「".$bmi."」です。<br>" ; 
                }else if($who){
                    echo $who."のデータはありません。<br>" ;
                }else{
                    echo "名前が入力されていません。<br>";
                }
            }
        }

        $object1 = new Person("Keisuke", 56, 163);
        $object2 = new Person("Ayamoto", 60, 165);
        $object3 = new Person();
        $object1 -> bmi_cal("Keisuke");
        $object2 -> bmi_cal("Ayamoto");
        $object3 -> bmi_cal();
    ?>
</body>
</html>