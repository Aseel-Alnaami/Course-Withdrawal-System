<!DOCTYPE html>
<?php
include('header_sidebar.php');
?>

<html lang="en" dir="rtl">

<head>

    <title> جدول الطالب </title>


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="CSS\std_schedule.css">
</head>

<body>
    <?php
    $year_smst = get_curr_smst($year, $semester);
    //to filter the user id from any extension (insr, dept, ....) and also +/-
    $user_id = (int)filter_var($_SESSION['userid'], FILTER_SANITIZE_NUMBER_INT);
    $schedule = get_st_sch($user_id);

    $shours = 0;
    ?>

    <div class="container">
        <table class="std_sched">
            <thead>
                <tr>
                    <th colspan=10 style='text-align: center;  font-family: Arabic Typesetting;'>
                        <h2> <?php
                                echo "جدول الطالب في الفصل " . $semester . ' ' . $year;
                                ?> </h2>
                    </th>
                </tr>
                <th> رقم المادة </th>

                <th> اسم المادة </th>

                <th> الشعبة </th>

                <th> س.م </th>

                <th> الأيام </th>

                <th> من </th>

                <th> الى </th>

                <th> المدرس </th>

                <th> القاعة </th>

                <th> حالة المادة </th>
            </thead>

            <tbody>

                <?php
                // to check every value in the schedule array 
                foreach ($schedule as $row) {
                ?>
                    <tr>
                        <?php
                        $shours += $row['cor_hours'];
                        // to extract the value of every attribute in the array
                        foreach ($row as $key => $cell) {
                            // to store the inline style for every td
                            $color = "";
                            // to skip printing the course id
                            if ($key == "cor_id") {
                                continue;
                            }
                            //if there's a value in the sta (from status deprived\withdrawed) print it in this format
                            if ($key == "sta" && !empty($cell)) {
                                $color .= ' style="color:red; font-weight:bold;"';
                            }
                            echo '<td' . $color . '>' . $cell . '</td>';
                        }

                        ?>
                    </tr>
                <?php
                }
                ?>
                
            </tbody>

            <tfoot>
                <tr>
                    <th colspan=10 style="text-align: center; vertical-align: middle;">
                        <?php
                        echo 'مجموع الساعات : ' . $shours;
                        ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>