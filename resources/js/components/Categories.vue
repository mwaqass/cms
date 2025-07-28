<template>
  <div class="section">
    <h1>Categories</h1>
    <div class="actions">
      <input v-model="search" @input="fetchCategories(1)" placeholder="Search categories..." />
      <button @click="openCreateModal">Add Category</button>
    </div>
    <table class="category-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Slug</th>
          <th>Parent</th>
          <th>Posts</th>
          <th>Status</th>
          <th>Order</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="category in categories" :key="category.id">
          <td>
            <span :style="{ paddingLeft: getCategoryLevel(category) * 20 + 'px' }">
              {{ category.name }}
            </span>
          </td>
          <td>{{ category.slug }}</td>
          <td>{{ category.parent?.name || '-' }}</td>
          <td>{{ category.posts_count || 0 }}</td>
          <td>
            <span :class="category.is_active ? 'status-active' : 'status-inactive'">
              {{ category.is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>{{ category.order }}</td>
          <td>
            <button @click="openEditModal(category)">Edit</button>
            <button @click="confirmDelete(category)" class="delete-btn">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="pagination">
      <button :disabled="page === 1" @click="fetchCategories(page - 1)">&lt; Prev</button>
      <span>Page {{ page }} of {{ totalPages }}</span>
      <button :disabled="page === totalPages" @click="fetchCategories(page + 1)">Next &gt;</button>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal modern-modal">
        <button class="close-btn" @click="closeModal">&times;</button>
        <h2>{{ editingCategory ? 'Edit Category' : 'Add Category' }}</h2>
        <form @submit.prevent="saveCategory">
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
          <div class="form-row">
            <div class="form-group">
              <label>Parent Category</label>
              <select v-model="form.parent_id">
                <option value="">No Parent</option>
                <option v-for="cat in availableParents" :key="cat.id" :value="cat.id">
                  {{ cat.name }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label>Order</label>
              <input v-model="form.order" type="number" />
            </div>
          </div>
          <div class="form-group">
            <label>Featured Image URL</label>
            <input v-model="form.featured_image" />
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="form.is_active" />
              Active
            </label>
          </div>
          <div class="form-group">
            <label>Meta Title</label>
            <input v-model="form.meta_title" />
          </div>
          <div class="form-group">
            <label>Meta Description</label>
            <textarea v-model="form.meta_description" rows="2"></textarea>
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
        <h2>Delete Category</h2>
        <p>Are you sure you want to delete "{{ deletingCategory?.name }}"?</p>
        <div v-if="deletingCategory?.posts_count > 0" class="warning">
          <strong>Warning:</strong> This category has {{ deletingCategory.posts_count }} posts. You cannot delete it until you move or delete the posts.
        </div>
        <div v-if="deletingCategory?.children_count > 0" class="warning">
          <strong>Warning:</strong> This category has {{ deletingCategory.children_count }} subcategories. You cannot delete it until you delete the subcategories.
        </div>
        <div class="modal-actions">
          <button @click="deleteCategory" class="delete-btn" :disabled="cannotDelete">Yes, Delete</button>
          <button @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const categories = ref([]);
const page = ref(1);
const totalPages = ref(1);
const search = ref('');
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingCategory = ref(null);
const deletingCategory = ref(null);
const form = ref({
  name: '',
  slug: '',
  description: '',
  featured_image: '',
  parent_id: '',
  order: 0,
  is_active: true,
  meta_title: '',
  meta_description: '',
});

const availableParents = computed(() => {
  if (!editingCategory.value) return categories.value;
  return categories.value.filter(cat => cat.id !== editingCategory.value.id);
});

const cannotDelete = computed(() => {
  if (!deletingCategory.value) return false;
  return (deletingCategory.value.posts_count > 0) || (deletingCategory.value.children_count > 0);
});

function fetchCategories(p = 1) {
  page.value = p;
  let params = { page: p };
  if (search.value) params.search = search.value;
  axios.get('/api/categories', { params })
    .then(res => {
      categories.value = res.data.data || res.data;
      totalPages.value = res.data.last_page || 1;
    });
}

function getCategoryLevel(category) {
  let level = 0;
  let current = category;
  while (current.parent) {
    level++;
    current = current.parent;
  }
  return level;
}

function openCreateModal() {
  editingCategory.value = null;
  form.value = {
    name: '',
    slug: '',
    description: '',
    featured_image: '',
    parent_id: '',
    order: 0,
    is_active: true,
    meta_title: '',
    meta_description: '',
  };
  showModal.value = true;
}

function openEditModal(category) {
  editingCategory.value = category;
  form.value = {
    name: category.name,
    slug: category.slug,
    description: category.description || '',
    featured_image: category.featured_image || '',
    parent_id: category.parent_id || '',
    order: category.order || 0,
    is_active: category.is_active,
    meta_title: category.meta_title || '',
    meta_description: category.meta_description || '',
  };
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

function saveCategory() {
  if (editingCategory.value) {
    axios.put(`/api/categories/${editingCategory.value.id}`, form.value)
      .then(() => {
        fetchCategories(page.value);
        closeModal();
      });
  } else {
    axios.post('/api/categories', form.value)
      .then(() => {
        fetchCategories(1);
        closeModal();
      });
  }
}

function confirmDelete(category) {
  deletingCategory.value = category;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
}

function deleteCategory() {
  if (cannotDelete.value) return;

  axios.delete(`/api/categories/${deletingCategory.value.id}`)
    .then(() => {
      fetchCategories(page.value);
      closeDeleteModal();
    })
    .catch(error => {
      if (error.response?.data?.message) {
        alert(error.response.data.message);
      }
    });
}

onMounted(() => {
  fetchCategories();
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
.category-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1rem;
}
.category-table th, .category-table td {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}
.category-table th {
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
button:disabled {
  background: #9ca3af;
  cursor: not-allowed;
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
.warning {
  background: #fef3c7;
  border: 1px solid #f59e0b;
  color: #92400e;
  padding: 1rem;
  border-radius: 4px;
  margin: 1rem 0;
}
</style>
