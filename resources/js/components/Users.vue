<template>
  <div class="section">
    <h1>Users</h1>
    <div class="actions">
      <input v-model="search" @input="fetchUsers(1)" placeholder="Search users..." />
      <button @click="openCreateModal">Add User</button>
    </div>
    <table class="user-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>
            <button @click="openEditModal(user)">Edit</button>
            <button @click="confirmDelete(user)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="pagination">
      <button :disabled="page === 1" @click="fetchUsers(page - 1)">&lt; Prev</button>
      <span>Page {{ page }} of {{ totalPages }}</span>
      <button :disabled="page === totalPages" @click="fetchUsers(page + 1)">Next &gt;</button>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal modern-modal">
        <button class="close-btn" @click="closeModal">&times;</button>
        <h2>{{ editingUser ? 'Edit User' : 'Add User' }}</h2>
        <form @submit.prevent="saveUser">
          <div class="form-group">
            <label>Name</label>
            <input v-model="form.name" required />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input v-model="form.email" type="email" required />
          </div>
          <div class="form-group" v-if="!editingUser">
            <label>Password</label>
            <input v-model="form.password" type="password" required />
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
        <h2>Delete User</h2>
        <p>Are you sure you want to delete {{ deletingUser?.name }}?</p>
        <div class="modal-actions">
          <button @click="deleteUser">Yes, Delete</button>
          <button @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const users = ref([]);
const page = ref(1);
const totalPages = ref(1);
const search = ref('');
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingUser = ref(null);
const deletingUser = ref(null);
const form = ref({ name: '', email: '', password: '' });

function fetchUsers(p = 1) {
  page.value = p;
  let params = { page: p };
  if (search.value) params.search = search.value;
  axios.get('/api/users', { params })
    .then(res => {
      users.value = res.data.data || res.data;
      totalPages.value = res.data.last_page || 1;
    });
}

function openCreateModal() {
  editingUser.value = null;
  form.value = { name: '', email: '', password: '' };
  showModal.value = true;
}

function openEditModal(user) {
  editingUser.value = user;
  form.value = { name: user.name, email: user.email, password: '' };
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

function saveUser() {
  if (editingUser.value) {
    axios.put(`/api/users/${editingUser.value.id}`, form.value)
      .then(() => {
        fetchUsers(page.value);
        closeModal();
      });
  } else {
    axios.post('/api/users', form.value)
      .then(() => {
        fetchUsers(1);
        closeModal();
      });
  }
}

function confirmDelete(user) {
  deletingUser.value = user;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
}

function deleteUser() {
  axios.delete(`/api/users/${deletingUser.value.id}`)
    .then(() => {
      fetchUsers(page.value);
      closeDeleteModal();
    });
}



onMounted(() => {
  fetchUsers();
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
.user-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1rem;
}
.user-table th, .user-table td {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}
.user-table th {
  background: #f1f5f9;
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
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal.modern-modal {
  background: #fff;
  padding: 2.5rem 2rem 2rem 2rem;
  border-radius: 16px;
  min-width: 350px;
  box-shadow: 0 8px 32px rgba(30,41,59,0.18);
  position: relative;
  animation: modalIn 0.2s cubic-bezier(.4,2,.6,1) both;
}
@keyframes modalIn {
  from { transform: translateY(40px) scale(0.98); opacity: 0; }
  to { transform: none; opacity: 1; }
}
.close-btn {
  position: absolute;
  top: 1.1rem;
  right: 1.1rem;
  background: none;
  border: none;
  font-size: 1.7rem;
  color: #64748b;
  cursor: pointer;
  transition: color 0.2s;
}
.close-btn:hover {
  color: #2563eb;
}
.modern-modal h2 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  text-align: left;
}
.form-group {
  margin-bottom: 1.2rem;
  text-align: left;
}
.form-group label {
  display: block;
  font-weight: 500;
  margin-bottom: 0.4rem;
  color: #334155;
}
.form-group input {
  width: 100%;
  padding: 0.6rem 0.8rem;
  border: 1.5px solid #cbd5e1;
  border-radius: 6px;
  font-size: 1rem;
  transition: border 0.2s;
  outline: none;
}
.form-group input:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 2px #2563eb22;
}
.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
  justify-content: flex-end;
}
button.primary {
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 0.6rem 1.5rem;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}
button.primary:hover {
  background: #1e40af;
}
button.secondary {
  background: #f1f5f9;
  color: #334155;
  border: none;
  border-radius: 6px;
  padding: 0.6rem 1.5rem;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}
button.secondary:hover {
  background: #e2e8f0;
}
</style>
