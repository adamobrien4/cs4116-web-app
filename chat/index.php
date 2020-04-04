<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';
include 'chat_handler.php';



// Allow only logged in users to visit this page
login_check(1);



?>
<!DOCTYPE html>
<html lang = "en">
<head>
    <title>Chat</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu-1.css">
    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="..\assets/css/styles.css">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>



    <link rel="stylesheet" href="chat.css" />


</head>


<body>

<!--
INSERT INTO `messages`(`message_id`, `chat_id`, `user_id`, `timestamp`, `message`) VALUES (2,1,8,"2020-03-22 11:59:30", "This is the second message replying to the introduction." )
for inserting by hand into the db

select chats.chat_id, users.firstname from chats inner join users on chats.userA_id = users.user_id or chats.userB_id = users.user_id where users.user_id = 19
-->

<div id="wrapper">
    <?php include('..\navbar.php'); ?>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>


            <div class="bootstrap_chat">
                <div class="container py-5 px-4">
                    <!-- For demo purpose-->
                    <header class="text-center">
                        <h1 class="display-4 text-white">Chat -- only works visually for now entering data on the db to see on the ui</h1>
                    </header>

                    <div class="row rounded-lg overflow-hidden shadow">
                        <!-- Users conversations box-->
                        <div class="col-5 px-0">
                            <div class="bg-white">

                                <div class="bg-gray px-4 py-2 bg-light">
                                    <p class="h5 mb-0 py-1">Recent</p>
                                </div>

                                <div class="messages-box">
                                    <div class="list-group rounded-0">



                                        <!--conversation User1-->
                                        <!--
                                        <a class="list-group-item list-group-item-action active text-white rounded-0">
                                            <div class="media"><img src="https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg" alt="user" width="50" class="rounded-circle">
                                                <div class="media-body ml-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <h6 class="mb-0">Jason Doe</h6><small class="small font-weight-bold">25 Dec</small>
                                                    </div>
                                                    <p class="font-italic mb-0 text-small">Havent decided if I am going to include partial message text here or not.</p>
                                                </div>
                                            </div>
                                        </a> -->

                                        <?php
                                            $firstTime = True;
                                            //so the first recipient in the inbox will be highlighted and the other ones will only be highlighted once selected

                                            //theres still a few more small touches - the toggle between conversatins aint working yet
                                            foreach ($chats as $items){
                                                $top = ($firstTime == True) ? ("active text-white") : ("list-group-item-light");
                                                $bottom = ($firstTime == True) ? ("text-muted") : ("");
                                                echo ("<a class=\"list-group-item list-group-item-action " . $top . "rounded-0\">
                                                        <div class=\"media\"><img src=\"https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg\" alt=\"user\" width=\"50\" class=\"rounded-circle\">
                                                            <div class=\"media-body ml-4\">
                                                                <div class=\"d-flex align-items-center justify-content-between mb-1\">
                                                                <h6 class=\"mb-0\">" . $items->matches_name . "</h6><small class=\"small font-weight-bold\">25 Dec</small>
                                                                </div>
                                                                <p class=\"font-italic " . $bottom . "  mb-0 text-small\">Havent decided if I am going to include partial message text here or not.</p>
                                                            </div>
                                                        </div>
                                                      </a>");

                                                $firstTime = False;
                                            }
                                        ?>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Box -->
                        <div class="col-7 px-0">
                            <div class="px-4 py-5 chat-box bg-white">


                                <?php

                                // error somewhere - only putting some values into the chat box
                                //call the messages function based on the toggle switch that happens somehwere above

                                foreach ($mess as $item){
                                    $div1 = "";
                                    $img = "";
                                    $div2 = "";
                                    $div3 = "";
                                    $div4 = "";
                                    if ($item->sender == True){
                                        $img = "<img src=\"https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg\" alt=\"user\" width=\"50\" class=\"rounded-circle\">";
                                        $div2 = "ml-3";
                                        $div3 = "bg-light";
                                        $div4 = "text-muted";
                                    } else{ // the message to display has been received from another user
                                        $div1 = "ml-auto";
                                        $div3 = "bg-primary";
                                        $div4 = "text-white";
                                    }
                                }


                                //echo ($item->message);
                                echo ("
                                <div class=\"media w-50 " . $div1 . " mb-3\">" . $img . " 
                                    <div class=\"media-body " . $div2 . "\">
                                        <div class=\"" . $div3 . " rounded py-2 px-3 mb-2\">
                                            <p class=\"text-small mb-0 ". $div4 ."\">" . $item->message . "</p>
                                        </div>
                                        <p class=\"small text-muted\">12:00 PM | Aug 13</p>
                                    </div>
                                </div>
                               ");

                               ?>




                            </div>

                            <!-- Typing area -->
                            <form action="#" class="bg-light">
                                <div class="input-group">
                                    <input type="text" placeholder="Type a message" aria-describedby="button-addon2" class="form-control rounded-0 border-0 py-4 bg-light">
                                    <div class="input-group-append">
                                        <button id="button-addon2" type="submit" class="btn btn-link"> <i class="fa fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>



        </div>

    </div>
</div>

<script src="..\assets/js/Sidebar-Menu.js"></script>





</body>
</html>