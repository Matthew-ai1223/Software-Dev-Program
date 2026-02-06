<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | DevProgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="../../accet/styl.css">
</head>
<body class="dashboard-body">
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="ph-fill ph-shield-check logo-icon"></i>
                <span class="logo-text">Admin Panel</span>
            </div>
            <nav class="sidebar-nav">
                <a href="#assignments" class="nav-item active" data-tab="assignments">
                    <i class="ph ph-check-circle"></i><span>Assignments</span>
                </a>
                <a href="#curriculum" class="nav-item" data-tab="curriculum">
                    <i class="ph ph-book-open-text"></i><span>Curriculum</span>
                </a>
                <a href="#community" class="nav-item" data-tab="community">
                    <i class="ph ph-users"></i><span>Community</span>
                </a>
                <a href="#schedule" class="nav-item" data-tab="schedule">
                    <i class="ph ph-calendar-blank"></i><span>Schedule</span>
                </a>
                <a href="manage_student.php" class="nav-item">
                    <i class="ph ph-student"></i><span>Manage Students</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="../../dashbord.html" class="nav-item">
                    <i class="ph ph-arrow-left"></i><span>Back to Dashboard</span>
                </a>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <div class="page-title"><h2 id="pageTitle">Manage Assignments</h2></div>
            </header>

            <div class="dashboard-grid" id="content-area">
                <!-- Dynamic content will be loaded here -->
            </div>
        </main>
    </div>

    <script src="admin.js" defer></script>
</body>
</html>
