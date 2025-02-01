<?php 

include("header_sidebar.php");
include ("session_pass.php");

?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    
    <title>Withdrawal</title>


    <link rel="stylesheet" href="CSS\changepass.css">

</head>
<body>
  
<?php  
  if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php
          	echo $_SESSION['success'];
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>

<?php endif ?>  


<form method="post" action="changepass.php">
    <table >
        <thead>
            <tr ><!-- table row -->
              <th colspan=2 style='font-family: Arabic Typesetting;'><!--- column with head format -->
                تغيير كلمة المرور </th>
            </tr>
            
        </thead> 
        
        
        <tbody>
        <?php include('error_pass.php'); ?>
        <tr> <td style='font-family: Arabic Typesetting;'> كلمة المرور الحالية</td><!--- column -->
        <td>        <input type="password" name="old_pass" id="old_pass" />   </td>
        </tr>
        <tr> <td style='font-family: Arabic Typesetting;'> كلمة المرور الجديدة</td>
            <td>        <input type="password" name="new_pass" id="new_pass" />    </td>
        </tr>
        <tr> <td style='font-family: Arabic Typesetting;'> تأكيد كلمة المرور الجديدة</td>
            <td>        <input type="password" name="con_new_pass" id="con_new_pass" />    </td>
        </tr></tbody>
        <tfoot>
            <tr> <th colspan=2  > <!--to merge tow cells-->
            <button type="submit" name="change_bttn" id="change_bttn"  style='font-family: Arabic Typesetting;'>  تغيير  </button>
        
        </th></tr>

        </tfoot>

    </table>
    </form>




</body>
</html>