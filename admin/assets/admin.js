document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    // Toggle sidebar
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (window.innerWidth <= 991) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        });
    }

    overlay.addEventListener('click', function () {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });

    // Auto-hide flash messages
    setTimeout(() => {
        document.querySelectorAll('.alert-dismissible').forEach(el => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert.close();
        });
    }, 4000);

    // ========== LOGOUT CONFIRMATION MODAL ==========
    const logoutModal = document.createElement('div');
    logoutModal.id = 'logoutConfirmModal';
    logoutModal.innerHTML = `
        <div class="logout-modal-overlay" id="logoutModalOverlay">
            <div class="logout-modal-box">
                <div class="logout-modal-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h5 class="logout-modal-title">Keluar dari Panel Admin?</h5>
                <p class="logout-modal-msg">Sesi Anda akan diakhiri dan Anda perlu login kembali untuk mengakses panel admin.</p>
                <div class="logout-modal-actions">
                    <button class="btn-logout-cancel" id="logoutModalCancel">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button class="btn-logout-confirm-btn" id="logoutModalConfirm">
                        <i class="fas fa-sign-out-alt me-1"></i> Ya, Logout
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(logoutModal);

    let logoutTargetUrl = null;

    function openLogoutModal(url) {
        logoutTargetUrl = url;
        document.getElementById('logoutModalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLogoutModal() {
        document.getElementById('logoutModalOverlay').classList.remove('active');
        document.body.style.overflow = '';
        logoutTargetUrl = null;
    }

    document.getElementById('logoutModalCancel').addEventListener('click', closeLogoutModal);
    document.getElementById('logoutModalOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeLogoutModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.getElementById('logoutModalOverlay').classList.contains('active')) {
            closeLogoutModal();
        }
    });

    document.getElementById('logoutModalConfirm').addEventListener('click', function () {
        if (logoutTargetUrl) {
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Keluar...';
            this.disabled = true;
            window.location.href = logoutTargetUrl;
        }
    });

    document.body.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-logout-confirm');
        if (!btn) return;
        e.preventDefault();
        openLogoutModal(btn.getAttribute('href'));
    });

    // ========== MODERN DELETE CONFIRMATION MODAL ==========
    // Create modal HTML once
    const deleteModal = document.createElement('div');
    deleteModal.id = 'deleteConfirmModal';
    deleteModal.innerHTML = `
        <div class="delete-modal-overlay" id="deleteModalOverlay">
            <div class="delete-modal-box">
                <div class="delete-modal-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h5 class="delete-modal-title">Hapus Data</h5>
                <p class="delete-modal-msg" id="deleteModalMsg">Yakin ingin menghapus data ini?</p>
                <p class="delete-modal-sub">Tindakan ini tidak dapat dibatalkan.</p>
                <div class="delete-modal-actions">
                    <button class="btn-modal-cancel" id="deleteModalCancel">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button class="btn-modal-confirm" id="deleteModalConfirm">
                        <i class="fas fa-trash-alt me-1"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(deleteModal);

    let deleteTargetUrl = null;

    function openDeleteModal(url, name) {
        deleteTargetUrl = url;
        const msg = document.getElementById('deleteModalMsg');
        msg.textContent = 'Yakin ingin menghapus ' + name + '?';
        const overlay = document.getElementById('deleteModalOverlay');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const overlay = document.getElementById('deleteModalOverlay');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        deleteTargetUrl = null;
    }

    document.getElementById('deleteModalCancel').addEventListener('click', closeDeleteModal);
    document.getElementById('deleteModalOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeDeleteModal();
    });

    document.getElementById('deleteModalConfirm').addEventListener('click', function () {
        if (deleteTargetUrl) {
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menghapus...';
            this.disabled = true;
            window.location.href = deleteTargetUrl;
        }
    });

    // Attach to all delete buttons (including dynamically added ones via delegation)
    document.body.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-confirm-delete');
        if (!btn) return;
        e.preventDefault();
        const url  = btn.getAttribute('href');
        const name = btn.getAttribute('data-name') || 'data ini';
        openDeleteModal(url, name);
    });

    // Image preview on file select
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function () {
            const previewId = this.getAttribute('data-preview');
            const preview   = document.getElementById(previewId);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Select all checkbox
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        });
    }
});
