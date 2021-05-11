<?php
    require_once __DIR__ . "/vendor/autoload.php";
    $con=new MongoClient();
    $collection= $con-> test-> tasks;
    $list = $collection->find();
    $manager1 = $_POST['mng'];
    $manager2 = $_POST['wrk'];
    $project = $_POST['prj'];
    $dat = $_POST['dt'];
    $arr1=array();
    $arr2=array();
    $arr3=array();
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table{
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 24px;
        }
        th,td{
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <?php
        $filter1=array("manager"=>$manager1);
        $res1=$collection->find($filter1);

        $filter2=array("manager"=>$manager2);
        $res2=$collection->find($filter2);
        
        $js="function(){ return this.projectName=='".$project."' && this.endDate<='".$dat."'}";
        $filter3=array('$where'=>$js);
        $res3=$collection->find($filter3);

    ?>
    <div style="border:1px solid black;">
        <p>
            <span>Проекты руководителя(<?php echo $manager1;?>):</span>
            <table>
                <tr>
                    <th>Name</th>
                </tr>
                <?php
                    $proj=0;
                    while($document=$res1->getNext()){
                        echo "<tr>";
                        if(!in_array($document["projectName"], $arr1)){
                            $proj++;
                            echo "<td>".$document["projectName"]."</td>";
                            array_push($arr1,$document["projectName"]);
                        }
                        echo "</tr>";
                    }
                ?>
            </table>
            <br>
            <span>Количество проектов: <?php echo $proj;?></span>
            <?php
                array_push($arr1, $proj);
            ?>
        </p>
    </div>

    <div style="border:1px solid black;">
    <p>
        <span>Сотрудники, работавшие под началом руководителя <?php echo $manager2;?>:</span>
        <?php
            $temp_arr=array();
            while($document=$res2->getNext()){
                if(is_array($document["workers"])){
                    for($i=0;$i<count($document["workers"]);$i++){
                        if(!in_array($document["workers"][$i], $temp_arr)){
                            array_push($temp_arr, $document["workers"][$i]);
                            array_push($arr2,$document["workers"][$i]);
                        }
                    }
                }
                else{
                    if(!in_array($document["workers"], $temp_arr)){
                        array_push($temp_arr, $document["workers"]);
                        array_push($arr2,$document["workers"]);
                    }
                }
            }
        ?>
        <ul>
            <?php
                for($i=0;$i<count($temp_arr);$i++){
                    echo "<li>".$temp_arr[$i]."</li>";
                }
            ?>
        </ul>
    </p>
    </div>
    <div style="border:1px solid black;">
    <p>
        <span>Готовые задачи проекта <?php echo $project;?>(Дата:<?php echo $dat;?>):</span>
        <table>
            <tr>
                <th>Task</th>
                <th>Manager</th>
                <th>Start</th>
                <th>End</th>
            </tr>
            <?php
                while($document=$res3->getNext()){
                    echo "<tr>";
                    echo "<td>".$document["description"]."</td>";
                    echo "<td>".$document["manager"]."</td>";
                    echo "<td>".$document["startDate"]."</td>";
                    echo "<td><b>".$document["endDate"]."</b></td>";
                    array_push($arr3,$document["description"]);
                    array_push($arr3,$document["manager"]);
                    array_push($arr3,$document["startDate"]);
                    array_push($arr3,$document["endDate"]);
                    echo "</tr>";
                }
            ?>
        </table>
    </p>
    </div>

    <?php
        $cnt = $arr1[count($arr)-1];
        array_pop($arr1);
        $json = json_encode($arr1);
        $json2 = json_encode($arr2);
        $json3 = json_encode($arr3);

    ?>

<script>
       var arr = JSON.parse('<?php echo $json; ?>');
       var arr2 = JSON.parse('<?php echo $json2; ?>');
       var arr3 = JSON.parse('<?php echo $json3; ?>');
       
       localStorage.setItem('<?php echo "Руководитель ".$manager1."(количество проектов ".$cnt.")";?>',JSON.stringify(arr));
       localStorage.setItem('<?php echo $manager2."(workers)";?>',JSON.stringify(arr2));
       localStorage.setItem('<?php echo $project." ".$dat;?>',JSON.stringify(arr3));
    </script>
    
</body>
</html>