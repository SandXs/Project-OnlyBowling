<?php
include("tools.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.25/js/uikit-icons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.25/js/uikit.min.js"></script>
    <script src="./main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./CSS/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Security chief</title>
</head>


<body>
    <input type="checkbox" id="menu_bar" checked>
    <label for="menu_bar" class="menu_bar"><i class="fa-solid fa-bars"></i></label>
    <div class="header-container">

        <nav class="nav-container">
            <ul>
                <div class="list1">
                    <li class="list_item">
                        <a href="./index.php">
                            <i class="fa-solid fa-house"></i>
                            Home
                        </a>
                    </li>
                    <li class="list_item">
                        <a href="./AboutUs.php"><i class="fa-solid fa-address-card"></i>
                            About us
                        </a>
                    </li>
                    <li class="list_item">
                        <a href="./info.php">
                            <i class="fa-solid fa-circle-info"></i>
                            Info
                        </a>
                    </li>

                </div>
                <div class="list2">
                    <li class="list_item">
                        <a href="./contact.php">
                            <i class="fa-brands fa-telegram"></i>
                            Contact
                        </a>
                    </li>
                    <li id="login" class="list_item">
                        <a href="./login.php"><i class="fa-solid fa-right-to-bracket"></i>
                            Login</a>
                    </li>
                </div>
            </ul>
        </nav>

    </div>

  

