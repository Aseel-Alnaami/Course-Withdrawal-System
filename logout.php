<?PHP

//session_unset() ;

session_start();
//session_unset() ;
session_destroy();

 unset($_POST['login_user']);
 unset($_SESSION['userid']);
 unset($_SESSION['username']);
 unset($_SESSION['role']);
 unset($_SESSION['dispname']);
 unset($_SESSION['userpass']);
 unset($_SESSION['deg_id']);
 unset($_SESSION['deg_name']);

session_destroy();

 
header("location: index.php");
exit;  //Ensures that the script stops executing immediately after the redirection, preventing any further execution.

?>
