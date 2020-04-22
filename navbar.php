<style>
    .active {
        background-color:#e94b3cff;
        color:white !important;
    }

</style>

<div id="sidebar-wrapper">
    <ul class="sidebar-nav">

        <!--Leaving the image for now - this can be retrieved per user later-->
        <!--Have to disable moving to other sections of the website until the person has completed their account / thinking of doing banned the same way you cannot move to other sections if banned-->
        <li class="sidebar-brand"><a href="#"><img src="https://cdn.pixabay.com/photo/2017/06/13/12/53/profile-2398782_960_720.png" width="50px" height="50px" alt="Icon" />&nbsp;<?php echo ($_SESSION['fullname']); ?></a></li>

        <?php //$activePage = basename($_SERVER['PHP_SELF']); ?>

        <?php
        //echo "<p class=\"active\">$activePage</p>";  //-- proves you can access those variables here too */
        ?>
        <?php /*if($activePage == 'index.php'){ echo 'class=\"active\"';};*/ ?>
        <li><a class= "home" href=" <?php echo $_ENV['site_home'] ?>home">HOME</a></li>   <!--have it in the A tag as you want to change the color-->
        <li><a class="search" href="<?php echo $_ENV['site_home'] ?>search">SEARCH </a></li>
        <li><a class = "chat" href=" <?php echo $_ENV['site_home'] ?>chat">CHAT </a></li>
        <li><a class = "profile" href="<?php echo $_ENV['site_home'] ?>profile">PROFILE </a></li>

        <?php if (isset($_SESSION['user_id'])) {
            echo '<li><a href="' . $_ENV['site_home'] . 'connections.php">CONNECTIONS</a></li>';
            echo '<li><a href="' . $_ENV['site_home'] . 'logout.php">LOGOUT</a></li>';
        } ?>

        <?php if ($_SESSION['admin'] == 1) {
            echo '<li><a style="color:pink" href="' . $_ENV['site_home'] . 'admin ">ADMIN</a></li>';
        } ?>

    </ul>
</div>