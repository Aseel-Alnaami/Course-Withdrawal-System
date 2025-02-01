<!DOCTYPE html>
<?php
include "header_sidebar.php";


// if($_SESSION['role']<=1){
//     session_destroy();
//   	unset($_SESSION['userid']);
//     unset($_SESSION['username']);
//     unset($_SESSION['role']);
//   	header("location: index.php");}
// ?>
<html lang="ar" dir="rtl">

<head>
<!-- <link rel="stylesheet" href="CSS/personinfo.css"> -->

    <link rel="stylesheet" href="CSS/withdrawal_dept.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title> طلبات الاسقاط </title>

</head>

<body>

    <?php
    // echo' <center>'.$_SESSION['userid']
    

    $userid = (int) filter_var($_SESSION['userid'], FILTER_SANITIZE_NUMBER_INT);//(int,+,-)
    $role = $_SESSION['role'];
    $arr = get_withdraw_req($userid, $role);
    if (empty($arr)) {
        echo "
    <div class='main_content'>
      
        <div class='info'>
          <div> <h3> لا يوجد طلبات إسقاط مواد حاليًا! ^•^
          </h3></div>
          
        </div>
        </div>
    ";

        exit;
    }
    ?>





    <div class="contaner">


<?php


$cnt=0;
$htmlTable = '<div> <table class="dept"> ';
$htmlTable .='<thead><tr><th colspan=10 style="text-align: center; vertical-align: middle;">';
    $htmlTable .='
    <div>
        طلبات الاسقاط
    </div>
 ';
          
                $htmlTable .='<hr>
       </th></tr>';
$htmlTable .='
    <tr>
    <th>#</th>
    <th> رقم الطالب</th>
    <th> اسم الطالب</th>
        <th> رقم المادة</th>
    <th> اسم المادة</th>
    <th> الشعبة</th>
    
    <th> مدرس المادة</th>
    <th> </th>
    <th> </th>
    <th>  ملاحظـــات </th>
   
    </tr>
              </thead>';
foreach ($arr as $row) {
    
    
    $htmlTable .= '<tr id="'.$row["userid"].'">';
    $cnt+=1;
    $lbl ="";
    $disabled ="";
    $depstatus= get_withdrawal_status($row["userid"],$row["cor_id"],$row["w_class"],0,$role);
    
    if(!empty($depstatus)){
    $disabled = "disabled";
    $lbl=$depstatus;
    }
    
    $htmlTable .= '<td>' . $cnt . '</td>';
    //var_dump($row);
    foreach ($row as $key=>$cell) {
        if($key=="cor_id")
        {  continue;}
        $htmlTable .= '<td>' . $cell . '</td>';
    }
        
    $htmlTable .='<td><button type="button" id="ap_'.$row["userid"].'_'.$row["cor_id"].'_'.$row["w_class"].
            '" name="dep" '.$disabled.' onClick="update_withdraw_st(this.id,2)">تأكيد</button></td>';
    $htmlTable .='<td><button type="button" id="rj_'.$row["userid"].'_'.$row["cor_id"].'_'.$row["w_class"].
            '" name="dep" '.$disabled.' onClick="update_withdraw_st(this.id,3)">رفض</button></td>';
    $htmlTable .='<td><label style="display: block; width: 100px;" id="lbl_'.$row["userid"].'_'.$row["cor_id"].'_'.$row["w_class"].
            '" name="lbl_'.$row["userid"].'" > '.$lbl.'</label></td>';
    $htmlTable .= '</tr>';
}

$htmlTable .= '</table></div>';



echo "
          
        $htmlTable";
       
?>
 
    
</div>
   
</body>
</html>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script> 

   
function confirmW() {
    if (confirm("هل قرأت جميع المترتبات على اسقاط المادة ؟")) {
       
        
       // window.location.href = "logout.php";
    } else {
        
        return false; 
    }}
 function update_withdraw_st( e, x) {
        
        var myArray = e.split("_");
        var st = myArray[1];
        var cor = myArray[2];
        var cclass = myArray[3];

        var apbtn = 'ap_'+st+'_'+cor+'_'+cclass;
        var rjbtn = 'rj_'+st+'_'+cor+'_'+cclass;
        var lbl = st+'_'+cor+'_'+cclass;

        var row = document.getElementById(st);
        var cell = row.getElementsByTagName("td")[2]; 
        var crname=row.getElementsByTagName("td")[4]; 
        if (x==2){
        var result = confirm("هل أنت متأكد من تأكيد طلب اسقاط الطالب: "+cell.textContent+" في مادة "+crname.textContent);
        }
        if (x==3){
        var result = confirm("هل أنت متأكد من رفض طلب اسقاط الطالب: "+cell.textContent+" في مادة "+crname.textContent);
        }

        if (result){

        $.ajax({
            url: "withdraw_process.php", // URL of the server-side script
            type: "POST", // HTTP method (POST recommended for updating data)
            data: { 
                // Data to be sent to the server (if any)
                student: st,
                corid: cor,
                classid: cclass,
                action: "UPDATE",
                val: x
            },
            success: function(response){
                // Code to be executed if the request succeeds
                if (response==1)
                    {
                        //alert(response);
                        document.getElementById(apbtn).disabled = true;
                        document.getElementById(rjbtn).disabled = true;
                        document.getElementById('lbl_'+lbl).textContent ="قيد الاجراء";
                    }
                    else{
                        alert(response);
                        return;
                    }
                console.log("Database updated successfully!"+response);
            },
            error: function(xhr, status, error){
                // Code to be executed if the request fails
                console.error("Error updating database:", error);
            }
        });
        }
}

</script>