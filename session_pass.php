<?php

$errors = array();


/********************* for change password ********************************/
if (isset($_POST['change_bttn'])){
    $pass1=$_POST['old_pass'];
    $pass2=$_POST['new_pass'];
    $pass3=$_POST['con_new_pass'];


    if(empty($pass1) or empty($pass2) or empty($pass3) ){
        array_push($errors,"يجب كتابة كلمة السر في جميع الحقول !");
        }
    
    if( md5($pass1)!=$_SESSION['userpass']){
        array_push($errors,"كلمة السر الحالية غير صحيحة ");
    }
    if(md5($pass2)!=md5($pass3)){
        array_push($errors,"كلمة السر الجديده غير متطابقة مع تأكيد كلمة السر ");
    }
    else{
        $errors=[];
    }

    
    $error_num=count($errors);

    if($error_num==0){
        //this is a fun in db
        $x=changepass($_SESSION['userid'],$pass2,$_SESSION['role']);
        if($x==1){

            $_SESSION['success']='!!تم تعديل كلمة السر بنجاح ';
            $_SESSION['userpass']=md5($pass2);
        }
        }
    else { 
        array_push($errors ,"حصل خطأ ما !!");
    }
}
    














?>