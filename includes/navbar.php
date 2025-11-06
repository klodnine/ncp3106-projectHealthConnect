<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'guest';
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
?>

<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <div class="navbar-logo">
                <i class="fas fa-heartbeat"></i>
                <span>HEALTHCARE Community Dashboard</span>
            </div>
        </div>
        
        <div class="navbar-right">
            <?php if ($isLoggedIn): ?>
                <div class="navbar-status">
                    <span class="status-badge status-online">
                        <i class="fas fa-circle"></i> System Online
                    </span>
                </div>
                
                <div class="navbar-user">
                    <span class="user-name"><?php echo htmlspecialchars($userName); ?></span>
                    <span class="user-role"><?php echo htmlspecialchars($userRole); ?></span>
                </div>
                
                <button id="logoutBtn" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            <?php else: ?>
                <span class="viewing-mode">Viewing Mode - Read Only</span>
            <?php endif; ?>
        </div>
    </div>
</nav>
