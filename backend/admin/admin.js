document.addEventListener('DOMContentLoaded', () => {
    const contentArea = document.getElementById('content-area');
    const pageTitle = document.getElementById('pageTitle');
    const navItems = document.querySelectorAll('.nav-item[data-tab]');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.querySelector('.sidebar');

    // Mobile Menu Toggle
    if (mobileMenuBtn && sidebar) {
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileMenuBtn.contains(e.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            }
        });
    }

    // Tab Switching
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            navItems.forEach(n => n.classList.remove('active'));
            item.classList.add('active');
            loadTab(item.dataset.tab);
        });
    });

    function loadTab(tab) {
        switch (tab) {
            case 'assignments':
                pageTitle.textContent = 'Manage Assignments';
                loadAssignmentsPanel();
                break;
            case 'curriculum':
                pageTitle.textContent = 'Manage Curriculum';
                loadCurriculumPanel();
                break;
            case 'community':
                pageTitle.textContent = 'Manage Community';
                loadCommunityPanel();
                break;
            case 'schedule':
                pageTitle.textContent = 'Manage Schedule';
                loadSchedulePanel();
                break;
        }
    }

    // ==================== ASSIGNMENTS ====================
    function loadAssignmentsPanel() {
        contentArea.innerHTML = `
            <div class="content-card full-width">
                <div class="card-header-row">
                    <h4>Add New Assignment</h4>
                </div>
                <form id="assignmentForm" style="display: grid; gap: 1rem;">
                    <input type="hidden" id="assignmentId" value="">
                    <input type="text" id="assignmentTitle" placeholder="Assignment Title" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <textarea id="assignmentDescription" placeholder="Description" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white; min-height: 100px;"></textarea>
                    <input type="datetime-local" id="assignmentDueDate" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <select id="assignmentStatus" style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                        <option value="active">Active</option>
                        <option value="graded">Graded</option>
                    </select>
                    <input type="number" id="assignmentGrade" placeholder="Grade (if graded)" style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <button type="submit" class="primary-btn small">Save Assignment</button>
                </form>
            </div>
            <div class="content-card full-width">
                <div class="card-header-row">
                    <h4>Existing Assignments</h4>
                </div>
                <div id="assignmentsList"></div>
            </div>
        `;
        document.getElementById('assignmentForm').addEventListener('submit', saveAssignment);
        loadAssignments();
    }

    async function saveAssignment(e) {
        e.preventDefault();
        const data = {
            id: document.getElementById('assignmentId').value,
            title: document.getElementById('assignmentTitle').value,
            description: document.getElementById('assignmentDescription').value,
            due_date: document.getElementById('assignmentDueDate').value,
            status: document.getElementById('assignmentStatus').value,
            grade: document.getElementById('assignmentGrade').value || null
        };

        const response = await fetch('../api/assignments.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.success) {
            alert('Assignment saved!');
            document.getElementById('assignmentForm').reset();
            loadAssignments();
        }
    }

    async function loadAssignments() {
        const response = await fetch('../api/assignments.php');
        const data = await response.json();
        const list = document.getElementById('assignmentsList');

        if (data.success && data.assignments.length > 0) {
            list.innerHTML = data.assignments.map(a => `
                <div class="class-item" style="margin-bottom: 1rem;">
                    <div class="class-details">
                        <h5>${a.title}</h5>
                        <p>${a.description} | Due: ${a.due_date} | Status: ${a.status}</p>
                    </div>
                    <button class="primary-btn small" onclick="deleteItem('assignments', ${a.id})">Delete</button>
                </div>
            `).join('');
        } else {
            list.innerHTML = '<p style="color: var(--text-muted);">No assignments yet.</p>';
        }
    }

    // ==================== CURRICULUM ====================
    function loadCurriculumPanel() {
        contentArea.innerHTML = `
            <div class="content-card full-width">
                <div class="card-header-row">
                    <h4>Add Curriculum Module</h4>
                </div>
                <form id="curriculumForm" style="display: grid; gap: 1rem;">
                    <input type="text" id="curriculumTitle" placeholder="Module Title (e.g., Month 1: Foundation)" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <input type="text" id="curriculumWeekTitle" placeholder="Week Title" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <textarea id="curriculumDescription" placeholder="Week Description" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white; min-height: 80px;"></textarea>
                    <select id="curriculumStatus" style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                        <option value="completed">Completed</option>
                        <option value="in_progress">In Progress</option>
                        <option value="locked">Locked</option>
                    </select>
                    <button type="submit" class="primary-btn small">Save Module</button>
                </form>
            </div>
            <div class="content-card full-width">
                <div class="card-header-row">
                    <h4>Existing Curriculum</h4>
                </div>
                <div id="curriculumList"></div>
            </div>
        `;
        document.getElementById('curriculumForm').addEventListener('submit', saveCurriculum);
        loadCurriculumData();
    }

    async function saveCurriculum(e) {
        e.preventDefault();
        const data = {
            module_title: document.getElementById('curriculumTitle').value,
            week_title: document.getElementById('curriculumWeekTitle').value,
            description: document.getElementById('curriculumDescription').value,
            status: document.getElementById('curriculumStatus').value
        };

        const response = await fetch('../api/curriculum.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.success) {
            alert('Curriculum saved!');
            document.getElementById('curriculumForm').reset();
            loadCurriculumData();
        }
    }

    async function loadCurriculumData() {
        const response = await fetch('../api/curriculum.php');
        const data = await response.json();
        const list = document.getElementById('curriculumList');

        if (data.success && data.curriculum.length > 0) {
            list.innerHTML = data.curriculum.map(c => `
                <div class="class-item" style="margin-bottom: 1rem;">
                    <div class="class-details">
                        <h5>${c.module_title} - ${c.week_title}</h5>
                        <p>${c.description} | Status: ${c.status}</p>
                    </div>
                    <button class="primary-btn small" onclick="deleteItem('curriculum', ${c.id})">Delete</button>
                </div>
            `).join('');
        } else {
            list.innerHTML = '<p style="color: var(--text-muted);">No curriculum modules yet.</p>';
        }
    }

    // ==================== COMMUNITY ====================
    function loadCommunityPanel() {
        contentArea.innerHTML = `
            <div class="content-card full-width">
                <div class="card-header-row">
                    <h4>Add Community Post</h4>
                </div>
                <form id="communityForm" style="display: grid; gap: 1rem;">
                    <input type="text" id="communityTitle" placeholder="Post Title" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <textarea id="communityContent" placeholder="Post Content" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white; min-height: 100px;"></textarea>
                    <select id="communityType" style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                        <option value="announcement">Announcement</option>
                        <option value="discussion">Discussion</option>
                    </select>
                    <input type="text" id="communityAuthor" placeholder="Author Name" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <button type="submit" class="primary-btn small">Save Post</button>
                </form>
            </div>
            <div class="content-card full-width">
                <div class="card-header-row">
                    <h4>Existing Posts</h4>
                </div>
                <div id="communityList"></div>
            </div>
        `;
        document.getElementById('communityForm').addEventListener('submit', saveCommunityPost);
        loadCommunityData();
    }

    async function saveCommunityPost(e) {
        e.preventDefault();
        const data = {
            title: document.getElementById('communityTitle').value,
            content: document.getElementById('communityContent').value,
            type: document.getElementById('communityType').value,
            author: document.getElementById('communityAuthor').value
        };

        const response = await fetch('../api/community.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.success) {
            alert('Post saved!');
            document.getElementById('communityForm').reset();
            loadCommunityData();
        }
    }

    async function loadCommunityData() {
        const response = await fetch('../api/community.php');
        const data = await response.json();
        const list = document.getElementById('communityList');

        if (data.success && data.posts.length > 0) {
            list.innerHTML = data.posts.map(p => `
                <div class="class-item" style="margin-bottom: 1rem;">
                    <div class="class-details">
                        <h5>${p.title}</h5>
                        <p>${p.content} | Type: ${p.type} | By: ${p.author}</p>
                    </div>
                    <button class="primary-btn small" onclick="deleteItem('community', ${p.id})">Delete</button>
                </div>
            `).join('');
        } else {
            list.innerHTML = '<p style="color: var(--text-muted);">No posts yet.</p>';
        }
    }

    // ==================== SCHEDULE ====================
    function loadSchedulePanel() {
        contentArea.innerHTML = `
            <div class="content-card full-width">
                <div class="card-header-row">
                    <h4>Add Schedule Event</h4>
                </div>
                <form id="scheduleForm" style="display: grid; gap: 1rem;">
                    <input type="text" id="scheduleTitle" placeholder="Event Title" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <input type="datetime-local" id="scheduleDate" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <input type="text" id="scheduleTime" placeholder="Time (e.g., 10:00 AM - 12:00 PM)" required style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white;">
                    <textarea id="scheduleDescription" placeholder="Event Description" style="padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-dark); color: white; min-height: 80px;"></textarea>
                    <button type="submit" class="primary-btn small">Save Event</button>
                </form>
            </div>
            <div class="content-card full-width">
                <div class="card-header-row">
                    <h4>Existing Events</h4>
                </div>
                <div id="scheduleList"></div>
            </div>
        `;
        document.getElementById('scheduleForm').addEventListener('submit', saveScheduleEvent);
        loadScheduleData();
    }

    async function saveScheduleEvent(e) {
        e.preventDefault();
        const data = {
            title: document.getElementById('scheduleTitle').value,
            event_date: document.getElementById('scheduleDate').value,
            time_range: document.getElementById('scheduleTime').value,
            description: document.getElementById('scheduleDescription').value
        };

        const response = await fetch('../api/schedule.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.success) {
            alert('Event saved!');
            document.getElementById('scheduleForm').reset();
            loadScheduleData();
        }
    }

    async function loadScheduleData() {
        const response = await fetch('../api/schedule.php');
        const data = await response.json();
        const list = document.getElementById('scheduleList');

        if (data.success && data.events.length > 0) {
            list.innerHTML = data.events.map(e => `
                <div class="class-item" style="margin-bottom: 1rem;">
                    <div class="class-details">
                        <h5>${e.title}</h5>
                        <p>${e.event_date} ${e.time_range} | ${e.description || 'No description'}</p>
                    </div>
                    <button class="primary-btn small" onclick="deleteItem('schedule', ${e.id})">Delete</button>
                </div>
            `).join('');
        } else {
            list.innerHTML = '<p style="color: var(--text-muted);">No events yet.</p>';
        }
    }

    // Global Delete Function
    window.deleteItem = async function (type, id) {
        if (!confirm('Are you sure you want to delete this item?')) return;

        const response = await fetch(`../api/${type}.php?id=${id}`, { method: 'DELETE' });
        const result = await response.json();

        if (result.success) {
            alert('Deleted successfully!');
            loadTab(type === 'assignments' ? 'assignments' : type === 'curriculum' ? 'curriculum' : type === 'community' ? 'community' : 'schedule');
        }
    };

    // Load default tab
    loadTab('assignments');
});
