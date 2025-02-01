<?php
session_start();
include 'DB.php';
// we brought the values from the ajaxruest fun in     withdrawal_std.php
$student = $_POST['student'];
$cor = $_POST['corid'];
$class = $_POST['classid'];
$action = $_POST['action'];


if ($action == "val_load") {
    $student = $_SESSION["userid"];
    $deg = $_SESSION["deg_id"];
    $corsta = get_cor_status($student, $cor, $class);
    if ($corsta == 1) {
        echo "المادة مسقطة";
    }
    if ($corsta == 2) {
        echo "الطالب محروم في المادة";
    }


    $whours = get_sum_withdrawn_hours($student);
    $reghours =  get_sum_reg_hours($student); 
    $corhours = get_cor_hours($cor);
    

    // min hours shows the minmum hours you should have
    if ($deg >= 4) {
        $min_hours = 3;
    } else {
        $min_hours = 6;
    }


    if ($reghours - ($whours + $corhours) < $min_hours) {
        echo "تجاوز الحد الأدنى للعبء";
    } else {
        echo '1';
    }
}


if ($action=="UPDATE"){
    $act= $_POST['val'];
    // val=2 confirm  val=3 reject
    
            $x=update_deprived_student($student,$cor,$class,$_SESSION["role"],$act);
            if($x==1 and $_SESSION["role"]==4 and $act==2)
            {
                $xx= transfer_deprived($student,$cor,$class);
                if($xx==1){
                    echo 1;
                }
         else {echo 0;}
            }
            else if($x==1) {
            echo 1;
        } else {
            echo 0;
        }
    }

?>
