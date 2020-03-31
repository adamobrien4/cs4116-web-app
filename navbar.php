<div id="sidebar-wrapper">
    <ul class="sidebar-nav">

        <!--Leaving the image for now - this can be retrieved per user later  // also where is the session_start() created so i can add the users name to get for the nav bar here-->
        <li class="sidebar-brand"><a href="#"><img src="https://cdn.pixabay.com/photo/2017/06/13/12/53/profile-2398782_960_720.png" width="50px" height="50px" alt="Icon" />&nbsp;<?php echo ($_SESSION['fullname']); ?></a></li>

        <?php if (isset($_SESSION['user_id'])) {
            echo '<li><a href="' . $_ENV['site_home'] . '/logout.php">LOGOUT</a></li>';
        } ?>

        <li><a href="<?php echo $_ENV['site_home'] ?>">HOME</a></li>
        <li><a href="<?php echo $_ENV['site_home'] ?>search"">SEARCH </a></li>
        <li><a href=" <?php echo $_ENV['site_home'] ?>chat">CHAT </a></li>
        <li><a href="<?php echo $_ENV['site_home'] ?>profile">PROFILE </a></li>

        <?php if ($_SESSION['admin'] == 1) {
            echo '<li><a style="color:pink" href="' . $_ENV['site_home'] . 'admin ">ADMIN</a></li>';
        } ?>

    </ul>
</div>