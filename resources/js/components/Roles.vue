<template>
  <div class="section">
    <h1>Roles</h1>
    <div class="actions">
      <input v-model="search" @input="fetchRoles(1)" placeholder="Search roles..." />
      <button @click="openCreateModal">Add Role</button>
    </div>
    <table class="role-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="role in roles" :key="role.id">
          <td>{{ role.name }}</td>
          <td>{{ role.description || '-' }}</td>
          <td>
            <span :class="role.is_active ? 'status-active' : 'status-inactive'">
              {{ role.is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <button @click="openEditModal(role)">Edit</button>
            <button @click="confirmDelete(role)" class="delete-btn">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="pagination">
      <button :disabled="page === 1" @click="fetchRoles(page - 1)">&lt; Prev</button>
      <span>Page {{ page }} of {{ totalPages }}</span>
      <button :disabled="page === totalPages" @click="fetchRoles(page + 1)">Next &gt;</button>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal modern-modal">
        <button class="close-btn" @click="closeModal">&times;</button>
        <h2>{{ editingRole ? 'Edit Role' : 'Add Role' }}</h2>
        <form @submit.prevent="saveRole">
          <div class="form-group">
            <label>Name</label>
            <input v-model="form.name" required />
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea v-model="form.description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Permissions</label>
            <div class="permissions-header">
              <span class="permissions-count">{{ form.permissions.length }} permissions selected</span>
              <button type="button" @click="selectAllPermissions" class="select-all-btn">Select All</button>
            </div>
            <div class="permissions-grid">
              <label
                v-for="permission in availablePermissions"
                :key="permission"
                class="permission-item"
                :class="{ 'permission-checked': form.permissions.includes(permission) }"
              >
                <input
                  type="checkbox"
                  :value="permission"
                  v-model="form.permissions"
                />
                <span class="permission-text">{{ permission }}</span>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="form.is_active" />
              Active
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
        <h2>Delete Role</h2>
        <p>Are you sure you want to delete {{ deletingRole?.name }}?</p>
        <div class="modal-actions">
          <button @click="deleteRole" class="delete-btn">Yes, Delete</button>
          <button @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const roles = ref([]);
const page = ref(1);
const totalPages = ref(1);
const search = ref('');
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingRole = ref(null);
const deletingRole = ref(null);
const form = ref({ name: '', description: '', permissions: [], is_active: true });

const availablePermissions = [
  'users.create', 'users.read', 'users.update', 'users.delete',
  'roles.create', 'roles.read', 'roles.update', 'roles.delete',
  'pages.create', 'pages.read', 'pages.update', 'pages.delete',
  'posts.create', 'posts.read', 'posts.update', 'posts.delete',
  'categories.create', 'categories.read', 'categories.update', 'categories.delete',
  'media.upload', 'media.read', 'media.delete',
  'settings.read', 'settings.update',
];

function fetchRoles(p = 1) {
  page.value = p;
  let params = { page: p };
  if (search.value) params.search = search.value;
  axios.get('/api/roles', { params })
    .then(res => {
      roles.value = res.data.data || res.data;
      totalPages.value = res.data.last_page || 1;
    });
}

function openCreateModal() {
  editingRole.value = null;
  form.value = { name: '', description: '', permissions: [], is_active: true };
  showModal.value = true;
}

function openEditModal(role) {
  editingRole.value = role;
  form.value = {
    name: role.name,
    description: role.description || '',
    permissions: role.permissions || [],
    is_active: role.is_active
  };
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

function saveRole() {
  if (editingRole.value) {
    axios.put(`/api/roles/${editingRole.value.id}`, form.value)
      .then(() => {
        fetchRoles(page.value);
        closeModal();
      });
  } else {
    axios.post('/api/roles', form.value)
      .then(() => {
        fetchRoles(1);
        closeModal();
      });
  }
}

function confirmDelete(role) {
  deletingRole.value = role;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
}

function deleteRole() {
  axios.delete(`/api/roles/${deletingRole.value.id}`)
    .then(() => {
      fetchRoles(page.value);
      closeDeleteModal();
    });
}

function selectAllPermissions() {
  if (form.value.permissions.length === availablePermissions.length) {
    form.value.permissions = [];
  } else {
    form.value.permissions = [...availablePermissions];
  }
}

onMounted(() => {
  fetchRoles();
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
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}
.role-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1rem;
}
.role-table th, .role-table td {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}
.role-table th {
  background: #f1f5f9;
}
.status-active {
  color: #059669;
  font-weight: 500;
}
.status-inactive {
  color: #dc2626;
  font-weight: 500;
}
.pagination {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 2rem;
}
button {
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 0.4rem 1rem;
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
  max-width: 500px;
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
.form-group {
  margin-bottom: 1rem;
}
.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}
.form-group input, .form-group textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  font-size: 1rem;
}
.permissions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 0.75rem;
  max-height: 300px;
  overflow-y: auto;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  padding: 1.5rem;
  background: #f9fafb;
}
.permission-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.9rem;
}
.permission-item:hover {
  background: #f3f4f6;
  border-color: #d1d5db;
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}
.permission-item input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: #2563eb;
  cursor: pointer;
}
.permission-text {
  font-weight: 500;
  color: #374151;
  flex: 1;
}
.permission-checked {
  background: #eff6ff !important;
  border-color: #2563eb !important;
}
.permission-checked .permission-text {
  color: #1e40af !important;
  font-weight: 600 !important;
}
.permissions-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  padding: 0.75rem 1rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
}
.permissions-count {
  font-size: 0.875rem;
  color: #64748b;
  font-weight: 500;
}
.select-all-btn {
  background: #2563eb;
  color: white;
  border: none;
  padding: 0.375rem 0.75rem;
  border-radius: 4px;
  font-size: 0.875rem;
  cursor: pointer;
  transition: background 0.2s;
}
.select-all-btn:hover {
  background: #1e40af;
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
