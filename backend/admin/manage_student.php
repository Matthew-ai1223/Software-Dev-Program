<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students | Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="../../accet/styl.css">
    <style>
        .student-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            color: var(--text-main);
        }
        .student-table th {
            text-align: left;
            padding: 1rem;
            border-bottom: 2px solid var(--border-color);
            color: var(--text-muted);
            font-weight: 600;
        }
        .student-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        .student-table tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
            margin-right: 0.75rem;
        }
        .user-name-cell {
            display: flex;
            align-items: center;
        }
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 4px;
            transition: all 0.2s;
            color: var(--text-muted);
        }
        .action-btn:hover {
            background: var(--bg-dark);
            color: var(--primary);
        }
        .action-btn.delete:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(99, 102, 241, 0.1);
            color: #818cf8;
        }
    </style>
</head>
<body class="dashboard-body">
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="ph-fill ph-shield-check logo-icon"></i>
                <span class="logo-text">Admin Panel</span>
            </div>
            <nav class="sidebar-nav">
                <a href="indext.php#assignments" class="nav-item">
                    <i class="ph ph-check-circle"></i><span>Assignments</span>
                </a>
                <a href="indext.php#curriculum" class="nav-item">
                    <i class="ph ph-book-open-text"></i><span>Curriculum</span>
                </a>
                <a href="indext.php#community" class="nav-item">
                    <i class="ph ph-users"></i><span>Community</span>
                </a>
                <a href="indext.php#schedule" class="nav-item">
                    <i class="ph ph-calendar-blank"></i><span>Schedule</span>
                </a>
                <a href="manage_student.php" class="nav-item active">
                    <i class="ph ph-student"></i><span>Manage Students</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="../../dashbord.html" class="nav-item">
                    <i class="ph ph-arrow-left"></i><span>Back to Dashboard</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-bar">
                <div class="page-title"><h2>Manage Students</h2></div>
                <div class="user-profile">
                    <div class="avatar"><span>AD</span></div>
                    <div class="user-info"><span class="user-name">Admin User</span><span class="user-role">Administrator</span></div>
                </div>
            </header>

            <div class="dashboard-grid">
                <div class="content-card full-width">
                    <div class="card-header-row">
                        <h4>All Registered Students</h4>
                        <button onclick="loadStudents()" class="primary-btn small" style="background: transparent; border: 1px solid var(--border-color);">
                            <i class="ph ph-arrows-clockwise"></i> Refresh
                        </button>
                    </div>
                    
                    <div class="table-container" style="overflow-x: auto;">
                        <table class="student-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Track</th>
                                    <th>Tech Stack</th>
                                    <th>Date Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentList">
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--text-muted);">Loading students...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="content-card" style="width: 400px; max-width: 90%;">
            <h4>Edit Student</h4>
            <form id="editForm" style="margin-top: 1rem; display: grid; gap: 1rem;">
                <input type="hidden" id="editId">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Full Name</label>
                    <input type="text" id="editName" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Track</label>
                    <select id="editTrack" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                        <option value="frontend">Frontend</option>
                        <option value="backend">Backend</option>
                        <option value="fullstack">Fullstack</option>
                        <option value="design">UI/UX Design</option>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" onclick="closeModal()" class="primary-btn small" style="background: transparent; border: 1px solid var(--border-color);">Cancel</button>
                    <button type="submit" class="primary-btn small">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadStudents);

        async function loadStudents() {
            const tbody = document.getElementById('studentList');
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Loading...</td></tr>';

            try {
                const response = await fetch('../api/manage_users.php');
                const data = await response.json();

                if (data.success && data.users.length > 0) {
                    tbody.innerHTML = data.users.map(u => {
                        const initials = u.fullname ? u.fullname.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : '??';
                        
                        let techStack = 'None selected';
                        try {
                            if (u.tech_stack && u.tech_stack !== 'null') {
                                const parsed = JSON.parse(u.tech_stack);
                                if (Array.isArray(parsed)) techStack = parsed.join(', ');
                            }
                        } catch (e) { console.error('Error parsing tech stack', e); }

                        const dateJoined = new Date(u.created_at).toLocaleDateString();
                        const safeName = u.fullname.replace(/'/g, "\\'"); // Escape quotes
                        
                        const githubIcon = u.github_link 
                            ? `<a href="${u.github_link}" target="_blank" title="${u.github_link}" style="color: var(--text-main); margin-right: 0.5rem;"><i class="ph ph-github-logo"></i></a>` 
                            : '<span style="color: var(--text-muted); margin-right: 0.5rem;"><i class="ph ph-github-logo" style="opacity: 0.3;"></i></span>';

                        return `
                            <tr>
                                <td>
                                    <div class="user-name-cell">
                                        <div class="user-avatar">${initials}</div>
                                        <div>
                                            <div style="font-weight: 500;">${u.fullname}</div>
                                            <div style="font-size: 0.8rem; color: var(--text-muted);">${u.email}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge" style="text-transform: capitalize;">${u.track}</span></td>
                                <td><div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${techStack}">${techStack}</div></td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        ${githubIcon}
                                        ${dateJoined}
                                    </div>
                                </td>
                                <td>
                                    <button onclick="editUser(${u.id}, '${safeName}', '${u.track}')" class="action-btn" title="Edit">
                                        <i class="ph ph-pencil-simple"></i>
                                    </button>
                                    <button onclick="deleteUser(${u.id})" class="action-btn delete" title="Delete">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem;">No students found.</td></tr>';
                }
            } catch (error) {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #ef4444;">Error loading data.</td></tr>';
            }
        }

        async function deleteUser(id) {
            if (!confirm('Are you sure you want to delete this student? This action cannot be undone.')) return;

            try {
                const response = await fetch(`../api/manage_users.php?id=${id}`, { method: 'DELETE' });
                const result = await response.json();
                
                if (result.success) {
                    alert('Student deleted successfully');
                    loadStudents();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while deleting.');
            }
        }

        function editUser(id, name, track) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editTrack').value = track;
            
            const modal = document.getElementById('editModal');
            modal.style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const id = document.getElementById('editId').value;
            const name = document.getElementById('editName').value;
            const track = document.getElementById('editTrack').value;

            try {
                const response = await fetch('../api/manage_users.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, fullname: name, track })
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Student updated successfully');
                    closeModal();
                    loadStudents();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating.');
            }
        });

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('editModal')) closeModal();
        });
    </script>
</body>
</html>
