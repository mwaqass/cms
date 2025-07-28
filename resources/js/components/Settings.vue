<template>
  <div class="section">
    <h1>Settings</h1>
    <div class="actions">
      <button @click="openCreateModal">Add Setting</button>
    </div>

    <!-- Settings Groups -->
    <div v-for="(settings, group) in groupedSettings" :key="group" class="settings-group">
      <h2>{{ formatGroupName(group) }}</h2>
      <div class="settings-grid">
        <div v-for="setting in settings" :key="setting.id" class="setting-card">
          <div class="setting-header">
            <h3>{{ setting.label }}</h3>
            <div class="setting-actions">
              <button @click="openEditModal(setting)">Edit</button>
              <button @click="confirmDelete(setting)" class="delete-btn">Delete</button>
            </div>
          </div>
          <div class="setting-content">
            <div class="setting-value">
              <strong>Value:</strong>
              <span v-if="setting.type === 'boolean'">
                {{ setting.typed_value ? 'Yes' : 'No' }}
              </span>
              <span v-else>{{ setting.value || 'Not set' }}</span>
            </div>
            <div class="setting-meta">
              <span class="setting-type">{{ setting.type }}</span>
              <span v-if="setting.is_public" class="setting-public">Public</span>
            </div>
            <div v-if="setting.description" class="setting-description">
              {{ setting.description }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal modern-modal">
        <button class="close-btn" @click="closeModal">&times;</button>
        <h2>{{ editingSetting ? 'Edit Setting' : 'Add Setting' }}</h2>
        <form @submit.prevent="saveSetting">
          <div class="form-row">
            <div class="form-group">
              <label>Key</label>
              <input v-model="form.key" required :disabled="editingSetting" />
            </div>
            <div class="form-group">
              <label>Type</label>
              <select v-model="form.type" required>
                <option value="string">String</option>
                <option value="number">Number</option>
                <option value="boolean">Boolean</option>
                <option value="json">JSON</option>
                <option value="file">File</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Label</label>
              <input v-model="form.label" required />
            </div>
            <div class="form-group">
              <label>Group</label>
              <input v-model="form.group" required />
            </div>
          </div>
          <div class="form-group">
            <label>Value</label>
            <textarea v-if="form.type === 'json'" v-model="form.value" rows="4" placeholder='{"key": "value"}'></textarea>
            <input v-else-if="form.type === 'boolean'" type="checkbox" v-model="form.booleanValue" />
            <input v-else v-model="form.value" />
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea v-model="form.description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="form.is_public" />
              Public (accessible without authentication)
            </label>
          </div>
          <div class="modal-actions">
            <button class="primary" type="submit">Save</button>
            <button class="secondary" type="button" @click="closeModal">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal">
        <h2>Delete Setting</h2>
        <p>Are you sure you want to delete "{{ deletingSetting?.label }}"?</p>
        <div class="modal-actions">
          <button @click="deleteSetting" class="delete-btn">Yes, Delete</button>
          <button @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const settings = ref([]);
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingSetting = ref(null);
const deletingSetting = ref(null);
const form = ref({
  key: '',
  value: '',
  type: 'string',
  group: 'general',
  label: '',
  description: '',
  is_public: false,
  booleanValue: false,
});

const groupedSettings = computed(() => {
  const grouped = {};
  settings.value.forEach(setting => {
    if (!grouped[setting.group]) {
      grouped[setting.group] = [];
    }
    grouped[setting.group].push(setting);
  });
  return grouped;
});

function fetchSettings() {
  axios.get('/api/settings')
    .then(res => {
      settings.value = res.data;
    });
}

function openCreateModal() {
  editingSetting.value = null;
  form.value = {
    key: '',
    value: '',
    type: 'string',
    group: 'general',
    label: '',
    description: '',
    is_public: false,
    booleanValue: false,
  };
  showModal.value = true;
}

function openEditModal(setting) {
  editingSetting.value = setting;
  form.value = {
    key: setting.key,
    value: setting.type === 'boolean' ? '' : setting.value,
    type: setting.type,
    group: setting.group,
    label: setting.label,
    description: setting.description || '',
    is_public: setting.is_public,
    booleanValue: setting.type === 'boolean' ? setting.typed_value : false,
  };
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

function saveSetting() {
  const data = {
    key: form.value.key,
    value: form.value.type === 'boolean' ? form.value.booleanValue : form.value.value,
    type: form.value.type,
    group: form.value.group,
    label: form.value.label,
    description: form.value.description,
    is_public: form.value.is_public,
  };

  if (editingSetting.value) {
    axios.put(`/api/settings/${editingSetting.value.id}`, data)
      .then(() => {
        fetchSettings();
        closeModal();
      });
  } else {
    axios.post('/api/settings', data)
      .then(() => {
        fetchSettings();
        closeModal();
      });
  }
}

function confirmDelete(setting) {
  deletingSetting.value = setting;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
}

function deleteSetting() {
  axios.delete(`/api/settings/${deletingSetting.value.id}`)
    .then(() => {
      fetchSettings();
      closeDeleteModal();
    });
}

function formatGroupName(group) {
  return group.charAt(0).toUpperCase() + group.slice(1);
}

onMounted(() => {
  fetchSettings();
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
.actions {
  margin-bottom: 2rem;
}
.settings-group {
  margin-bottom: 3rem;
}
.settings-group h2 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
  padding-bottom: 0.5rem;
}
.settings-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  gap: 1.5rem;
}
.setting-card {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 1.5rem;
  background: #fff;
  transition: all 0.2s;
}
.setting-card:hover {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
.setting-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}
.setting-header h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #111827;
}
.setting-actions {
  display: flex;
  gap: 0.5rem;
}
.setting-actions button {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}
.setting-content {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.setting-value {
  font-size: 0.9rem;
}
.setting-meta {
  display: flex;
  gap: 1rem;
  font-size: 0.8rem;
}
.setting-type {
  background: #dbeafe;
  color: #1e40af;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-weight: 500;
}
.setting-public {
  background: #dcfce7;
  color: #166534;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-weight: 500;
}
.setting-description {
  font-size: 0.875rem;
  color: #6b7280;
  font-style: italic;
}
button {
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 0.5rem 1rem;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
}
button:hover {
  background: #1e40af;
}
.delete-btn {
  background: #dc2626;
}
.delete-btn:hover {
  background: #b91c1c;
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal {
  background: #fff;
  padding: 2rem;
  border-radius: 8px;
  max-width: 600px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
}
.modern-modal {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  position: relative;
}
.close-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6b7280;
}
.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}
.form-group {
  margin-bottom: 1rem;
}
.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}
.form-group input, .form-group textarea, .form-group select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  font-size: 1rem;
}
.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}
.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 2rem;
}
.modal-actions button {
  flex: 1;
  padding: 0.75rem;
}
.primary {
  background: #2563eb;
}
.secondary {
  background: #6b7280;
}
</style>
