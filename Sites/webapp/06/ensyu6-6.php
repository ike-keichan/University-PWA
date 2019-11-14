<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>ensyu6-6</title>
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

        class Personal_info extends Person{ 
            var $age;

            function bmi_cal($who = ""){
                echo "Personal_infoによるオーバライド<br>";
                parent::bmi_cal($who); 
            }

            function bmi_age($age = " "){
                if( $this -> age < 30 && $this -> bmi >= 24.0 ){
                    echo "メタボ！";
                } else if ( $this -> age >= 30 && $this -> bmi >= 27.0 ){
                    echo "メタボ！"; 
                } else {
                    echo "メタボじゃないです！";
                } 
            }
        }

        $object = new Personal_info("Taro", 65, 1.7); 
        $object -> bmi_cal("Taro");
        $object -> bmi_age(22);

        $yes_no = class_exists("Person");
        echo "<hr>".$yes_no;

        $method = get_class_methods("Person");
        foreach($method as $k => $val){ 
            echo"<br>method" . $k. ": " .$val;
        }
        
    ?>
</body>
</html>