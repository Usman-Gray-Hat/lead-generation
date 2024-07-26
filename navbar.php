<nav class="navbar navbar-expand-sm navbar-dark border-bottom-spec">
    <a href="home.php" class="navbar-brand ps-2 pt-2"><img src="images/logo.png" height="50"></a>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="home.php" class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'home.php') { echo "active"; } ?>">
                <i class="fas fa-home"></i> Home</a>
        </li>

        <li class="nav-item">
            <a href="qualifiedLeads.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "qualifiedLeads.php") { echo "active"; } ?>">
                <i class="fas fa-check-double"></i> Qualified Leads</a>
        </li>

        <li class="nav-item">
            <a href="follow-up.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "follow-up.php") { echo "active"; } ?>">
                <i class="fas fa-reply"></i> Follow ups</a>
        </li>

        <li class="nav-item">
            <a href="closed.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "closed.php") { echo "active"; } ?>">
                <i class="fas fa-check-circle"></i> Closed</a>
        </li>  

        <!-- Access for admins only -->
        <?php if($_SESSION['login_type']==1): ?>
        <li class="nav-item">
            <a href="members.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "members.php") { echo "active"; } ?>">
                <i class="fas fa-users"></i> Members</a>
        </li>

        <li class="nav-item">
            <a href="accounts.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "accounts.php") { echo "active"; } ?>">
                <i class="fas fa-id-card"></i> Accounts</a> 
        </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="all_attendance.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "all_attendance.php") { echo "active"; } ?>">
                <i class="fas fa-clock"></i> Attendance</a>
        </li>

        <li class="nav-item">
            <a href="score.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "score.php") { echo "active"; } ?>">
                <i class="fas fa-chart-line"></i> Scoreboard</a>
        </li>

        <li class="nav-item">
            <a href="target.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "target.php") { echo "active"; } ?>">
                <i class="fas fa-rocket"></i> Targets</a>
        </li>

        <li class="nav-item">
            <a href="stats.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "stats.php") { echo "active"; } ?>">
            <i class="fas fa-chart-pie"></i> Stats</a>
        </li> 
        
        <!-- Access for admins only -->
        <?php if($_SESSION['login_type']==1): ?>
        <li class="nav-item">
            <a href="users.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "users.php") { echo "active"; } ?>">
                <i class="fas fa-user-circle"></i> Users</a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="privacy.php" 
            class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "privacy.php") { echo "active"; } ?>">
                <i class="fas fa-key"></i> Privacy</a>
        </li>

        <li class="nav-item">
            <a class="nav-link logout"><i class="fas fa-power-off"></i> Logout</a>
        </li>
    </ul>
</nav>