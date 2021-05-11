 <?php
    require_once __DIR__ . "/vendor/autoload.php";
    $con=new MongoClient();
    $collection= $con-> test-> tasks;
    $list = $collection->find();
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <title>Document</title>

    <style>
    #tbl,th,td{
        border: 1px solid black;
    }
    </style>
</head>
<body>
    <?php
        // $authors=array();
        // $publ=array();
        // $years=array();

        $managers = array();
        $projects = array();

        while($document=$list->getNext()){
            array_push($managers, $document["manager"]);
            array_push($projects, $document["projectName"]);
            // array_push($years, $document["year"]);
            // if(isset($document["author"])){
            //     if(is_array($document["author"])){
            //         for($i=0;$i<count($document["author"]);$i++){
            //             array_push($authors,$document["author"][$i]);
            //         }
            //     }
            //     else{
            //         array_push($authors,$document["author"]);
            //     }
            // }
        }
    
        // $managers=array_unique($managers);
        // $projects=array_unique($projects);
        $managers2 = array();
        for($i=0;$i<count($managers);$i++){
            if(!in_array($managers[$i],$managers2)){
                array_push($managers2,$managers[$i]);
            }
        }

        $projects2 = array();
        for($i=0;$i<count($projects);$i++){
            if(!in_array($projects[$i],$projects2)){
                array_push($projects2,$projects[$i]);
            }
        }
    ?>

    <form action="result.php" method="POST">
        <p>
            <label for="mng">Выберите руководителя: </label>
            <select name="mng" id="mng">
                <?php
                    for($i=0;$i<count($managers2);$i++){
                        echo "<option value='".$managers2[$i]."'>".$managers2[$i]."</option>";
                    }
                ?>
            </select>
        </p>
        <p>
            <label for="wrk">Сотрудники руководителя: </label>
            <select name="wrk" id="wrk">
            <?php
                for($i=0;$i<count($managers2);$i++){
                    echo "<option value='".$managers2[$i]."'>".$managers2[$i]."</option>";
                }
            ?>
            </select>
        </p>
        <p>
            <label for="prj">Выполненные задачи проекта на указанную дату: </label>
            <select name="prj" id="prj">
            <?php
                for($i=0;$i<count($projects2);$i++){
                    echo "<option value='".$projects2[$i]."'>".$projects2[$i]."</option>";
                }
            ?>
            </select>
            <br>
            <input type="date" id="dt" name="dt">
        </p>

        <p>
            <input type="submit" value="Информация">
        </p>
    </form>


    <script>
            var resArr=new Array();
            for(var i=0;i<localStorage.length;i++){
                resArr[resArr.length]=localStorage.key(i);
            }
            console.log(resArr);
    </script>



    <br><br><br><br><br><br>

    <select name="hst" id="hst">
            
    </select>

       
       <script>
             $(function(){ 
               for(var i=0;i<resArr.length;i++){
                   var op = new Option(resArr[i], resArr[i]);
                   $(op).html(resArr[i]);
                   $("#hst").append(op);
               }
           });
        </script>

        
        <br>
        <button class="loc">Поиск</button>

        <table id="tbl">
           
        </table>

        <ul id="sp">

        </ul>

        <script>
            $(function(){
                $('.loc').click(function(){
                    $('#tbl').empty();
                    $('#sp').empty();
                    var key=$('#hst').val();

                    var str=localStorage.getItem(key);
                    var tempArr=JSON.parse(str);

                    if(key.includes("Руководитель")){
                        $('#tbl').append('<tr><th>Name</th></tr>');
                        for(var i=0;i<tempArr.length;i++){
                            $('#tbl').append('<tr>');
                            $('#tbl').append('<td>'+tempArr[i]+'</td>');
                            $('#tbl').append('</tr>');
                        }
                        return;
                    }

                    if(key.includes("workers")){
                        for(var i=0;i<tempArr.length;i++){
                            $('#sp').append('<li>'+ tempArr[i]+'</li>');
                        }
                        return;
                    }

                    $('#tbl').append('<tr><th>Task</th><th>Manager</th><th>Start</th><th>End</th></tr>');
                    var index=0;
                    while(true){
                        $('#tbl').append('<tr><td>'+tempArr[index++]+'</td><td>'+tempArr[index++]+'</td><td>'+tempArr[index++]+'</td><td>'+tempArr[index++]+"</td></tr>");
                        if(index<tempArr.length){
                            continue;
                        }
                        else{
                            break;
                        }
                    }
                });
            });
        </script>
</body>
</html>