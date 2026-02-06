// Dynamic Content Loader for Student Pages
document.addEventListener('DOMContentLoaded', () => {
    const currentPage = window.location.pathname.split('/').pop();

    // Determine which content to load based on current page
    switch (currentPage) {
        case 'assignments.html':
            loadAssignmentsContent();
            break;
        case 'curriculum.html':
            loadCurriculumContent();
            break;
        case 'community.html':
            loadCommunityContent();
            break;
        case 'schedule.html':
            loadScheduleContent();
            break;
    }

    // ==================== ASSIGNMENTS ====================
    async function loadAssignmentsContent() {
        try {
            const response = await fetch('backend/api/assignments.php');
            const data = await response.json();

            if (!data.success) return;

            const activeAssignments = data.assignments.filter(a => a.status === 'active');
            const gradedAssignments = data.assignments.filter(a => a.status === 'graded');

            // Update Active Assignment Section
            const activeSection = document.querySelector('.welcome-card .welcome-text');
            if (activeSection && activeAssignments.length > 0) {
                const active = activeAssignments[0];
                const dueDate = new Date(active.due_date);
                activeSection.innerHTML = `
                    <h3>Pending Task: ${active.title}</h3>
                    <p>${active.description}</p>
                    <p style="font-size: 0.85rem; margin-top: 0.5rem; color: #ef4444;">
                        <i class="ph-bold ph-warning"></i> Due: ${dueDate.toLocaleString()}
                    </p>
                    <button class="primary-btn small" style="margin-top: 1rem;">Submit Project</button>
                `;
            }

            // Update Graded Assignments List
            const gradedList = document.querySelector('.content-card .class-list');
            if (gradedList && gradedAssignments.length > 0) {
                gradedList.innerHTML = gradedAssignments.map(a => `
                    <div class="class-item">
                        <div class="class-details">
                            <h5>${a.title}</h5>
                            <p>Submitted • Grade: <strong>${a.grade || 'N/A'}/100</strong></p>
                        </div>
                        <span style="color: var(--success); font-weight: 600;">Passed</span>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading assignments:', error);
        }
    }

    // ==================== CURRICULUM ====================
    async function loadCurriculumContent() {
        try {
            const response = await fetch('backend/api/curriculum.php');
            const data = await response.json();

            if (!data.success) return;

            // Group by module
            const modules = {};
            data.curriculum.forEach(item => {
                if (!modules[item.module_title]) {
                    modules[item.module_title] = [];
                }
                modules[item.module_title].push(item);
            });

            // Update curriculum cards
            const grid = document.querySelector('.dashboard-grid');
            if (grid) {
                grid.innerHTML = Object.keys(modules).map(moduleTitle => {
                    const items = modules[moduleTitle];
                    const status = items[0].status;

                    return `
                        <div class="content-card full-width">
                            <div class="card-header-row">
                                <h4>${moduleTitle}</h4>
                                <span class="status-badge ${status === 'completed' ? 'complete' : ''}" 
                                      style="color: var(${status === 'completed' ? '--success' : '--primary'});">
                                    ${status === 'completed' ? 'Completed' : status === 'in_progress' ? 'In Progress' : 'Locked'}
                                </span>
                            </div>
                            <div class="class-list">
                                ${items.map(week => `
                                    <div class="class-item">
                                        <div class="class-details">
                                            <h5>${week.week_title}</h5>
                                            <p>${week.description}</p>
                                        </div>
                                        <button class="primary-btn small" ${week.status === 'locked' ? 'disabled' : ''}>
                                            ${week.status === 'locked' ? 'Locked' : week.status === 'completed' ? 'Review' : 'Continue'}
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }).join('');
            }
        } catch (error) {
            console.error('Error loading curriculum:', error);
        }
    }

    // ==================== COMMUNITY ====================
    async function loadCommunityContent() {
        try {
            const response = await fetch('backend/api/community.php');
            const data = await response.json();

            if (!data.success) return;

            const announcements = data.posts.filter(p => p.type === 'announcement');
            const discussions = data.posts.filter(p => p.type === 'discussion');

            // Update Announcements
            const announcementsList = document.querySelector('.content-card:first-child .class-list');
            if (announcementsList) {
                announcementsList.innerHTML = announcements.map(post => `
                    <div class="class-item">
                        <div class="date-box" style="border-color: var(--primary);">
                            <i class="ph-fill ph-megaphone" style="color: var(--primary);"></i>
                        </div>
                        <div class="class-details">
                            <h5>${post.title}</h5>
                            <p>${post.content}</p>
                        </div>
                        <button class="primary-btn small">RSVP</button>
                    </div>
                `).join('');
            }

            // Update Discussions
            const discussionsList = document.querySelector('.content-card.wide .class-list');
            if (discussionsList) {
                discussionsList.innerHTML = discussions.map(post => `
                    <div class="class-item">
                        <div class="class-details">
                            <h5>${post.title}</h5>
                            <p>Started by <strong>${post.author}</strong> • ${post.replies || 0} replies</p>
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading community:', error);
        }
    }

    // ==================== SCHEDULE ====================
    async function loadScheduleContent() {
        try {
            const response = await fetch('backend/api/schedule.php');
            const data = await response.json();

            if (!data.success) return;

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const todayEvents = data.events.filter(e => {
                const eventDate = new Date(e.event_date);
                eventDate.setHours(0, 0, 0, 0);
                return eventDate.getTime() === today.getTime();
            });

            const upcomingEvents = data.events.filter(e => {
                const eventDate = new Date(e.event_date);
                return eventDate > today;
            }).slice(0, 5);

            // Update Today's Schedule
            const todayCard = document.querySelector('.welcome-card .class-item');
            if (todayCard && todayEvents.length > 0) {
                const event = todayEvents[0];
                const eventDate = new Date(event.event_date);

                todayCard.innerHTML = `
                    <div class="date-box">
                        <span class="day">${eventDate.getDate().toString().padStart(2, '0')}</span>
                    </div>
                    <div class="class-details">
                        <h5 style="font-size: 1.1rem; color: var(--text-main);">${event.title}</h5>
                        <p>${event.time_range} • ${event.description || 'No description'}</p>
                    </div>
                    <button class="primary-btn small">Join Now</button>
                `;
            }

            // Update Upcoming Events
            const upcomingList = document.querySelector('.content-card .class-list');
            if (upcomingList) {
                upcomingList.innerHTML = upcomingEvents.map(event => {
                    const eventDate = new Date(event.event_date);
                    const month = eventDate.toLocaleString('default', { month: 'short' });
                    const day = eventDate.getDate();

                    return `
                        <div class="class-item">
                            <div class="date-box">
                                <span class="day">${day}</span>
                                <span class="month">${month}</span>
                            </div>
                            <div class="class-details">
                                <h5>${event.title}</h5>
                                <p>${event.time_range}</p>
                            </div>
                        </div>
                    `;
                }).join('');
            }
        } catch (error) {
            console.error('Error loading schedule:', error);
        }
    }
});
