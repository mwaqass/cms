<template>
  <div class="admin-layout">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-header">
        <div class="logo">
          <span class="logo-icon">🏢</span>
          <span class="logo-text">CMS Admin</span>
        </div>
      </div>

      <nav class="sidebar-nav">
        <div class="nav-section">
          <h3 class="nav-title">Dashboard</h3>
          <router-link to="/" class="nav-item" active-class="active">
            <span class="nav-icon">📊</span>
            <span class="nav-text">Overview</span>
          </router-link>
        </div>

        <div class="nav-section">
          <h3 class="nav-title">Content</h3>
          <router-link to="/posts" class="nav-item" active-class="active">
            <span class="nav-icon">📝</span>
            <span class="nav-text">Posts</span>
          </router-link>
          <router-link to="/pages" class="nav-item" active-class="active">
            <span class="nav-icon">📄</span>
            <span class="nav-text">Pages</span>
          </router-link>
          <router-link to="/categories" class="nav-item" active-class="active">
            <span class="nav-icon">📁</span>
            <span class="nav-text">Categories</span>
          </router-link>
          <router-link to="/media" class="nav-item" active-class="active">
            <span class="nav-icon">📁</span>
            <span class="nav-text">Media</span>
          </router-link>
        </div>

        <div class="nav-section">
          <h3 class="nav-title">Users & Access</h3>
          <router-link to="/users" class="nav-item" active-class="active">
            <span class="nav-icon">👥</span>
            <span class="nav-text">Users</span>
          </router-link>
          <router-link to="/roles" class="nav-item" active-class="active">
            <span class="nav-icon">🔐</span>
            <span class="nav-text">Roles</span>
          </router-link>
        </div>

        <div class="nav-section">
          <h3 class="nav-title">Analytics</h3>
          <router-link to="/analytics" class="nav-item" active-class="active">
            <span class="nav-icon">📈</span>
            <span class="nav-text">Analytics</span>
          </router-link>
          <router-link to="/reports" class="nav-item" active-class="active">
            <span class="nav-icon">📋</span>
            <span class="nav-text">Reports</span>
          </router-link>
        </div>

        <div class="nav-section">
          <h3 class="nav-title">Settings</h3>
          <router-link to="/settings" class="nav-item" active-class="active">
            <span class="nav-icon">⚙️</span>
            <span class="nav-text">Site Settings</span>
          </router-link>
        </div>
      </nav>

      <div class="sidebar-footer">
        <div class="user-info">
          <div class="user-avatar">👤</div>
          <div class="user-details">
            <div class="user-name">{{ userInfo.name }}</div>
            <div class="user-role">{{ userInfo.role }}</div>
          </div>
        </div>
        <button @click="logout" class="logout-btn">
          <span class="logout-icon">🚪</span>
          Logout
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Top Bar -->
      <div class="topbar">
        <div class="topbar-left">
          <button @click="toggleSidebar" class="menu-toggle">
            <span class="menu-icon">☰</span>
          </button>
          <div class="breadcrumb">
            <span class="breadcrumb-item">{{ currentPage }}</span>
          </div>
        </div>

        <div class="topbar-right">
          <div class="notifications">
            <button class="notification-btn">
              <span class="notification-icon">🔔</span>
              <span class="notification-badge">3</span>
            </button>
          </div>
          <div class="user-menu">
            <button class="user-menu-btn">
              <span class="user-avatar-small">👤</span>
              <span class="user-name-small">{{ userInfo.name }}</span>
              <span class="dropdown-arrow">▼</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Page Content -->
      <div class="page-content">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();

const userInfo = ref({
  name: 'Admin User',
  role: 'Administrator'
});

const currentPage = computed(() => {
  const routeMap = {
    '/': 'Dashboard',
    '/users': 'Users',
    '/roles': 'Roles',
    '/posts': 'Posts',
    '/pages': 'Pages',
    '/categories': 'Categories',
    '/media': 'Media',
    '/analytics': 'Analytics',
    '/reports': 'Reports',
    '/settings': 'Settings'
  };
  return routeMap[route.path] || 'Dashboard';
});

function toggleSidebar() {
  const sidebar = document.querySelector('.sidebar');
  sidebar.classList.toggle('collapsed');
}

function logout() {
  localStorage.removeItem('auth_token');
  delete axios.defaults.headers.common['Authorization'];
  router.push('/login');
}

onMounted(() => {
  // Fetch user info if needed
  const token = localStorage.getItem('auth_token');
  if (token) {
    axios.get('/api/user')
      .then(res => {
        userInfo.value = {
          name: res.data.name || 'Admin User',
          role: res.data.role || 'Administrator'
        };
      })
      .catch(err => {
        console.log('Using default user info');
      });
  }
});
</script>

<style scoped>
.admin-layout {
  display: flex;
  height: 100vh;
  background: #f8fafc;
}

.sidebar {
  width: 280px;
  background: #fff;
  border-right: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  transition: width 0.3s ease;
  box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
}

.sidebar.collapsed {
  width: 80px;
}

.sidebar-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.logo-icon {
  font-size: 2rem;
}

.logo-text {
  font-size: 1.25rem;
  font-weight: 700;
  color: #111827;
}

.sidebar.collapsed .logo-text {
  display: none;
}

.sidebar-nav {
  flex: 1;
  padding: 1rem 0;
  overflow-y: auto;
}

.nav-section {
  margin-bottom: 2rem;
}

.nav-title {
  font-size: 0.75rem;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 0 1.5rem 0.5rem;
  margin: 0;
}

.sidebar.collapsed .nav-title {
  display: none;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1.5rem;
  color: #374151;
  text-decoration: none;
  transition: all 0.2s;
  border-radius: 0.5rem;
  margin: 0 0.5rem;
}

.nav-item:hover {
  background: #f3f4f6;
  color: #111827;
}

.nav-item.active {
  background: #eff6ff;
  color: #2563eb;
  font-weight: 500;
}

.nav-icon {
  font-size: 1.25rem;
  width: 1.5rem;
  text-align: center;
}

.nav-text {
  font-size: 0.875rem;
  font-weight: 500;
}

.sidebar.collapsed .nav-text {
  display: none;
}

.sidebar-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid #e5e7eb;
  background: #f9fafb;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.user-avatar {
  width: 2.5rem;
  height: 2.5rem;
  background: #e5e7eb;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
}

.user-details {
  flex: 1;
}

.user-name {
  font-size: 0.875rem;
  font-weight: 600;
  color: #111827;
}

.user-role {
  font-size: 0.75rem;
  color: #6b7280;
}

.sidebar.collapsed .user-details {
  display: none;
}

.logout-btn {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem;
  background: #dc2626;
  color: #fff;
  border: none;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}

.logout-btn:hover {
  background: #b91c1c;
}

.logout-icon {
  font-size: 1rem;
}

.sidebar.collapsed .logout-btn {
  justify-content: center;
}

.sidebar.collapsed .logout-btn span:not(.logout-icon) {
  display: none;
}

.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.topbar {
  background: #fff;
  border-bottom: 1px solid #e5e7eb;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.menu-toggle {
  background: none;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 0.375rem;
  transition: background 0.2s;
}

.menu-toggle:hover {
  background: #f3f4f6;
}

.breadcrumb {
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.notifications {
  position: relative;
}

.notification-btn {
  background: none;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 0.375rem;
  position: relative;
  transition: background 0.2s;
}

.notification-btn:hover {
  background: #f3f4f6;
}

.notification-badge {
  position: absolute;
  top: 0;
  right: 0;
  background: #dc2626;
  color: #fff;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 0.125rem 0.375rem;
  border-radius: 1rem;
  min-width: 1.25rem;
  text-align: center;
}

.user-menu-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: none;
  border: none;
  padding: 0.5rem;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: background 0.2s;
}

.user-menu-btn:hover {
  background: #f3f4f6;
}

.user-avatar-small {
  width: 2rem;
  height: 2rem;
  background: #e5e7eb;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
}

.user-name-small {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.dropdown-arrow {
  font-size: 0.75rem;
  color: #6b7280;
}

.page-content {
  flex: 1;
  overflow-y: auto;
  padding: 0;
}

@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    left: -280px;
    top: 0;
    height: 100vh;
    z-index: 1000;
    transition: left 0.3s ease;
  }

  .sidebar.open {
    left: 0;
  }

  .main-content {
    margin-left: 0;
  }

  .topbar {
    padding: 1rem;
  }

  .breadcrumb {
    font-size: 1rem;
  }
}
</style>
