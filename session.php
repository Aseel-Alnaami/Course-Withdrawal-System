<?php

require_once 'DB.php';
$errors=array();
//i use session to store an important info about the user that wants multiple requests
//i can only use it once in the page and also include the page that has it once in other pages
session_start();


/****************************************** for login **************************************************/
if (isset($_POST['login_user'])) {
    
    // Get username and password from the POST request in the index page
    $user_id = $_POST['userid'];
    $password = $_POST['password'];
    

    /*be careful to pass the parameter that was assigned here*/
    if(empty($user_id)){
        array_push($errors, 'username is required');
    }

    if(empty($password)){
        array_push($errors, 'password is required');
    }

    if(count($errors)==0){
        $auth= authentication($user_id, $password);
        if($auth==1){
            //save the userid so i can retreive all og his info
            $_SESSION['userid']=$user_id;


            //to retreive all the info about the user in an array
            $get_info=get_user_info($_SESSION['userid']);
            //extract the array to assign the all of the info in variables so i can use them
            $user_info=extract($get_info[0], EXTR_PREFIX_SAME, 'wddx');

            //i need to initiate a new a varible to display the full name depending on the role
            if($role==3 || $role==4){
                $dispname=$personname. ' / ' .$username;

            }

            else{
                $dispname=$personname;
            }

            /* assigning vars to the retreived attributes*/
            $_SESSION['dispname']=$dispname;
            $_SESSION['username']=$username;
            $_SESSION['role']=$role;
            $_SESSION['userpass']=$userpasss;
            $_SESSION['deg_id']=$deg_id;
            $_SESSION['deg_name']=$deg_name;

            header('location: personInfo.php');
            
        }

        else{
            array_push($errors, 'wrong username or password');
        }
    }

    
}

/********************* for change password ********************************
if (isset($_POST['change_bttn'])){
        echo '<center>'. $_POST['old_pass'].'</center>';
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
    
                $_SESSION['success']='تم تعديل كلمة السر بنجاح ';
                $_SESSION['userpass']=md5($pass2);
            }
            }
        else { 
            array_push($errors ,"حصل خطأ ما !!");
        }
    }
        */
    


?>