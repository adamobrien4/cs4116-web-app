<style>
    .active {
        background-color:#e94b3cff;
        color:white !important;
    }

</style>

<div id="sidebar-wrapper">
    <ul class="sidebar-nav">

        <!--TO DO retrieve image per user -->

        <li class="sidebar-brand"><a href="<?php echo $_ENV['site_home']?>profile"><img style="border-radius: 4px; word-break: break-all;"src="../assets/uploads/<?php echo $_SESSION['user_id'] ?>.jpg" width="50px" height="50px" alt="Icon" />&nbsp;<?php echo ($_SESSION['fullname']); ?></a></li>

        <li><a class= "home" href=" <?php echo $_ENV['site_home'] ?>home">HOME</a></li>
        <li><a class="search" href="<?php echo $_ENV['site_home'] ?>search">SEARCH </a></li>
        <li><a class = "chat" href=" <?php echo $_ENV['site_home'] ?>chat">CHAT </a></li>
        <li><a class = "profile" href="<?php echo $_ENV['site_home'] ?>profile">PROFILE </a></li>

        <?php if (isset($_SESSION['user_id'])) {
            echo '<li><a href="' . $_ENV['site_home'] . 'logout.php">LOGOUT</a></li>';
        } ?>

        <?php if ($_SESSION['admin'] == 1) {
            echo '<li><a style="color:pink" href="' . $_ENV['site_home'] . 'admin ">ADMIN</a></li>';
        } ?>



        <li style="margin-left:0px; margin-top: 50px;" class="sidebar-brand"><a href="#"><img src="https://i.imgur.com/yFSkW1L.png" width="150px" height="150px"> </a></li>
    </ul>

</div>