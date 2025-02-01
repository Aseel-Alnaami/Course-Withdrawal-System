<?php 


//include 'session.php';


//"localhost", which typically means the database server is on the same machine as your web server.
define("DB_SERVER", "localhost");
//"root", the default username for MySQL databases.
define("DB_USER", "root");
//password used to connect to the MySQL database.
define("DB_PASSWORD", "FORfun.12345");
// defines the name of the MySQL database 
define("DB_DATABASE", "gp1");


function connection()
{
    //the order here is important
    $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);


    //$errors = array();


    if ($conn->connect_error) {
        // The die() function is used to stop the execution of the script and output a message. 
        // $conn->connect_error This provides information about why the connection failed.
        die("con failed" . $conn->connect_error); //to exet the massege
    }

    return $conn;
}



function authentication($userid, $password)
{

    //to check the variable password not the one in the post
    $md5pass = md5($password);



    //i created a query variable to retreive data from the database and check the username and password
    $query = "SELECT userid, username, userpass, role FROM gp1.accounts
               where userid='$userid' and userpass='$md5pass'";


    //i created a result variable to use the 'mysqli_query' that takes the following parameters which must 
    //be global to retreive the rows from the query
    $result = mysqli_query(connection(), $query);

    if (!$result) {
        die('Error: ' . mysqli_error(connection()));
    }

    
    //i created a result variable to use the 'mysqli_num_result' that retreives the num of rows from the result
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        return 1;
        //header("Location:header_sidebar.php"); //هون اسم الصفحة يلي رح يروح عليها 
    } 
    else {
        return 0;
        //header("Location:index.php");
    }
}


function get_user_info($userid){
     $conn = connection();
     $query = "SELECT * FROM gp1.accounts
            where userid='$userid'";

     $result = mysqli_query($conn, $query);

    //ensures that the rows are returned as associative arrays, where the column names are the keys.
    //{[,],[,]}
     $result2 = $result->fetch_all(MYSQLI_ASSOC);
     $arr=$result2;

    return $arr;
}



function changepass($user_id,$password,$role){
    //to know the user tht we want to change their password i need to 
    //retrieve the numbers only by using this built in fun 'filter_var' so we can upcast it to int and compare it 
    $username=(int)filter_var($user_id,FILTER_SANITIZE_NUMBER_INT);  // (int)--> Casting to Int
    $conn=connection();  //FILTER_SANITIZE_NUMBER_INT filter is used to remove all characters from the string except for digits, plus signs, and minus signs.
    $query='';
    if($role==1){
        $query= "update students set password='$password'
        where student_id='$username' ";
    }
    if($role==2){
        $query= "update instructors set inst_password='$password'
        where inst_id='$username' ";
    }
    if($role==3){
        $query= "update departments set dept_password='$password'
            where dept_id='$username' ";
    }
    if($role==4){
        $query = "update colleges set col_password='$password'
        where col_id='$username' ";
    }

    //$result = mysqli_query(connection(), $query);
    //$result = $conn->query($sql);

//if the query was successful and it was a query that doesn't return
// a result set (such as INSERT, UPDATE, DELETE, etc.).
    if ($conn->query($query)=== TRUE) {
        echo '<script>alert("تم تغير كلمة المرور بنجاح !"); </script>'; 
        return 1;
    } else {
        return 0;
    }
    
}



function get_curr_smst(&$year1, &$semester)
{
    $conn= connection();
    $sql = "select concat(year,smst) as ys
            from gp1.univ_calendar
            where sysdate() between smst_start and smst_end "; //sysdate() is a built-in function to retreive the current date and time of the day
   
    $result = $conn->query($sql);

// fetch_assoc() ---> retrieves one row at the time and set a key to each value
// fetch_all() ---> retrieves the whole data rows
$result2= $result -> fetch_assoc();

/*substr() is a built-in function that's used to extract the value depending on the index within a string, it will extract the first 10 digits 
  (0,9) from the concat variable result2 and set it to year*/
$year1 = substr($result2["ys"],0, 9);
$smst= substr($result2["ys"], -1);
// echo "<center>".$result2["ys"]." teeest!!!!<center>" ;

if ($smst==1){$semester="الفصل الأول";};
if ($smst==2){$semester="الفصل الثاني";};
if ($smst==3){$semester="الفصل الصيفي";};

//retrieves the year and the semester in the new form
return $result2["ys"];

}


function get_st_sch($student_id)
{
    $conn= connection();
    $yearsmst= get_curr_smst($year, $semester);

    // time_format to dispaly the start time and the end time of the course in this format 00:00
    // the added quotations for $yearsmst is because of concat
    $sql = "select cor_id,cor_regID,cor_name,sch_class,cor_hours,sch_days,
            TIME_FORMAT(sch_starttime, '%H:%i'),TIME_FORMAT(sch_endtime, '%H:%i'), 
            inst_name,room_name,

            concat(case withdraw_f when 1 then 'مسقطة' else '' end,
		            case deprived_f when 1 then 'محروم' else '' end) as sta

                from gp1.schedule, gp1.courses,gp1.instructors,gp1.rooms,student_reg
                where
                sch_cor_id= cor_id
                and sch_inst=inst_id
                and sch_room_id=room_id
                and reg_student_id=$student_id
                and concat(sch_year,sch_smst)='$yearsmst'
                and sch_cor_id=reg_cor_id
                and sch_class=reg_class
                and concat(reg_year,reg_smst)='$yearsmst'
                order by cor_regID, sch_class";

$result = $conn->query($sql);
$result2= $result -> fetch_all(MYSQLI_ASSOC);
return $result2;

}



function get_inst_sch($instructor_id)
{
    $conn= connection();
    $yearsmst= get_curr_smst($year, $semester);

    $query = "select cor_id,cor_regID,cor_name,sch_class,cor_hours,sch_days,
            TIME_FORMAT(sch_starttime, '%H:%i'),TIME_FORMAT(sch_endtime, '%H:%i'),
            inst_name,room_name
                from gp1.schedule, gp1.courses,gp1.instructors,gp1.rooms
                where
                sch_cor_id= cor_id
                and sch_inst=inst_id
                and sch_room_id=room_id
                and inst_id=$instructor_id
                and concat(sch_year,sch_smst)='$yearsmst'
                order by cor_regID, sch_class";

$result = $conn->query($query);
$result2= $result -> fetch_all(MYSQLI_ASSOC);
$arr = $result2;//mysqli_fetch_assoc($result);

return $arr;

}



/******************************* withdrawal info **********************************************************/
function get_sum_withdrawn_hours($student_id)
{
    $conn = connection();
    $yearsmst = get_curr_smst($year, $semestar);


    // NVL function is used to relace the null values with a specific value
    /* so now i need to check if this specific user id has any records in 
        the withdraw table, if yes retrieve the course info from the courses 
        table to calculate the withdrawn courses hours*/

    $sql = "select COALESCE(sum(cor_hours),0) as whours 
            from withdraw, courses
            where 
                concat(w_year,w_smst)='$yearsmst'
                and w_studentid= $student_id
                and w_corid=cor_id  ";

    //retrieve all the records from thw withdraw table for this student
    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        return $row["whours"];
    }
    else {
        return 0;
    }
}


function get_cor_status($student_id, $cor, $class)
{
    $conn = connection();
    $yearsmst = get_curr_smst($year, $semestar);

    $sql = "select COALESCE(withdraw_f,0) as withdrawed, COALESCE(deprived_f,0) as deprived
                from student_reg
                where
                    concat(reg_year,reg_smst)='$yearsmst'
                    and reg_student_id=$student_id
                    and reg_cor_id=$cor
                    and reg_class=$class";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        //withdrawn=1, deprived=2
        if ($row["withdrawed"] == 1 and $row["deprived"] == 0) {
            return 1;
        } 

        else if ($row["withdrawed"] == 0 and $row["deprived"] == 1) {
            return 2;
        } 
        
        else {
            // no status
            return 0;
        }
    } 
    
    else {
        // no course was found
        return -1;
    }
}

// f is basically a flag with the default value = zero, it's used to seperate the text output and the numerical  
function get_withdrawal_status($student_id, $cor, $class, $f=0, $role=-1){

    // to get the status of the course in general to check if the 
    //student is deprived or the course was already withdrawn
    $sta=  get_cor_status($student_id, $cor, $class);
    if($sta==1)
    {
        return "مسقطة";
    }
    if($sta==2)
    {
        return "محروم";
    }
     if($sta==3)
    {
        return "محروم+مسقطة";   //impossible case
    }                               


    $conn=connection();
    // to ensure that i'm checking the right schedule
    $yearsmst=get_curr_smst($year, $semester);
    
    //check the status of the withdrawal request
    $sql=   "select w_status 
            from withdraw
            where 
                concat(w_year,w_smst)='$yearsmst'
                and w_studentid= $student_id
                and w_corid=$cor
                and w_class=$class";

    $result= $conn->query($sql);
    if($result->num_rows>0){
        $row = $result->fetch_assoc();

        // who has accepted this withdrawal request 'till now
        $current_status= $row["w_status"];
        //echo $current_status;
    
        // current status = 0 withdrawal was requested
        // current status = 1 instructor has accepted
        // current status = 2 department head has accepted

        if($f==1 and $current_status==0){
            return -3;
        }

        if($current_status==0 and $role != 2)
        {  
            return "بانتظار مدرس المادة";
        }

        if($current_status==1 and $role != 3)
        {  
            return "بانتظار رئيس القسم";
        }

        if($current_status==2 and $role != 4)
        {  
            return "بانتظار العميد";
        }
        
        
        // current status = -1 instructor has rejected
        // current status = -2 department head has rejected
        // current status = -3 dean has rejected

        if($current_status==-1 )
        {  
            return "مرفوض من مدرس المادة";
        }

        if($current_status==-2 )
        {  
            return "مرفوض من رئيس القسم";
        }

        if($current_status==-3 )
        {  
            return "مرفوض من العميد";
        }
        
    }

     else {

        return 0;
    }
    

}

// //////////////////////////////////////
function get_sum_reg_hours($student){


    $yearsmst= get_curr_smst($year, $semestar);//ys
    $smst= substr($yearsmst, -1);
     $sql ="select COALESCE(sum(cor_hours),0) as reghours 
         from student_reg,courses
      where 
      concat(reg_year,reg_smst)='$yearsmst'
             and reg_student_id= $student
             and reg_cor_id=cor_id
             ";
    
  $result = connection()->query($sql);
//$result2= $result -> fetch_all(MYSQLI_ASSOC);
if ($result->num_rows > 0){
    $row = $result->fetch_assoc();
    return $row["reghours"];
}
 else {
    return 0;
}
}

function get_cor_hours($cor){
    // cor id 
  $query = "select cor_hours
              from courses
              where 
              cor_id=$cor ";

    $result = connection()->query($query);
    //$result2= $result -> fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["cor_hours"];
    } else {
        return 0;
    }
}


function withdraw_student($student,$cor,$class){
        
    $conn= connection();
    $yearsmst= get_curr_smst($year, $semestar);
    $smst= substr($yearsmst, -1);
        $sql = "insert into withdraw(w_year,w_smst,w_studentid,w_corid,w_class,w_status,w_create_date)
            values('$year','$smst',$student,$cor,$class,0,now())";
    
    //echo "<script>console.log($sql);</script>";  
//$result = $conn->query($sql);
//$result2= $result -> fetch_all(MYSQLI_ASSOC);
if ($conn->query($sql)=== TRUE) {
    //echo '<script>alert("Welcome to GeeksforGeeks!"); </script>'; 
    return 1;
} else {
    return 0;
}
}

function del_withdraw_student( $student, $cor, $class){

    $yearsmst= get_curr_smst($year, $semestar);
    $smst= substr($yearsmst, -1);
    $query = "delete from withdraw
                where concat(w_year,w_smst)='$yearsmst'
                and w_studentid= $student
                and w_corid=$cor
                and w_class=$class";

    if (connection()->query($query) === TRUE) {
        //echo '<script>alert("withdrawal request was deleted!"); </script>'; 
        return 1;
    } else {
        return 0;
    }

}



function update_withdraw_student($student, $cor, $class, $role, $act)
{

    $yearsmst = get_curr_smst($year, $semestar);
    $smst = substr($yearsmst, -1);

    if ($act == 3 and $role == 2) {
        $w_val = -1;
    }
    if ($act == 3 and $role == 3) {
        $w_val = -2;
    }
    if ($act == 3 and $role == 4) {
        $w_val = -3;
    }
    if ($act == 2 and $role == 2) {
        $w_val = 1;
    }
    if ($act == 2 and $role == 3) {
        $w_val = 2;
    }
    if ($act == 2 and $role == 4) {
        $w_val = 3;
    }
    $query = "";
    if ($role == 2) {
        $query = "update withdraw
                set w_status=$w_val,w_inst_apr_date=now()
                where
                concat(w_year,w_smst)='$yearsmst'
                and w_studentid=$student
                and w_corid=$cor
                and w_class=$class";
    }
    if ($role == 3) {
        $query = "update withdraw
                set w_status=$w_val,w_head_apr_date=now()
                where
                concat(w_year,w_smst)='$yearsmst'
                and w_studentid=$student
                and w_corid=$cor
                and w_class=$class";
    }
    if ($role == 4) {
        $query = "update withdraw
                set w_status=$w_val,w_col_apr_date=now()
                where
                concat(w_year,w_smst)='$yearsmst'
                and w_studentid=$student
                and w_corid=$cor
                and w_class=$class";
    }


    if (connection()->query($query) === TRUE) {
        return 1;
    } else {
        return 0;
    }
}


function transfer_withdraw($student,$cor,$class){

    $yearsmst= get_curr_smst($year, $semestar);
    $smst= substr($yearsmst, -1);
    
        $query = "update student_reg
                set withdraw_f=1
                where
                concat(reg_year,reg_smst)='$yearsmst'
                and reg_student_id=$student
                and reg_cor_id=$cor
                and reg_class=$class";
    

if (connection()->query($query)=== TRUE) {
    return 1;
} else {
    return 0;
}

}


function get_withdraw_req($userid,$role){

    $yearsmst=get_curr_smst($year,$semestar);
    $smst=substr($yearsmst,-1);// extract a part of a string (string ,start , legnth);
    $query="";
    
    if ($role==2){
        $query.="SELECT userid,username, cor_id,cor_regID,cor_name,w_class,inst_name
        FROM gp1.withdraw,accounts,courses,departments,schedule,instructors
        where
        w_studentid=userid
        and concat(w_year,w_smst)='$yearsmst'
        and w_corid=cor_id
        and cor_dept=dept_id
        and w_status in (0,3)
        and concat(sch_year,sch_smst)='$yearsmst'
        and sch_cor_id= w_corid
        and sch_class = w_class
        and sch_inst=inst_id
        and sch_inst=$userid";
    }

    if ($role==3){
        $query.="SELECT userid,username, cor_id,cor_regID,cor_name,w_class,inst_name
        FROM gp1.withdraw,accounts,courses,departments,schedule,instructors
        where
        w_studentid=userid
        and concat(w_year,w_smst)='$yearsmst'
        and w_corid=cor_id
        and cor_dept=dept_id
        and dept_id=$userid
        and w_status in (1,2,3)
        and concat(sch_year,sch_smst)='$yearsmst'
        and sch_cor_id= w_corid
        and sch_class = w_class
        and sch_inst=inst_id";
    }

    if ($role==4){
        $query.=" SELECT userid,username, cor_id,cor_regID,cor_name,w_class,inst_name
        FROM gp1.withdraw,accounts,courses,departments,schedule,instructors
        where
        w_studentid=userid
        and concat(w_year,w_smst)='$yearsmst'
        and w_corid=cor_id
        and cor_dept=dept_id
        and dept_col=$userid
        and w_status in (2,3)
        and concat(sch_year,sch_smst)='$yearsmst'
        and sch_cor_id= w_corid
        and sch_class = w_class
        and sch_inst=inst_id ";
    }
$result=mysqli_query(connection(),$query);
$result2=$result->fetch_all(MYSQLI_ASSOC);
return $result2;

}






// /***************************notifications fun ************ */

function not_con($notid)
{
    
    $yearsmst= get_curr_smst($year, $semestar);
    $smst= substr($yearsmst, -1);//s
    
        /*$query = "update st_notification
                  set not_status=0
                  where
                  concat(not_year,not_smst)='$yearsmst'
                  and id=$notid";*/

        $query= "DELETE FROM `gp1`.`st_notification` WHERE (`id` = $notid)";
    

    if (connection()->query($query)=== TRUE) {
        return 1;
    }    
    else {
        return 0;
    }
}



function get_st_notf($userid)
{
   
    $yearsmst = get_curr_smst($year, $semestar);
    $sql = "
            select id, not_text,not_date,
                    (case not_status 
                    when 1 then 'جديد'
                    else 'تم الاطلاع'
                    end) as not_status
            from gp1.st_notification
            where
                not_studentid=$userid
                and concat(not_year,not_smst)='$yearsmst'           
                order by not_status desc, not_date desc";
            //CASE statement to create a new column 
            //1=new ,else تم الاطلاع 
    $result = mysqli_query(connection(),$sql);

   
    $result2 = $result->fetch_all(MYSQLI_ASSOC);
    return $result2;

}



function check_new_noti($id,$role)
{
    if($role!= 1) //student 
    {return "";}
    
  
    $yearsmst= get_curr_smst($year, $semestar);//ys
    $smst= substr($yearsmst, -1);//s
    $query =" select count(*) as cnt
            FROM gp1.st_notification
            where
            not_studentid=$id
            and concat(not_year,not_smst)='$yearsmst'
            and not_status=1
                     
        ";
 

$arr =[];
// echo"<center > heyyyyyyyyyy1";
$result = connection()->query($query);
$row = $result->fetch_assoc();
if ($row["cnt"] > 0){
    return  $row["cnt"];
  }
 else {
return "";    
}
}



function check_new_withdrawal($id,$role)
{
    if($role!= 2 and $role!= 3 and $role!=4)
    {return "";}   
    $conn= connection();
    $yearsmst= get_curr_smst($year, $semestar);
    $smst= substr($yearsmst, -1);
    $sql="";
 if ($role==2){   
        $sql="SELECT count(*) as cnt
            FROM gp1.withdraw,accounts,courses,departments,schedule,instructors
            where
            w_studentid=userid
            and concat(w_year,w_smst)='$yearsmst'
            and w_corid=cor_id
            and cor_dept=dept_id
            and w_status in (0)
            and concat(sch_year,sch_smst)='$yearsmst'
            and sch_cor_id= w_corid
            and sch_class = w_class
            and sch_inst=inst_id
            and sch_inst=$id";
}
    if ($role==3){
     $sql .="
SELECT count(*) as cnt
            FROM gp1.withdraw,accounts,courses,departments,schedule,instructors
            where
            w_studentid=userid
            and concat(w_year,w_smst)='$yearsmst'
            and w_corid=cor_id
            and cor_dept=dept_id
            and dept_id=$id
            and w_status in (1)
            and concat(sch_year,sch_smst)='$yearsmst'
            and sch_cor_id= w_corid
            and sch_class = w_class
            and sch_inst=inst_id
         
        ";
    }
if ($role==4){
     $sql .=" SELECT count(*) as cnt
            FROM gp1.withdraw,accounts,courses,departments,schedule,instructors
            where
            w_studentid=userid
            and concat(w_year,w_smst)='$yearsmst'
            and w_corid=cor_id
            and cor_dept=dept_id
            and dept_col=$id
            and w_status in (2)
            and concat(sch_year,sch_smst)='$yearsmst'
            and sch_cor_id= w_corid
            and sch_class = w_class
            and sch_inst=inst_id
         
        ";
    }    
        
    
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row["cnt"] > 0){
    return  "<img src='new_logo.png' alt='New Logo'>";
  }
 else {
return "";    
}

}




// connect to the database
// LOGIN USER
// change password
// get the current semester
// get student schedule
// get instructor schedule
// get sum withdrawn hours
// get course status
// get withdrawal status
// get_sum_reg_hours 
// get withdraw_req
// not con
// get st notf
// check new notif
// check new withdrawal
?>