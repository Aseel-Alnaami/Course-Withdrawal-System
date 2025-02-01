<?php
include('session.php');
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <title>Course Withdrawal</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- bootstrap-->

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'><!--  icons -->

    <link rel="stylesheet" href="CSS\style.css"><!--  css -->
</head>

<body>

    <div class="background"></div>


    <br>

    <header>
        <div class="title-container">
            <div class="title">
                <h1>Course <span>Withdrawal</span></h1>

            </div>
            <div>
                <br>
                <p> The World Islamic Sciences and Education University.</p>
            </div>

    </header>
    <div class="container">
        <center>
            <div class="back-Login">

                <form method="post" action='index.php'>

                    <br>
                    <div class="login-header">
                        <a href="https://www.wise.edu.jo/" title="The World Islamic Sciences and Education University">
                            <img src="CSS\logo.jpg">
                        </a>


                        <h1> Login</h1>
                    </div>
                    <br>
                    <?php
                        include('errors.php');
                    ?>
                    <br>
                    <div class="boo">
                        <i class='bx bxs-user'></i>
                        <input class="box" type="text" placeholder="  Username" name="userid"  />
                        <div class="row"> <br></div>


                        <i class='bx bxs-lock-alt '></i>
                        <input class="box" type="password" placeholder="  password" name="password" />
                        
                        <button type="submit" class="login-bttn" name="login_user"> Login </button>

                    </div>
                </form><br>
            </div>
        </center>
    </div>
    </div>
    </div>
    </div>
</body>

</html>