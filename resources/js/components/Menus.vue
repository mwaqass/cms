<template>
  <div class="section">
    <h1>Menus</h1>
    <div class="actions">
      <input v-model="search" @input="fetchMenus(1)" placeholder="Search menus..." />
      <button @click="openCreateModal">Add Menu</button>
    </div>
    <table class="menu-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Slug</th>
          <th>Items</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="menu in menus" :key="menu.id">
          <td>{{ menu.name }}</td>
          <td>{{ menu.slug }}</td>
          <td>{{ menu.items_count || 0 }} items</td>
          <td>
            <span :class="menu.is_active ? 'status-active' : 'status-inactive'">
              {{ menu.is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>{{ formatDate(menu.created_at) }}</td>
          <td>
            <button @click="openEditModal(menu)">Edit</button>
            <button @click="openItemsModal(menu)">Manage Items</button>
            <button @click="confirmDelete(menu)" class="delete-btn">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="pagination">
      <button :disabled="page === 1" @click="fetchMenus(page - 1)">&lt; Prev</button>
      <span>Page {{ page }} of {{ totalPages }}</span>
      <button :disabled="page === totalPages" @click="fetchMenus(page + 1)">Next &gt;</button>
    </div>

    <!-- Create/Edit Menu Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal modern-modal">
        <button class="close-btn" @click="closeModal">&times;</button>
        <h2>{{ editingMenu ? 'Edit Menu' : 'Add Menu' }}</h2>
        <form @submit.prevent="saveMenu">
          <div class="form-row">
            <div class="form-group">
              <label>Name</label>
              <input v-model="form.name" required />
            </div>
            <div class="form-group">
              <label>Slug</label>
              <input v-model="form.slug" />
            </div>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea v-model="form.description" rows="3"></textarea>
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

    <!-- Menu Items Modal -->
    <div v-if="showItemsModal" class="modal-overlay">
      <div class="modal modern-modal large-modal">
        <button class="close-btn" @click="closeItemsModal">&times;</button>
        <h2>Manage Menu Items: {{ currentMenu?.name }}</h2>

        <div class="menu-items-container">
          <div class="menu-items-header">
            <button type="button" @click="openAddItemModal" class="add-item-btn">Add Item</button>
          </div>

          <div class="menu-items-list">
            <div
              v-for="item in menuItems"
              :key="item.id"
              class="menu-item"
              :style="{ marginLeft: (item.level * 20) + 'px' }"
            >
              <div class="menu-item-content">
                <span class="menu-item-title">{{ item.title }}</span>
                <span class="menu-item-url">{{ item.url }}</span>
                <div class="menu-item-actions">
                  <button @click="editMenuItem(item)" class="small-btn">Edit</button>
                  <button @click="deleteMenuItem(item)" class="small-btn delete-btn">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add/Edit Menu Item Modal -->
    <div v-if="showItemModal" class="modal-overlay">
      <div class="modal modern-modal">
        <button class="close-btn" @click="closeItemModal">&times;</button>
        <h2>{{ editingMenuItem ? 'Edit Menu Item' : 'Add Menu Item' }}</h2>
        <form @submit.prevent="saveMenuItem">
          <div class="form-group">
            <label>Title</label>
            <input v-model="itemForm.title" required />
          </div>
          <div class="form-group">
            <label>URL</label>
            <input v-model="itemForm.url" required />
          </div>
          <div class="form-group">
            <label>Parent Item</label>
            <select v-model="itemForm.parent_id">
              <option value="">No Parent</option>
              <option v-for="item in availableParentItems" :key="item.id" :value="item.id">
                {{ item.title }}
              </option>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Order</label>
              <input v-model="itemForm.order" type="number" />
            </div>
            <div class="form-group">
              <label>Target</label>
              <select v-model="itemForm.target">
                <option value="_self">Same Window</option>
                <option value="_blank">New Window</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="itemForm.is_active" />
              Active
            </label>
          </div>
          <div class="modal-actions">
            <button class="primary" type="submit">Save</button>
            <button class="secondary" type="button" @click="closeItemModal">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal">
        <h2>Delete Menu</h2>
        <p>Are you sure you want to delete "{{ deletingMenu?.name }}"?</p>
        <div class="modal-actions">
          <button @click="deleteMenu" class="delete-btn">Yes, Delete</button>
          <button @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const menus = ref([]);
const menuItems = ref([]);
const page = ref(1);
const totalPages = ref(1);
const search = ref('');
const showModal = ref(false);
const showItemsModal = ref(false);
const showItemModal = ref(false);
const showDeleteModal = ref(false);
const editingMenu = ref(null);
const editingMenuItem = ref(null);
const deletingMenu = ref(null);
const currentMenu = ref(null);

const form = ref({
  name: '',
  slug: '',
  description: '',
  is_active: true,
});

const itemForm = ref({
  title: '',
  url: '',
  parent_id: '',
  order: 0,
  target: '_self',
  is_active: true,
});

const availableParentItems = computed(() => {
  if (!editingMenuItem.value) return menuItems.value;
  return menuItems.value.filter(item => item.id !== editingMenuItem.value.id);
});

function fetchMenus(p = 1) {
  page.value = p;
  let params = { page: p };
  if (search.value) params.search = search.value;
  axios.get('/api/menus', { params })
    .then(res => {
      menus.value = res.data.data || res.data;
      totalPages.value = res.data.last_page || 1;
    });
}

function fetchMenuItems(menuId) {
  axios.get(`/api/menus/${menuId}/items`)
    .then(res => {
      menuItems.value = res.data;
    });
}

function openCreateModal() {
  editingMenu.value = null;
  form.value = {
    name: '',
    slug: '',
    description: '',
    is_active: true,
  };
  showModal.value = true;
}

function openEditModal(menu) {
  editingMenu.value = menu;
  form.value = {
    name: menu.name,
    slug: menu.slug,
    description: menu.description || '',
    is_active: menu.is_active,
  };
  showModal.value = true;
}

function openItemsModal(menu) {
  currentMenu.value = menu;
  fetchMenuItems(menu.id);
  showItemsModal.value = true;
}

function openAddItemModal() {
  editingMenuItem.value = null;
  itemForm.value = {
    title: '',
    url: '',
    parent_id: '',
    order: 0,
    target: '_self',
    is_active: true,
  };
  showItemModal.value = true;
}

function editMenuItem(item) {
  editingMenuItem.value = item;
  itemForm.value = {
    title: item.title,
    url: item.url,
    parent_id: item.parent_id || '',
    order: item.order || 0,
    target: item.target || '_self',
    is_active: item.is_active,
  };
  showItemModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

function closeItemsModal() {
  showItemsModal.value = false;
  currentMenu.value = null;
}

function closeItemModal() {
  showItemModal.value = false;
  editingMenuItem.value = null;
}

function saveMenu() {
  if (editingMenu.value) {
    axios.put(`/api/menus/${editingMenu.value.id}`, form.value)
      .then(() => {
        fetchMenus(page.value);
        closeModal();
      });
  } else {
    axios.post('/api/menus', form.value)
      .then(() => {
        fetchMenus(1);
        closeModal();
      });
  }
}

function saveMenuItem() {
  if (editingMenuItem.value) {
    axios.put(`/api/menus/${currentMenu.value.id}/items/${editingMenuItem.value.id}`, itemForm.value)
      .then(() => {
        fetchMenuItems(currentMenu.value.id);
        closeItemModal();
      });
  } else {
    axios.post(`/api/menus/${currentMenu.value.id}/items`, itemForm.value)
      .then(() => {
        fetchMenuItems(currentMenu.value.id);
        closeItemModal();
      });
  }
}

function deleteMenuItem(item) {
  if (confirm(`Are you sure you want to delete "${item.title}"?`)) {
    axios.delete(`/api/menus/${currentMenu.value.id}/items/${item.id}`)
      .then(() => {
        fetchMenuItems(currentMenu.value.id);
      });
  }
}

function confirmDelete(menu) {
  deletingMenu.value = menu;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
}

function deleteMenu() {
  axios.delete(`/api/menus/${deletingMenu.value.id}`)
    .then(() => {
      fetchMenus(page.value);
      closeDeleteModal();
    });
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

onMounted(() => {
  fetchMenus();
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
.menu-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1rem;
}
.menu-table th, .menu-table td {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}
.menu-table th {
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
.small-btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
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
.large-modal {
  max-width: 800px;
  width: 95%;
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
.menu-items-container {
  margin-top: 1rem;
}
.menu-items-header {
  margin-bottom: 1rem;
}
.add-item-btn {
  background: #059669;
}
.add-item-btn:hover {
  background: #047857;
}
.menu-items-list {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}
.menu-item {
  border-bottom: 1px solid #e5e7eb;
  padding: 1rem;
  background: #fff;
}
.menu-item:last-child {
  border-bottom: none;
}
.menu-item-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.menu-item-title {
  font-weight: 500;
  color: #374151;
}
.menu-item-url {
  color: #6b7280;
  font-size: 0.875rem;
  margin-left: 1rem;
}
.menu-item-actions {
  display: flex;
  gap: 0.5rem;
}
</style>
