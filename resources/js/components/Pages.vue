<template>
  <div class="section">
    <h1>Pages</h1>
    <div class="actions">
      <input v-model="search" @input="fetchPages(1)" placeholder="Search pages..." />
      <button @click="openCreateModal">Add Page</button>
    </div>
    <table class="page-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Slug</th>
          <th>Status</th>
          <th>Featured</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="page in pages" :key="page.id">
          <td>{{ page.title }}</td>
          <td>{{ page.slug }}</td>
          <td>
            <span :class="`status-${page.status}`">
              {{ page.status }}
            </span>
          </td>
          <td>
            <span :class="page.is_featured ? 'status-active' : 'status-inactive'">
              {{ page.is_featured ? 'Yes' : 'No' }}
            </span>
          </td>
          <td>{{ formatDate(page.created_at) }}</td>
          <td>
            <button @click="openEditModal(page)">Edit</button>
            <button @click="confirmDelete(page)" class="delete-btn">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="pagination">
      <button :disabled="page === 1" @click="fetchPages(page - 1)">&lt; Prev</button>
      <span>Page {{ page }} of {{ totalPages }}</span>
      <button :disabled="page === totalPages" @click="fetchPages(page + 1)">Next &gt;</button>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal modern-modal large-modal">
        <button class="close-btn" @click="closeModal">&times;</button>
        <h2>{{ editingPage ? 'Edit Page' : 'Add Page' }}</h2>
        <form @submit.prevent="savePage">
          <div class="form-row">
            <div class="form-group">
              <label>Title</label>
              <input v-model="form.title" required />
            </div>
            <div class="form-group">
              <label>Slug</label>
              <input v-model="form.slug" />
            </div>
          </div>
          <div class="form-group">
            <label>Content</label>
            <textarea v-model="form.content" rows="10" required></textarea>
          </div>
          <div class="form-group">
            <label>Excerpt</label>
            <textarea v-model="form.excerpt" rows="3"></textarea>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Status</label>
              <select v-model="form.status">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="archived">Archived</option>
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
              <input type="checkbox" v-model="form.is_featured" />
              Featured Page
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
          <div class="form-group">
            <label>Meta Keywords</label>
            <input v-model="form.meta_keywords" />
          </div>
          <div class="form-group">
            <label>Published At</label>
            <input v-model="form.published_at" type="datetime-local" />
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
        <h2>Delete Page</h2>
        <p>Are you sure you want to delete "{{ deletingPage?.title }}"?</p>
        <div class="modal-actions">
          <button @click="deletePage" class="delete-btn">Yes, Delete</button>
          <button @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const pages = ref([]);
const page = ref(1);
const totalPages = ref(1);
const search = ref('');
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingPage = ref(null);
const deletingPage = ref(null);
const form = ref({
  title: '',
  slug: '',
  content: '',
  excerpt: '',
  featured_image: '',
  status: 'draft',
  meta_title: '',
  meta_description: '',
  meta_keywords: '',
  order: 0,
  is_featured: false,
  published_at: '',
});

function fetchPages(p = 1) {
  page.value = p;
  let params = { page: p };
  if (search.value) params.search = search.value;
  axios.get('/api/pages', { params })
    .then(res => {
      pages.value = res.data.data || res.data;
      totalPages.value = res.data.last_page || 1;
    });
}

function openCreateModal() {
  editingPage.value = null;
  form.value = {
    title: '',
    slug: '',
    content: '',
    excerpt: '',
    featured_image: '',
    status: 'draft',
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
    order: 0,
    is_featured: false,
    published_at: '',
  };
  showModal.value = true;
}

function openEditModal(page) {
  editingPage.value = page;
  form.value = {
    title: page.title,
    slug: page.slug,
    content: page.content,
    excerpt: page.excerpt || '',
    featured_image: page.featured_image || '',
    status: page.status,
    meta_title: page.meta_title || '',
    meta_description: page.meta_description || '',
    meta_keywords: page.meta_keywords || '',
    order: page.order || 0,
    is_featured: page.is_featured || false,
    published_at: page.published_at ? new Date(page.published_at).toISOString().slice(0, 16) : '',
  };
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

function savePage() {
  if (editingPage.value) {
    axios.put(`/api/pages/${editingPage.value.id}`, form.value)
      .then(() => {
        fetchPages(page.value);
        closeModal();
      });
  } else {
    axios.post('/api/pages', form.value)
      .then(() => {
        fetchPages(1);
        closeModal();
      });
  }
}

function confirmDelete(page) {
  deletingPage.value = page;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
}

function deletePage() {
  axios.delete(`/api/pages/${deletingPage.value.id}`)
    .then(() => {
      fetchPages(page.value);
      closeDeleteModal();
    });
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

onMounted(() => {
  fetchPages();
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
.page-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1rem;
}
.page-table th, .page-table td {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}
.page-table th {
  background: #f1f5f9;
}
.status-draft {
  color: #f59e0b;
  font-weight: 500;
}
.status-published {
  color: #059669;
  font-weight: 500;
}
.status-archived {
  color: #6b7280;
  font-weight: 500;
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
</style>
