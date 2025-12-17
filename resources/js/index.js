window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
            
    sidebar.classList.toggle('hidden');
    mainContent.classList.toggle('expanded');
};

window.toggleDropdown = function () {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('show');
};

window.editProfile = function (event) {
    event.stopPropagation();
    alert('Edit Profile clicked');
    window.toggleDropdown();
};

window.logout = function (event) {
    event.stopPropagation();
    if(confirm('Apakah Anda yakin ingin logout?')) {
        alert('Logout berhasil');
    }
    window.toggleDropdown();
};

// Toggle Role Dropdown
window.toggleRoleDropdown = function (event, dropdownId) {
    event.stopPropagation();
    
    // Tutup semua dropdown lain
    document.querySelectorAll('.role-dropdown').forEach(dropdown => {
        if (dropdown.id !== dropdownId) {
            dropdown.classList.remove('show');
        }
    });
    
    // Toggle dropdown yang diklik
    const dropdown = document.getElementById(dropdownId);
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
};

// Update Role User
window.updateRole = function (userId, newRole, tableName) {
    if(confirm(`Apakah Anda yakin ingin mengubah role menjadi ${newRole}?`)) {
        // Kirim request AJAX untuk update role
        fetch('/update-role', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: userId,
                role: newRole,
                table: tableName
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Role berhasil diubah!');
                location.reload(); // Reload halaman untuk update data
            } else {
                alert('Gagal mengubah role: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah role');
        });
    }
    
    // Tutup dropdown
    document.querySelectorAll('.role-dropdown').forEach(dropdown => {
        dropdown.classList.remove('show');
    });
};

// Menutup dropdown ketika klik di luar
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdownMenu');
    const userProfile = document.querySelector('.user-profile');
    
    // Tutup user profile dropdown
    if (userProfile && !userProfile.contains(event.target)) {
        if (dropdown) {
            dropdown.classList.remove('show');
        }
    }
    
    // Tutup semua role dropdown jika klik di luar
    if (!event.target.closest('.role-badge-wrapper')) {
        document.querySelectorAll('.role-dropdown').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});