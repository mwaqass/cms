<template>
  <div class="section">
    <h1>Analytics Dashboard</h1>

    <!-- Date Range Selector -->
    <div class="date-range">
      <label>Date Range:</label>
      <select v-model="dateRange" @change="fetchAnalytics">
        <option value="7">Last 7 days</option>
        <option value="30">Last 30 days</option>
        <option value="90">Last 90 days</option>
        <option value="365">Last year</option>
      </select>
    </div>

    <!-- Key Metrics -->
    <div class="metrics-grid">
      <div class="metric-card">
        <div class="metric-icon">👥</div>
        <div class="metric-content">
          <h3>Total Users</h3>
          <div class="metric-value">{{ metrics.totalUsers }}</div>
          <div class="metric-change" :class="metrics.userGrowth >= 0 ? 'positive' : 'negative'">
            {{ metrics.userGrowth >= 0 ? '+' : '' }}{{ metrics.userGrowth }}% from last period
          </div>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-icon">📄</div>
        <div class="metric-content">
          <h3>Total Posts</h3>
          <div class="metric-value">{{ metrics.totalPosts }}</div>
          <div class="metric-change" :class="metrics.postGrowth >= 0 ? 'positive' : 'negative'">
            {{ metrics.postGrowth >= 0 ? '+' : '' }}{{ metrics.postGrowth }}% from last period
          </div>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-icon">👁️</div>
        <div class="metric-content">
          <h3>Page Views</h3>
          <div class="metric-value">{{ metrics.pageViews }}</div>
          <div class="metric-change" :class="metrics.viewGrowth >= 0 ? 'positive' : 'negative'">
            {{ metrics.viewGrowth >= 0 ? '+' : '' }}{{ metrics.viewGrowth }}% from last period
          </div>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-icon">💬</div>
        <div class="metric-content">
          <h3>Comments</h3>
          <div class="metric-value">{{ metrics.totalComments }}</div>
          <div class="metric-change" :class="metrics.commentGrowth >= 0 ? 'positive' : 'negative'">
            {{ metrics.commentGrowth >= 0 ? '+' : '' }}{{ metrics.commentGrowth }}% from last period
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
      <div class="chart-container">
        <h3>User Growth</h3>
        <div class="chart-placeholder">
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
        <h3>Top Content</h3>
        <div class="top-content-list">
          <div v-for="content in topContent" :key="content.id" class="content-item">
            <div class="content-title">{{ content.title }}</div>
            <div class="content-stats">
              <span class="views">{{ content.views }} views</span>
              <span class="comments">{{ content.comments }} comments</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Traffic Sources -->
    <div class="traffic-sources">
      <h3>Traffic Sources</h3>
      <div class="sources-grid">
        <div v-for="source in trafficSources" :key="source.name" class="source-item">
          <div class="source-name">{{ source.name }}</div>
          <div class="source-bar">
            <div class="source-fill" :style="{ width: source.percentage + '%' }"></div>
          </div>
          <div class="source-percentage">{{ source.percentage }}%</div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
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
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const dateRange = ref('30');
const metrics = ref({
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
  { id: 1, title: 'Getting Started with Laravel', views: 1250, comments: 45 },
  { id: 2, title: 'Vue.js Best Practices', views: 980, comments: 32 },
  { id: 3, title: 'CSS Grid Layout Guide', views: 750, comments: 28 },
  { id: 4, title: 'API Design Principles', views: 620, comments: 19 },
  { id: 5, title: 'Database Optimization Tips', views: 480, comments: 15 },
]);

const trafficSources = ref([
  { name: 'Direct', percentage: 35 },
  { name: 'Google', percentage: 28 },
  { name: 'Social Media', percentage: 20 },
  { name: 'Referral', percentage: 12 },
  { name: 'Other', percentage: 5 },
]);

const recentActivity = ref([
  { id: 1, icon: '👤', text: 'New user registered: john.doe@example.com', created_at: new Date(Date.now() - 5 * 60 * 1000) },
  { id: 2, icon: '📝', text: 'New post published: "Advanced Vue.js Techniques"', created_at: new Date(Date.now() - 15 * 60 * 1000) },
  { id: 3, icon: '💬', text: 'New comment on "Laravel Best Practices"', created_at: new Date(Date.now() - 30 * 60 * 1000) },
  { id: 4, icon: '👤', text: 'User profile updated: jane.smith@example.com', created_at: new Date(Date.now() - 45 * 60 * 1000) },
  { id: 5, icon: '📄', text: 'Page "About Us" was updated', created_at: new Date(Date.now() - 60 * 60 * 1000) },
]);

function fetchAnalytics() {
  // In a real application, this would fetch data from the API
  // For now, we'll simulate the data
  axios.get(`/api/analytics?days=${dateRange.value}`)
    .then(res => {
      // Update metrics with real data
      console.log('Analytics data:', res.data);
    })
    .catch(err => {
      console.log('Using mock analytics data');
      // Use mock data for demonstration
      metrics.value = {
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
  fetchAnalytics();
});
</script>

<style scoped>
.section {
  padding: 2rem;
}
.section h1 {
  font-size: 2rem;
  margin-bottom: 1rem;
}
.date-range {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 2rem;
}
.date-range select {
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  font-size: 1rem;
}
.metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}
.metric-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.metric-icon {
  font-size: 2rem;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f3f4f6;
  border-radius: 12px;
}
.metric-content h3 {
  margin: 0 0 0.5rem 0;
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
}
.metric-value {
  font-size: 1.875rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 0.25rem;
}
.metric-change {
  font-size: 0.875rem;
  font-weight: 500;
}
.metric-change.positive {
  color: #059669;
}
.metric-change.negative {
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
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.chart-container h3 {
  margin: 0 0 1rem 0;
  font-size: 1.125rem;
  font-weight: 600;
}
.chart-placeholder {
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
.top-content-list {
  max-height: 300px;
  overflow-y: auto;
}
.content-item {
  padding: 1rem 0;
  border-bottom: 1px solid #f3f4f6;
}
.content-item:last-child {
  border-bottom: none;
}
.content-title {
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.5rem;
}
.content-stats {
  display: flex;
  gap: 1rem;
  font-size: 0.875rem;
  color: #6b7280;
}
.traffic-sources {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.traffic-sources h3 {
  margin: 0 0 1rem 0;
  font-size: 1.125rem;
  font-weight: 600;
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
.source-name {
  min-width: 120px;
  font-weight: 500;
  color: #374151;
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
.source-percentage {
  min-width: 50px;
  text-align: right;
  font-weight: 500;
  color: #6b7280;
}
.recent-activity {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.recent-activity h3 {
  margin: 0 0 1rem 0;
  font-size: 1.125rem;
  font-weight: 600;
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
</style>
