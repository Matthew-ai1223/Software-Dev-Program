document.addEventListener('DOMContentLoaded', () => {
    // ==========================================
    // 1. REGISTRATION PAGE LOGIC
    // ==========================================
    const form = document.getElementById('regForm');

    if (form) {
        const submitBtn = document.getElementById('submitBtn');
        const successModal = document.getElementById('successModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const termsModal = document.getElementById('termsModal');
        const openTermsBtn = document.getElementById('openTermsBtn');
        const closeTermsBtn = document.getElementById('closeTermsBtn');
        const termsCheckbox = document.getElementById('terms');

        // Form Submit
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const termsAccepted = termsCheckbox.checked;

            if (!termsAccepted) {
                alert('You must accept the Terms & Conditions to register.');
                return;
            }

            if (fullname.trim() === '' || email.trim() === '') {
                alert('Please fill in all required fields.');
                return;
            }

            setLoading(submitBtn, true);

            // Real API Call to Backend
            try {
                const response = await fetch('backend/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        fullname: fullname,
                        email: email,
                        phone: document.getElementById('phone').value,
                        github: document.getElementById('github') ? document.getElementById('github').value : '',
                        track: document.querySelector('input[name="track"]:checked').value,
                        experience: document.getElementById('experience').value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.setItem('userId', data.userId); // Save User ID
                    showModal(successModal);
                    form.reset();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                setLoading(submitBtn, false);
            }
        });

        // Modals
        if (openTermsBtn) {
            openTermsBtn.addEventListener('click', (e) => {
                e.preventDefault();
                showModal(termsModal);
            });
        }
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => {
                hideModal(successModal);
                window.location.href = 'dashbord.html';
            });
        }
        if (closeTermsBtn) closeTermsBtn.addEventListener('click', () => hideModal(termsModal));
    }

    // ==========================================
    // 2. CENTRALIZED USER DATA LOGIC (Dashboard & Settings)
    // ==========================================
    const userId = localStorage.getItem('userId');
    const isProtectedPage = document.querySelector('.dashboard-body'); // Dashboard & Settings have this class

    // Redirect if not logged in on protected pages
    if (isProtectedPage && !userId) {
        window.location.href = 'reg.html';
    }

    // Fetch and Update UI if User ID exists
    if (userId && isProtectedPage) {
        fetchUserData(userId);
    }

    function fetchUserData(id) {
        fetch(`backend/get_user.php?id=${id}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                if (data.success && data.user) {
                    updateUserInterface(data.user);
                } else {
                    console.error('Failed to fetch user data:', data.message);
                    alert("Session Error: " + data.message + ". Please login again.");
                    localStorage.removeItem('userId');
                    window.location.href = 'reg.html';
                }
            })
            .catch(e => {
                console.error("Error loading user data:", e);
                // Optionally alert specific network errors
            });
    }

    function updateUserInterface(u) {
        // A. Update Header/Sidebar Elements (Present on Dashboard & Settings)
        const userNameEls = document.querySelectorAll('.user-name');
        const userRoleEl = document.querySelector('.user-role');
        const avatarEls = document.querySelectorAll('.avatar span');
        const welcomeMsg = document.querySelector('.welcome-text h3');

        userNameEls.forEach(el => el.textContent = u.fullname);

        if (userRoleEl) {
            userRoleEl.textContent = u.track.charAt(0).toUpperCase() + u.track.slice(1) + ' Track';
        }

        if (avatarEls.length > 0) {
            const initials = u.fullname.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            avatarEls.forEach(el => el.textContent = initials);
        }

        if (welcomeMsg) {
            const firstName = u.fullname.split(' ')[0];
            welcomeMsg.innerHTML = `Welcome back, ${firstName}! 👋`;
        }

        // B. Update Settings Form Inputs (If present)
        const nameInput = document.getElementById('settingsInternalName');
        const emailInput = document.getElementById('settingsEmail');
        const githubInput = document.getElementById('settingsGithub');
        const bioInput = document.getElementById('settingsBio');

        if (nameInput) nameInput.value = u.fullname;
        if (emailInput) emailInput.value = u.email;
        if (githubInput) githubInput.value = u.github_link || '';
        if (bioInput) bioInput.value = u.bio || '';

        // C. Update Tech Stack Checkboxes
        if (u.tech_stack) {
            try {
                const techStackArray = JSON.parse(u.tech_stack);
                const checkboxes = document.querySelectorAll('input[name="techStack"]');
                checkboxes.forEach(checkbox => {
                    if (techStackArray.includes(checkbox.value)) {
                        checkbox.checked = true;
                    }
                });
            } catch (e) {
                console.error('Error parsing tech stack:', e);
            }
        }
    }

    // ==========================================
    // 3. UI INTERACTION LOGIC
    // ==========================================
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.querySelector('.sidebar');

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

    // Logout Logic
    const logoutBtns = document.querySelectorAll('.logout');
    logoutBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            localStorage.removeItem('userId');
        });
    });

    // ==========================================
    // 4. SETTINGS PAGE ACTIONS
    // ==========================================
    const settingsForm = document.getElementById('settingsForm');
    const deleteBtn = document.getElementById('deleteAccountBtn');

    if (settingsForm) {
        const saveBtn = document.getElementById('saveSettingsBtn');
        const nameInput = document.getElementById('settingsInternalName');
        const emailInput = document.getElementById('settingsEmail');
        const githubInput = document.getElementById('settingsGithub');
        const bioInput = document.getElementById('settingsBio');

        // Handle Update Profile
        settingsForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            setLoading(saveBtn, true);

            // Get selected tech stack
            const selectedTech = [];
            document.querySelectorAll('input[name="techStack"]:checked').forEach(checkbox => {
                selectedTech.push(checkbox.value);
            });

            try {
                const response = await fetch('backend/update_user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        userId: userId,
                        fullname: nameInput.value,
                        email: emailInput.value,
                        bio: bioInput.value,
                        techStack: JSON.stringify(selectedTech),
                        github: githubInput ? githubInput.value : ''
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Profile updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating profile.');
            } finally {
                setLoading(saveBtn, false);
            }
        });

        // Handle Delete Account
        if (deleteBtn) {
            deleteBtn.addEventListener('click', async () => {
                if (confirm('Are you strictly sure you want to delete your account? This action cannot be undone.')) {
                    setLoading(deleteBtn, true);
                    try {
                        const response = await fetch('backend/delete_user.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ userId: userId })
                        });

                        const data = await response.json();

                        if (data.success) {
                            alert('Account deleted successfully.');
                            localStorage.removeItem('userId');
                            window.location.href = 'reg.html';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while deleting account.');
                    } finally {
                        setLoading(deleteBtn, false);
                    }
                }
            });
        }
    }

    // ==========================================
    // 5. HELPER FUNCTIONS
    // ==========================================
    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal')) {
            hideModal(e.target);
        }
    });

    function setLoading(btn, isLoading) {
        if (isLoading) {
            btn.classList.add('loading');
            btn.disabled = true;
        } else {
            btn.classList.remove('loading');
            btn.disabled = false;
        }
    }

    function showModal(modalEl) {
        if (!modalEl) return;
        modalEl.style.display = 'flex';
        setTimeout(() => modalEl.classList.add('show'), 10);
    }

    function hideModal(modalEl) {
        if (!modalEl) return;
        modalEl.classList.remove('show');
        setTimeout(() => modalEl.style.display = 'none', 300);
    }

    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            // Optional focus logic
        });
    });
});
