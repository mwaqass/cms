<template>
  <div class="dashboard">
    <div class="dashboard-header">
      <h1>Dashboard</h1>
      <div class="header-actions">
        <button @click="refreshData" class="refresh-btn">
          <span class="refresh-icon">🔄</span>
          Refresh
        </button>
        <button @click="logout" class="logout-btn">Logout</button>
      </div>
    </div>

    <!-- Overview Cards -->
    <div class="overview-grid">
      <div class="overview-card users-card">
        <div class="card-icon">👥</div>
        <div class="card-content">
          <h3>Total Users</h3>
          <div class="card-value">{{ overview.totalUsers }}</div>
          <div class="card-change positive">+{{ overview.userGrowth }}% this month</div>
        </div>
      </div>

      <div class="overview-card posts-card">
        <div class="card-icon">📄</div>
        <div class="card-content">
          <h3>Total Posts</h3>
          <div class="card-value">{{ overview.totalPosts }}</div>
          <div class="card-change positive">+{{ overview.postGrowth }}% this month</div>
        </div>
      </div>

      <div class="overview-card views-card">
        <div class="card-icon">👁️</div>
        <div class="card-content">
          <h3>Page Views</h3>
          <div class="card-value">{{ overview.pageViews }}</div>
          <div class="card-change positive">+{{ overview.viewGrowth }}% this month</div>
        </div>
      </div>

      <div class="overview-card comments-card">
        <div class="card-icon">💬</div>
        <div class="card-content">
          <h3>Comments</h3>
          <div class="card-value">{{ overview.totalComments }}</div>
          <div class="card-change positive">+{{ overview.commentGrowth }}% this month</div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
      <div class="chart-container">
        <h3>User Growth</h3>
        <div class="chart-wrapper">
          <div class="chart-bars">
            <div
              v-for="(value, index) in userGrowthData"
              :key="index"
              class="chart-bar"
              :style="{ height: (value / Math.max(...userGrowthData) * 100) + '%' }"
            >
              <span class="bar-value">{{ value }}</span>
            </div>
          </div>
          <div class="chart-labels">
            <span v-for="(label, index) in chartLabels" :key="index">{{ label }}</span>
          </div>
        </div>
      </div>

      <div class="chart-container">
        <h3>Content Performance</h3>
        <div class="performance-list">
          <div v-for="content in topContent" :key="content.id" class="performance-item">
            <div class="content-info">
              <div class="content-title">{{ content.title }}</div>
              <div class="content-stats">
                <span class="views">{{ content.views }} views</span>
                <span class="comments">{{ content.comments }} comments</span>
              </div>
            </div>
            <div class="performance-score">
              <div class="score-bar">
                <div class="score-fill" :style="{ width: content.score + '%' }"></div>
              </div>
              <span class="score-text">{{ content.score }}%</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <h3>Quick Actions</h3>
      <div class="actions-grid">
        <button @click="navigateTo('/users')" class="action-card">
          <div class="action-icon">👥</div>
          <div class="action-text">Manage Users</div>
        </button>
        <button @click="navigateTo('/posts')" class="action-card">
          <div class="action-icon">📝</div>
          <div class="action-text">Create Post</div>
        </button>
        <button @click="navigateTo('/pages')" class="action-card">
          <div class="action-icon">📄</div>
          <div class="action-text">Create Page</div>
        </button>
        <button @click="navigateTo('/media')" class="action-card">
          <div class="action-icon">📁</div>
          <div class="action-text">Upload Media</div>
        </button>
        <button @click="navigateTo('/menus')" class="action-card">
          <div class="action-icon">🍔</div>
          <div class="action-text">Manage Menus</div>
        </button>
        <button @click="navigateTo('/settings')" class="action-card">
          <div class="action-icon">⚙️</div>
          <div class="action-text">Site Settings</div>
        </button>
      </div>
    </div>

    <!-- Recent Activity & System Status -->
    <div class="bottom-section">
      <div class="recent-activity">
        <h3>Recent Activity</h3>
        <div class="activity-list">
          <div v-for="activity in recentActivity" :key="activity.id" class="activity-item">
            <div class="activity-icon">{{ activity.icon }}</div>
            <div class="activity-content">
              <div class="activity-text">{{ activity.text }}</div>
              <div class="activity-time">{{ formatTime(activity.created_at) }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="system-status">
        <h3>System Status</h3>
        <div class="status-grid">
          <div class="status-item">
            <div class="status-label">CPU Usage</div>
            <div class="status-bar">
              <div class="status-fill" :style="{ width: systemStatus.cpuUsage + '%' }"></div>
            </div>
            <div class="status-value">{{ systemStatus.cpuUsage }}%</div>
          </div>
          <div class="status-item">
            <div class="status-label">Memory Usage</div>
            <div class="status-bar">
              <div class="status-fill" :style="{ width: systemStatus.memoryUsage + '%' }"></div>
            </div>
            <div class="status-value">{{ systemStatus.memoryUsage }}%</div>
          </div>
          <div class="status-item">
            <div class="status-label">Disk Usage</div>
            <div class="status-bar">
              <div class="status-fill" :style="{ width: systemStatus.diskUsage + '%' }"></div>
            </div>
            <div class="status-value">{{ systemStatus.diskUsage }}%</div>
          </div>
          <div class="status-item">
            <div class="status-label">Uptime</div>
            <div class="status-value uptime">{{ systemStatus.uptime }}%</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Traffic Sources -->
    <div class="traffic-sources">
      <h3>Traffic Sources</h3>
      <div class="sources-grid">
        <div v-for="source in trafficSources" :key="source.name" class="source-item">
          <div class="source-info">
            <span class="source-name">{{ source.name }}</span>
            <span class="source-percentage">{{ source.percentage }}%</span>
          </div>
          <div class="source-bar">
            <div class="source-fill" :style="{ width: source.percentage + '%' }"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const overview = ref({
  totalUsers: 0,
  userGrowth: 0,
  totalPosts: 0,
  postGrowth: 0,
  pageViews: 0,
  viewGrowth: 0,
  totalComments: 0,
  commentGrowth: 0,
});

const userGrowthData = ref([120, 150, 180, 200, 220, 250, 280]);
const chartLabels = ref(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);

const topContent = ref([
  { id: 1, title: 'Getting Started with Laravel', views: 1250, comments: 45, score: 95 },
  { id: 2, title: 'Vue.js Best Practices', views: 980, comments: 32, score: 87 },
  { id: 3, title: 'CSS Grid Layout Guide', views: 750, comments: 28, score: 82 },
  { id: 4, title: 'API Design Principles', views: 620, comments: 19, score: 78 },
]);

const recentActivity = ref([
  { id: 1, icon: '👤', text: 'New user registered: john.doe@example.com', created_at: new Date(Date.now() - 5 * 60 * 1000) },
  { id: 2, icon: '📝', text: 'New post published: "Advanced Vue.js Techniques"', created_at: new Date(Date.now() - 15 * 60 * 1000) },
  { id: 3, icon: '💬', text: 'New comment on "Laravel Best Practices"', created_at: new Date(Date.now() - 30 * 60 * 1000) },
  { id: 4, icon: '👤', text: 'User profile updated: jane.smith@example.com', created_at: new Date(Date.now() - 45 * 60 * 1000) },
  { id: 5, icon: '📄', text: 'Page "About Us" was updated', created_at: new Date(Date.now() - 60 * 60 * 1000) },
]);

const systemStatus = ref({
  cpuUsage: 45,
  memoryUsage: 62,
  diskUsage: 78,
  uptime: 99.8,
});

const trafficSources = ref([
  { name: 'Direct', percentage: 35 },
  { name: 'Google', percentage: 28 },
  { name: 'Social Media', percentage: 20 },
  { name: 'Referral', percentage: 12 },
  { name: 'Other', percentage: 5 },
]);

function fetchDashboardData() {
  axios.get('/api/analytics?days=30')
    .then(res => {
      console.log('Dashboard data:', res.data);
      // Update overview with real data
    })
    .catch(err => {
      console.log('Using mock dashboard data');
      // Use mock data for demonstration
      overview.value = {
        totalUsers: 1247,
        userGrowth: 12.5,
        totalPosts: 89,
        postGrowth: 8.3,
        pageViews: 15420,
        viewGrowth: 15.7,
        totalComments: 342,
        commentGrowth: 5.2,
      };
    });
}

function refreshData() {
  fetchDashboardData();
}

function logout() {
  localStorage.removeItem('auth_token');
  delete axios.defaults.headers.common['Authorization'];
  router.push('/login');
}

function navigateTo(path) {
  router.push(path);
}

function formatTime(date) {
  const now = new Date();
  const diff = now - date;
  const minutes = Math.floor(diff / 60000);
  const hours = Math.floor(diff / 3600000);
  const days = Math.floor(diff / 86400000);

  if (minutes < 60) return `${minutes} minutes ago`;
  if (hours < 24) return `${hours} hours ago`;
  return `${days} days ago`;
}

onMounted(() => {
  fetchDashboardData();
});
</script>

<style scoped>
.dashboard {
  padding: 2rem;
  background: #f8fafc;
  min-height: 100vh;
}

.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.dashboard-header h1 {
  font-size: 2rem;
  font-weight: 700;
  color: #111827;
  margin: 0;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

.refresh-btn, .logout-btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.refresh-btn {
  background: #2563eb;
  color: #fff;
}

.refresh-btn:hover {
  background: #1e40af;
}

.logout-btn {
  background: #dc2626;
  color: #fff;
}

.logout-btn:hover {
  background: #b91c1c;
}

.refresh-icon {
  font-size: 1rem;
}

.overview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.overview-card {
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.overview-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.card-icon {
  font-size: 2.5rem;
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 16px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
}

.users-card .card-icon {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.posts-card .card-icon {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.views-card .card-icon {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.comments-card .card-icon {
  background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.card-content h3 {
  margin: 0 0 0.5rem 0;
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
}

.card-value {
  font-size: 2rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 0.25rem;
}

.card-change {
  font-size: 0.875rem;
  font-weight: 500;
}

.card-change.positive {
  color: #059669;
}

.card-change.negative {
  color: #dc2626;
}

.charts-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

.chart-container {
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.chart-container h3 {
  margin: 0 0 1rem 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.chart-wrapper {
  height: 200px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.chart-bars {
  display: flex;
  align-items: end;
  justify-content: space-between;
  height: 150px;
  gap: 0.5rem;
}

.chart-bar {
  background: linear-gradient(to top, #2563eb, #3b82f6);
  border-radius: 4px 4px 0 0;
  min-width: 30px;
  position: relative;
  transition: height 0.3s ease;
}

.bar-value {
  position: absolute;
  top: -25px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 0.75rem;
  font-weight: 500;
  color: #374151;
}

.chart-labels {
  display: flex;
  justify-content: space-between;
  font-size: 0.875rem;
  color: #6b7280;
}

.performance-list {
  max-height: 300px;
  overflow-y: auto;
}

.performance-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
  border-bottom: 1px solid #f3f4f6;
}

.performance-item:last-child {
  border-bottom: none;
}

.content-info {
  flex: 1;
}

.content-title {
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.25rem;
}

.content-stats {
  display: flex;
  gap: 1rem;
  font-size: 0.875rem;
  color: #6b7280;
}

.performance-score {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.score-bar {
  width: 60px;
  height: 8px;
  background: #f3f4f6;
  border-radius: 4px;
  overflow: hidden;
}

.score-fill {
  height: 100%;
  background: linear-gradient(to right, #2563eb, #3b82f6);
  border-radius: 4px;
  transition: width 0.3s ease;
}

.score-text {
  font-size: 0.875rem;
  font-weight: 500;
  color: #6b7280;
  min-width: 35px;
}

.quick-actions {
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.quick-actions h3 {
  margin: 0 0 1rem 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
}

.action-card {
  background: #f8fafc;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
}

.action-card:hover {
  border-color: #2563eb;
  background: #eff6ff;
  transform: translateY(-2px);
}

.action-icon {
  font-size: 2rem;
}

.action-text {
  font-weight: 500;
  color: #374151;
  text-align: center;
}

.bottom-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

.recent-activity, .system-status {
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.recent-activity h3, .system-status h3 {
  margin: 0 0 1rem 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.activity-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.activity-item {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1rem;
  background: #f9fafb;
  border-radius: 8px;
}

.activity-icon {
  font-size: 1.25rem;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.activity-content {
  flex: 1;
}

.activity-text {
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.25rem;
}

.activity-time {
  font-size: 0.875rem;
  color: #6b7280;
}

.status-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.status-item {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.status-label {
  min-width: 120px;
  font-weight: 500;
  color: #374151;
}

.status-bar {
  flex: 1;
  height: 8px;
  background: #f3f4f6;
  border-radius: 4px;
  overflow: hidden;
}

.status-fill {
  height: 100%;
  background: linear-gradient(to right, #2563eb, #3b82f6);
  border-radius: 4px;
  transition: width 0.3s ease;
}

.status-value {
  min-width: 50px;
  text-align: right;
  font-weight: 500;
  color: #6b7280;
}

.status-value.uptime {
  color: #059669;
}

.traffic-sources {
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.traffic-sources h3 {
  margin: 0 0 1rem 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.sources-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.source-item {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.source-info {
  display: flex;
  justify-content: space-between;
  min-width: 200px;
}

.source-name {
  font-weight: 500;
  color: #374151;
}

.source-percentage {
  font-weight: 500;
  color: #6b7280;
}

.source-bar {
  flex: 1;
  height: 8px;
  background: #f3f4f6;
  border-radius: 4px;
  overflow: hidden;
}

.source-fill {
  height: 100%;
  background: linear-gradient(to right, #2563eb, #3b82f6);
  border-radius: 4px;
  transition: width 0.3s ease;
}

@media (max-width: 768px) {
  .dashboard {
    padding: 1rem;
  }

  .dashboard-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .charts-section {
    grid-template-columns: 1fr;
  }

  .bottom-section {
    grid-template-columns: 1fr;
  }

  .actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
