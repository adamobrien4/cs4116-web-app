<div class="bg-light border-right col-4 text-center" id="sidebar-wrapper">
    <div class="sidebar-heading">Start Bootstrap </div>
    <div class="list-group list-group-flush">
     <?php if(isset($_SESSION['user_id'])){ echo '<a href="' . $_ENV['site_home'] . '/logout.php" class="list-group-item list-group-item-action bg-light">Logout</a>'; } ?>
        <a href="<?php echo $_ENV['site_home'] ?>" class="list-group-item list-group-item-action bg-light">Home</a>
        <a href="<?php echo $_ENV['site_home'] ?>profile" class="list-group-item list-group-item-action bg-light">Profile</a>
        <a href="<?php echo $_ENV['site_home'] ?>chat" class="list-group-item list-group-item-action bg-light">Chat</a>
        <?php if($_SESSION['admin'] == 1){ echo '<a href="{' . $_ENV['site_home'] . '/admin" class="list-group-item list-group-item-action bg-light">Admin</a>'; } ?>
    </div>
</div>