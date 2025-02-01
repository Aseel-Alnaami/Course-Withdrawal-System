<!DOCTYPE html>
<?php 
include("header_sidebar.php");
?>
<html lang="en" dir="rtl">
<head>
    
    <title> المعلومات الشخصية </title>


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="CSS\personinfo.css">
</head>
<body>
   
<?php 
$userinfo=get_user_info($_SESSION['userid']);

$info=extract($userinfo[0],EXTR_PREFIX_SAME,'wddx');
if($role==1){
    {$desc = 'طالب'.' / '.$deg_name;}

}
if($role==2){
    {$desc = 'مدرس';}
}
if($role==3||$role==4){
    {$desc = $username;}

}
?>
    <center>
   <div class="container">
    <h2 style='font-family: Arabic Typesetting;  '>المعلومات الشخصية </h2>
   <table class="user-info">
        <thead>

            <tr ><!-- table row -->
              <th colspan=2 style='   text-align: center;  font-family: Arabic Typesetting;  padding:  10px 0px 0px 0px;'><!--- column with head format -->
                <i class='bx bx-user-circle'></i></th>
            </tr>
      
        </thead>   
        <tbody>
        <tr> <td style='font-family: Arabic Typesetting;'> إسم المستخدم </td><!--- column -->
        <td> <?php echo "$userid" ?></td>
</td>
        </tr>
        <tr> <td style='font-family: Arabic Typesetting;'>  الإسم الكامل </td>
            <td> <?php echo "$personname" ?></td>
        </tr>
        <tr> <td style='font-family: Arabic Typesetting;'>  القسم الاكاديمي </td>
            <td> <?php echo "$userdept" ?></td>
        </tr>
        <tr> <td style='font-family: Arabic Typesetting;'>  الكلية </td>
            <td>       <?php echo "$usercol" ?></td>
        </tr>
        <tr> <td style='font-family: Arabic Typesetting;'>  الصفة </td>
            <td> <?php echo "$desc" ?></td>
        </tr>
       
    </tbody>
        <tfoot>
            <tr></th></tr>
        </tfoot>
    </form>
    </table>

   </div>
</center>
<img src="css/main_mush.png" height="300px" width="300px" style="position:absolute; bottom:0; left:0" class="bottom_left"/>
   
</body>
</html>