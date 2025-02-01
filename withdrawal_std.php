<!DOCTYPE html>

<?php
include('header_sidebar.php');
?>

<html lang="en" dir="rtl">

<head>

    <title> تقديم طلب اسقاط </title>


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="CSS\withdrawal_std.css">
</head>

<body>
    <?php
    $year_smst = get_curr_smst($year, $semester);
    //to filter the user id from any extension (insr, dept, ....) and also +/-
    $user_id = (int)filter_var($_SESSION['userid'], FILTER_SANITIZE_NUMBER_INT);
    $schedule = get_st_sch($user_id);

    $shours = 0;
    $disabled = "";
    $withdraw_status = "";
    ?>

    <div class="container">
        <table class="std_sched">
            <thead>
                <tr>
                    <th colspan=11 style='text-align: center;  font-family: Arabic Typesetting;'>
                        <h2> <?php
                                echo "جدول الطالب في الفصل " . $semester . ' ' . $year;
                                ?> </h2>
                    </th>

                </tr>

                <tr>
                    <th> رقم المادة </th>

                    <th> اسم المادة </th>

                    <th> الشعبة </th>

                    <th> س.م </th>

                    <th> الأيام </th>

                    <th> من </th>

                    <th> الى </th>

                    <th> المدرس </th>

                    <th> القاعة </th>

                    <th> تقدبم طلب اسقاط </th>

                    <th> حالة الاسقاط</th>

                </tr>

            </thead>

            <tbody>

                <?php
                // to check every value in the schedule array 
                foreach ($schedule as $row) {

                    // calculate withdrawn hours
                    $whours = get_sum_withdrawn_hours($user_id);
                ?>
                    <!-- i gave the row an id so i can know the withdrawn course -->
                    <tr id="<?php echo $row['cor_id']; ?>">

                        <?php
                        $shours += $row['cor_hours'];

                        // to extract the value of every attribute in the array
                        foreach ($row as $key => $cell) {

                            $cor = $row['cor_id'];

                            // this will return a text about the withdrwal request status
                            // i have to keep in mind the student id and the course id that i'm checking their status
                            $withdrawal_status = get_withdrawal_status($user_id, $cor, $row['sch_class']);

                            // this will modify the flag value to 1 which will return a numerical value that will be used later
                            $check_status = get_withdrawal_status($user_id, $cor, $row['sch_class'], 1);


                            if ($withdrawal_status != 0) {
                                $disabled = "disabled";
                            } else {
                                $disabled = "";
                                $withdrawal_status = "";
                            }

                            $color = "";
                            // to skip printing the course id and the status columns
                            if ($key == "cor_id" or $key == "sta") {
                                continue;
                            }
                            //if there's a value in the sta (from status deprived\withdrawed) print it in this format
                            if ($key == "sta" && !empty($cell)) {
                                $color .= ' style="color:red; font-weight:bold;"';
                            }
                            echo '<td' . $color . '>' . $cell . '</td>';
                        }
                        
                        if ($check_status == -3) {
                            echo '<td id="del_with_btns">
                                    <button type="button" id="' . $user_id . '_' . $cor . '_' . $row['sch_class'] . '" name="del_withdraw"  onClick="del_withdraw(this.id)">الغاء اسقاط المادة</button>
                                  </td>';
                        } 
                        
                        else {
                            echo '<td id="with_btns">
                                    <button type="button" id="' . $user_id . '_' . $cor . '_' . $row["sch_class"] . '" name="withdraw" ' . $disabled . ' onClick="withdraw(this.id, 1)">طلب الاسقاط</button>
                                  </td>';
                        }

                        echo '<td> <label style="display: block;" id="lbl_'  . $user_id . '_' . $cor . '_' . $row["sch_class"] .  '" name="withrawal_status" > '
                            . $withdrawal_status .
                            '</label></td>';

                        ?>
                    </tr>
                    
                <?php
                }
                ?>

            </tbody>

            <tfoot>
                <tr>
                    <th colspan=8 style="text-align: center; vertical-align: middle;">
                        <?php
                        echo 'مجموع الساعات : ' . $shours;
                        ?>
                    </th>

                    <th colspan=3 style="text-align: center; vertical-align: middle;">
                        <?php
                        echo 'مجموع ساعات الاسقاط : ' . $whours;
                        ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>



    <div class="card">
        <h3>الترتيبات على إسقاط المواد </h3>
        <h5>قديراً من الجامعة للظروف التي قد يمر بها الطالب فقد أجازت له التعليمات إجراء تعديل على برنامجه الدراسي من خلال عملة الانسحاب الغير مرصد (اسقاط مادة).  </h5>



            <!-- <h4>يرجى العلم عزيزي الطالب في حال تقديمك لطلب الاسقاط والموافقة علية سيترتب عليك الاجراءات التالية -:</h4> -->
            <ul><li> الاسقاط  : هي عملية حذف مادة سبق أن سجلها الطالب أثناء فترة التسجيل، وذلك من أجل تعديل جدوله الدراسي عند الضروره في اخر اسبوعين من الفصل الدراسي.
</li>
            <li> يكون الاسقاط إلكتروني  ولا داعي لمراجعة اي جهة .
            </li>
            <li>  لا تدخل المادة التي يتم اسقاطها بالمعدل.
            </li>
            <li>  <br>يمكن للطالب إسقاط أي عدد يرغب به ما لم يتعد الحد الادنى من الساعات التي يجب أن يبقى الطالب مسجل بها. 
            وهي 6 ساعات لطالب البكلوريوس  و3 للماستر 
            </li> 
            <li>  يكون  بالانسحاب بخسارة مالية (100%) .
            </li> 
            
        </ul>
        للمزيد من المعلومات 
        <center> <a href="#">هنا </a></center>


    </div>
</body>





<!-- Jquery is basically a framework that's used for javascript and here's a link to connect to it 
 it's like to call a library but online  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    function ajaxRequest(url, action, st, cor, cclass, callback) {

        // This function sends an AJAX request using jQuery's $.ajax method. It takes several parameters:
        $.ajax({

            //defined parameters in jQuery
            url: url,
            type: 'POST',
            // Data to be sent to the server (if any)
            data: {
                student: st,
                corid: cor,
                classid: cclass,
                action: action
            },

            success: function(response) {
                    // callback is a custom function to pass the ajaxRequest function response
                    callback(null, response); 
                },

            // xhr : XML Http Request
            // status :  describing the type of error that occurred. Common values include "timeout", "error", "abort", and "parsererror" (status code)
            // error : error message
            error: function(xhr, status, error) {
                    callback(error); 
                }
        });
    }

    // Sequentially call AJAX functions


    // with_id is to retrieve the user_id, course_id and the class_id for the request
    // winfo_arr is to store the info in an array
    function withdraw(with_id, x) {
        var winfo_arr = with_id.split("_");
        var st = winfo_arr[0];
        var cor = winfo_arr[1];
        var cclass = winfo_arr[2];
    
        var act = "INSERT";
        if (x == 2) {
            act = "DELETE";
        }

        // built in functions if there's no match it returns null
        // it returns an object
        var row = document.getElementById(cor);
        // row is the returned object
        var cell = row.getElementsByTagName("td")[1];
        var chour = parseInt(row.getElementsByTagName("td")[3].textContent);



        if (x != 3) {
            // to show an alert with the type of confirm
            var result = confirm("هل أنت متأكد من طلب اسقاط المادة: " + cell.textContent + "؟");


            if (result) {
                // if yes then execute the first fun (ajaxrequest)
                // if error1 appears then, this student has exceeded the min hours 
                ajaxRequest('validate.php', 'val_load', st, cor, cclass,
                    function(error1, response1) {
                        if (error1) {
                            // this will print the error in the console 
                            // console.error to print the error
                            console.error('Error in example1.php:', error1);
                            return;
                        }

                        console.log('Response from example1.php:', response1);

                        // === should also be the same type
                        // if error2 appears, couldn't complete the withdrawal process
                        if (response1 === '1') {
                            ajaxRequest('withdraw_process.php', act, st, cor, cclass, 
                            function(error2, response2) {
                                if (error2) {

                                    console.error('Error in example2.php:', error2);
                                    return;
                                }

                                document.getElementById(with_id).disabled = true;
                                document.getElementById('lbl_' + with_id).textContent = "بانتظار مدرس المادة";
                                document.getElementById('drop').value = parseInt(document.getElementById('drop').value) + chour;
                                console.log('Response from example2.php:', response2);

                                }

                            );


            }
                    
            else {
                // there's no cancel
                alert(response1);
                }

            });

            }
        }
    }

    function del_withdraw(with_id) {

        var winfo_arr = with_id.split("_");
        var st = winfo_arr[0];
        var cor = winfo_arr[1];
        var cclass = winfo_arr[2];


        var row = document.getElementById(cor);
        // to get the name of the course
        var cell = row.getElementsByTagName("td")[1];
        var chour = parseInt(row.getElementsByTagName("td")[3].textContent);

        var result = confirm("هل أنت متأكد من الغاء طلب اسقاط المادة: " + cell.textContent + "؟");
        if (result) {

            $.ajax({
                url: "withdraw_process.php", // URL of the server-side script
                type: "POST", // HTTP method (POST recommended for updating data)
                data: {
                    // Data to be sent to the server (if any)
                    student: st,
                    corid: cor,
                    classid: cclass,
                    action: "DELETE"
                },
                success: function(response) {
                    // Code to be executed if the request succeeds
                    if (response == 1) {
                        //alert(response);
                        document.getElementById(with_id).disabled = true;
                        document.getElementById('lbl_' + with_id).textContent = " ";
                        document.getElementById('drop').value = parseInt(document.getElementById('drop').value) - chour;
                    }
                    console.log("Database updated successfully!" + response);
                },
                error: function(xhr, status, error) {
                    // Code to be executed if the request fails
                    console.error("Error updating database:", error);
                }
            });
        }
    }
</script> 
