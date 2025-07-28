<template>
  <div class="section">
    <h1>Reports</h1>

    <!-- Report Type Selector -->
    <div class="report-controls">
      <div class="report-type">
        <label>Report Type:</label>
        <select v-model="selectedReport" @change="generateReport">
          <option value="user-activity">User Activity Report</option>
          <option value="content-performance">Content Performance Report</option>
          <option value="traffic-analysis">Traffic Analysis Report</option>
          <option value="engagement-metrics">Engagement Metrics Report</option>
          <option value="system-usage">System Usage Report</option>
        </select>
      </div>

      <div class="date-filters">
        <div class="filter-group">
          <label>From:</label>
          <input type="date" v-model="dateFrom" @change="generateReport" />
        </div>
        <div class="filter-group">
          <label>To:</label>
          <input type="date" v-model="dateTo" @change="generateReport" />
        </div>
      </div>

      <div class="export-actions">
        <button @click="exportPDF" class="export-btn pdf">Export PDF</button>
        <button @click="exportCSV" class="export-btn csv">Export CSV</button>
      </div>
    </div>

    <!-- Report Content -->
    <div class="report-content">
      <!-- User Activity Report -->
      <div v-if="selectedReport === 'user-activity'" class="report-section">
        <h2>User Activity Report</h2>
        <div class="report-summary">
          <div class="summary-card">
            <h3>Total Active Users</h3>
            <div class="summary-value">{{ userActivity.totalActive }}</div>
          </div>
          <div class="summary-card">
            <h3>New Registrations</h3>
            <div class="summary-value">{{ userActivity.newRegistrations }}</div>
          </div>
          <div class="summary-card">
            <h3>Login Sessions</h3>
            <div class="summary-value">{{ userActivity.loginSessions }}</div>
          </div>
        </div>

        <div class="report-table">
          <h3>User Activity Details</h3>
          <table>
            <thead>
              <tr>
                <th>User</th>
                <th>Last Login</th>
                <th>Login Count</th>
                <th>Actions Performed</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in userActivity.details" :key="user.id">
                <td>{{ user.name }}</td>
                <td>{{ formatDate(user.last_login) }}</td>
                <td>{{ user.login_count }}</td>
                <td>{{ user.actions_count }}</td>
                <td>
                  <span :class="user.status === 'active' ? 'status-active' : 'status-inactive'">
                    {{ user.status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Content Performance Report -->
      <div v-if="selectedReport === 'content-performance'" class="report-section">
        <h2>Content Performance Report</h2>
        <div class="report-summary">
          <div class="summary-card">
            <h3>Total Views</h3>
            <div class="summary-value">{{ contentPerformance.totalViews }}</div>
          </div>
          <div class="summary-card">
            <h3>Average Views</h3>
            <div class="summary-value">{{ contentPerformance.avgViews }}</div>
          </div>
          <div class="summary-card">
            <h3>Top Performing</h3>
            <div class="summary-value">{{ contentPerformance.topPerforming }}</div>
          </div>
        </div>

        <div class="report-table">
          <h3>Content Performance Details</h3>
          <table>
            <thead>
              <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Views</th>
                <th>Comments</th>
                <th>Shares</th>
                <th>Performance Score</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="content in contentPerformance.details" :key="content.id">
                <td>{{ content.title }}</td>
                <td>{{ content.type }}</td>
                <td>{{ content.views }}</td>
                <td>{{ content.comments }}</td>
                <td>{{ content.shares }}</td>
                <td>
                  <div class="performance-bar">
                    <div class="bar-fill" :style="{ width: content.score + '%' }"></div>
                    <span>{{ content.score }}%</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Traffic Analysis Report -->
      <div v-if="selectedReport === 'traffic-analysis'" class="report-section">
        <h2>Traffic Analysis Report</h2>
        <div class="report-summary">
          <div class="summary-card">
            <h3>Total Visits</h3>
            <div class="summary-value">{{ trafficAnalysis.totalVisits }}</div>
          </div>
          <div class="summary-card">
            <h3>Unique Visitors</h3>
            <div class="summary-value">{{ trafficAnalysis.uniqueVisitors }}</div>
          </div>
          <div class="summary-card">
            <h3>Bounce Rate</h3>
            <div class="summary-value">{{ trafficAnalysis.bounceRate }}%</div>
          </div>
        </div>

        <div class="traffic-breakdown">
          <h3>Traffic Sources Breakdown</h3>
          <div class="breakdown-chart">
            <div v-for="source in trafficAnalysis.sources" :key="source.name" class="source-item">
              <div class="source-info">
                <span class="source-name">{{ source.name }}</span>
                <span class="source-percentage">{{ source.percentage }}%</span>
              </div>
              <div class="source-bar">
                <div class="bar-fill" :style="{ width: source.percentage + '%' }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Engagement Metrics Report -->
      <div v-if="selectedReport === 'engagement-metrics'" class="report-section">
        <h2>Engagement Metrics Report</h2>
        <div class="report-summary">
          <div class="summary-card">
            <h3>Avg. Session Duration</h3>
            <div class="summary-value">{{ engagementMetrics.avgSessionDuration }}</div>
          </div>
          <div class="summary-card">
            <h3>Pages per Session</h3>
            <div class="summary-value">{{ engagementMetrics.pagesPerSession }}</div>
          </div>
          <div class="summary-card">
            <h3>Return Rate</h3>
            <div class="summary-value">{{ engagementMetrics.returnRate }}%</div>
          </div>
        </div>

        <div class="engagement-chart">
          <h3>Engagement Trends</h3>
          <div class="chart-container">
            <div class="chart-bars">
              <div
                v-for="(metric, index) in engagementMetrics.trends"
                :key="index"
                class="chart-bar"
                :style="{ height: (metric.value / Math.max(...engagementMetrics.trends.map(m => m.value)) * 100) + '%' }"
              >
                <span class="bar-label">{{ metric.label }}</span>
                <span class="bar-value">{{ metric.value }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- System Usage Report -->
      <div v-if="selectedReport === 'system-usage'" class="report-section">
        <h2>System Usage Report</h2>
        <div class="report-summary">
          <div class="summary-card">
            <h3>Storage Used</h3>
            <div class="summary-value">{{ systemUsage.storageUsed }}</div>
          </div>
          <div class="summary-card">
            <h3>Database Size</h3>
            <div class="summary-value">{{ systemUsage.databaseSize }}</div>
          </div>
          <div class="summary-card">
            <h3>Uptime</h3>
            <div class="summary-value">{{ systemUsage.uptime }}%</div>
          </div>
        </div>

        <div class="system-metrics">
          <h3>System Metrics</h3>
          <div class="metrics-grid">
            <div class="metric-item">
              <h4>CPU Usage</h4>
              <div class="metric-bar">
                <div class="bar-fill" :style="{ width: systemUsage.cpuUsage + '%' }"></div>
                <span>{{ systemUsage.cpuUsage }}%</span>
              </div>
            </div>
            <div class="metric-item">
              <h4>Memory Usage</h4>
              <div class="metric-bar">
                <div class="bar-fill" :style="{ width: systemUsage.memoryUsage + '%' }"></div>
                <span>{{ systemUsage.memoryUsage }}%</span>
              </div>
            </div>
            <div class="metric-item">
              <h4>Disk Usage</h4>
              <div class="metric-bar">
                <div class="bar-fill" :style="{ width: systemUsage.diskUsage + '%' }"></div>
                <span>{{ systemUsage.diskUsage }}%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const selectedReport = ref('user-activity');
const dateFrom = ref('');
const dateTo = ref('');

// Set default date range (last 30 days)
const today = new Date();
const thirtyDaysAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
dateFrom.value = thirtyDaysAgo.toISOString().split('T')[0];
dateTo.value = today.toISOString().split('T')[0];

const userActivity = ref({
  totalActive: 0,
  newRegistrations: 0,
  loginSessions: 0,
  details: []
});

const contentPerformance = ref({
  totalViews: 0,
  avgViews: 0,
  topPerforming: 0,
  details: []
});

const trafficAnalysis = ref({
  totalVisits: 0,
  uniqueVisitors: 0,
  bounceRate: 0,
  sources: []
});

const engagementMetrics = ref({
  avgSessionDuration: '0:00',
  pagesPerSession: 0,
  returnRate: 0,
  trends: []
});

const systemUsage = ref({
  storageUsed: '0 MB',
  databaseSize: '0 MB',
  uptime: 0,
  cpuUsage: 0,
  memoryUsage: 0,
  diskUsage: 0
});

function generateReport() {
  const params = {
    report: selectedReport.value,
    from: dateFrom.value,
    to: dateTo.value
  };

  axios.get('/api/reports', { params })
    .then(res => {
      console.log('Report data:', res.data);
      // Update report data based on response
    })
    .catch(err => {
      console.log('Using mock report data');
      // Use mock data for demonstration
      loadMockData();
    });
}

function loadMockData() {
  if (selectedReport.value === 'user-activity') {
    userActivity.value = {
      totalActive: 1247,
      newRegistrations: 89,
      loginSessions: 3420,
      details: [
        { id: 1, name: 'John Doe', last_login: '2024-01-15 10:30:00', login_count: 45, actions_count: 156, status: 'active' },
        { id: 2, name: 'Jane Smith', last_login: '2024-01-14 15:20:00', login_count: 32, actions_count: 89, status: 'active' },
        { id: 3, name: 'Bob Johnson', last_login: '2024-01-13 09:15:00', login_count: 28, actions_count: 67, status: 'inactive' },
      ]
    };
  } else if (selectedReport.value === 'content-performance') {
    contentPerformance.value = {
      totalViews: 15420,
      avgViews: 385,
      topPerforming: 'Getting Started with Laravel',
      details: [
        { id: 1, title: 'Getting Started with Laravel', type: 'Post', views: 1250, comments: 45, shares: 23, score: 95 },
        { id: 2, title: 'Vue.js Best Practices', type: 'Post', views: 980, comments: 32, shares: 18, score: 87 },
        { id: 3, title: 'About Us', type: 'Page', views: 750, comments: 0, shares: 5, score: 72 },
      ]
    };
  } else if (selectedReport.value === 'traffic-analysis') {
    trafficAnalysis.value = {
      totalVisits: 25470,
      uniqueVisitors: 12450,
      bounceRate: 23.5,
      sources: [
        { name: 'Direct', percentage: 35 },
        { name: 'Google', percentage: 28 },
        { name: 'Social Media', percentage: 20 },
        { name: 'Referral', percentage: 12 },
        { name: 'Other', percentage: 5 },
      ]
    };
  } else if (selectedReport.value === 'engagement-metrics') {
    engagementMetrics.value = {
      avgSessionDuration: '4:32',
      pagesPerSession: 3.2,
      returnRate: 68.5,
      trends: [
        { label: 'Mon', value: 120 },
        { label: 'Tue', value: 150 },
        { label: 'Wed', value: 180 },
        { label: 'Thu', value: 200 },
        { label: 'Fri', value: 220 },
        { label: 'Sat', value: 250 },
        { label: 'Sun', value: 280 },
      ]
    };
  } else if (selectedReport.value === 'system-usage') {
    systemUsage.value = {
      storageUsed: '2.4 GB',
      databaseSize: '156 MB',
      uptime: 99.8,
      cpuUsage: 45,
      memoryUsage: 62,
      diskUsage: 78
    };
  }
}

function exportPDF() {
  alert('PDF export functionality would be implemented here');
}

function exportCSV() {
  alert('CSV export functionality would be implemented here');
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

onMounted(() => {
  generateReport();
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
.report-controls {
  display: flex;
  gap: 2rem;
  align-items: center;
  margin-bottom: 2rem;
  padding: 1.5rem;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.report-type, .date-filters {
  display: flex;
  align-items: center;
  gap: 1rem;
}
.report-type select, .date-filters input {
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  font-size: 1rem;
}
.export-actions {
  display: flex;
  gap: 1rem;
}
.export-btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
}
.export-btn.pdf {
  background: #dc2626;
  color: #fff;
}
.export-btn.csv {
  background: #059669;
  color: #fff;
}
.export-btn:hover {
  opacity: 0.9;
}
.report-content {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.report-section h2 {
  margin: 0 0 1.5rem 0;
  font-size: 1.5rem;
  font-weight: 600;
  color: #111827;
}
.report-summary {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}
.summary-card {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 1.5rem;
  text-align: center;
}
.summary-card h3 {
  margin: 0 0 0.5rem 0;
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
}
.summary-value {
  font-size: 1.875rem;
  font-weight: 700;
  color: #111827;
}
.report-table {
  margin-bottom: 2rem;
}
.report-table h3 {
  margin: 0 0 1rem 0;
  font-size: 1.125rem;
  font-weight: 600;
}
.report-table table {
  width: 100%;
  border-collapse: collapse;
}
.report-table th, .report-table td {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}
.report-table th {
  background: #f1f5f9;
  font-weight: 600;
}
.status-active {
  color: #059669;
  font-weight: 500;
}
.status-inactive {
  color: #dc2626;
  font-weight: 500;
}
.performance-bar {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.bar-fill {
  height: 8px;
  background: linear-gradient(to right, #2563eb, #3b82f6);
  border-radius: 4px;
  min-width: 20px;
}
.traffic-breakdown, .engagement-chart, .system-metrics {
  margin-bottom: 2rem;
}
.traffic-breakdown h3, .engagement-chart h3, .system-metrics h3 {
  margin: 0 0 1rem 0;
  font-size: 1.125rem;
  font-weight: 600;
}
.breakdown-chart {
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
.chart-container {
  height: 200px;
  display: flex;
  align-items: end;
  justify-content: space-between;
  gap: 0.5rem;
  padding: 1rem 0;
}
.chart-bars {
  display: flex;
  align-items: end;
  justify-content: space-between;
  width: 100%;
  height: 150px;
  gap: 0.5rem;
}
.chart-bar {
  background: linear-gradient(to top, #2563eb, #3b82f6);
  border-radius: 4px 4px 0 0;
  min-width: 30px;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.bar-label {
  position: absolute;
  bottom: -25px;
  font-size: 0.75rem;
  color: #6b7280;
}
.bar-value {
  position: absolute;
  top: -25px;
  font-size: 0.75rem;
  font-weight: 500;
  color: #374151;
}
.metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}
.metric-item {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 1.5rem;
}
.metric-item h4 {
  margin: 0 0 1rem 0;
  font-size: 1rem;
  font-weight: 600;
  color: #374151;
}
.metric-bar {
  display: flex;
  align-items: center;
  gap: 1rem;
}
.metric-bar .bar-fill {
  flex: 1;
  height: 12px;
  background: linear-gradient(to right, #2563eb, #3b82f6);
  border-radius: 6px;
}
.metric-bar span {
  min-width: 50px;
  text-align: right;
  font-weight: 500;
  color: #6b7280;
}
</style>
