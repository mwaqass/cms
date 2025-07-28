<template>
  <div class="section">
    <h1>Posts</h1>
    <div class="actions">
      <input v-model="search" @input="fetchPosts(1)" placeholder="Search posts..." />
      <button @click="openCreateModal">Add Post</button>
    </div>
    <table class="post-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Status</th>
          <th>Featured</th>
          <th>Views</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="post in posts" :key="post.id">
          <td>{{ post.title }}</td>
          <td>{{ post.category?.name || '-' }}</td>
          <td>
            <span :class="`status-${post.status}`">
              {{ post.status }}
            </span>
          </td>
          <td>
            <span :class="post.is_featured ? 'status-active' : 'status-inactive'">
              {{ post.is_featured ? 'Yes' : 'No' }}
            </span>
          </td>
          <td>{{ post.view_count }}</td>
          <td>{{ formatDate(post.created_at) }}</td>
          <td>
            <button @click="openEditModal(post)">Edit</button>
            <button @click="confirmDelete(post)" class="delete-btn">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="pagination">
      <button :disabled="page === 1" @click="fetchPosts(page - 1)">&lt; Prev</button>
      <span>Page {{ page }} of {{ totalPages }}</span>
      <button :disabled="page === totalPages" @click="fetchPosts(page + 1)">Next &gt;</button>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal modern-modal large-modal">
        <button class="close-btn" @click="closeModal">&times;</button>
        <h2>{{ editingPost ? 'Edit Post' : 'Add Post' }}</h2>
        <form @submit.prevent="savePost">
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
          <div class="form-row">
            <div class="form-group">
              <label>Category</label>
              <select v-model="form.category_id">
                <option value="">No Category</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select v-model="form.status">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="archived">Archived</option>
              </select>
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
              <label>Tags (comma separated)</label>
              <input v-model="tagsInput" @input="updateTags" placeholder="tag1, tag2, tag3" />
            </div>
            <div class="form-group">
              <label>Featured Image URL</label>
              <input v-model="form.featured_image" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="checkbox-label">
                <input type="checkbox" v-model="form.is_featured" />
                Featured Post
              </label>
            </div>
            <div class="form-group">
              <label class="checkbox-label">
                <input type="checkbox" v-model="form.allow_comments" />
                Allow Comments
              </label>
            </div>
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
        <h2>Delete Post</h2>
        <p>Are you sure you want to delete "{{ deletingPost?.title }}"?</p>
        <div class="modal-actions">
          <button @click="deletePost" class="delete-btn">Yes, Delete</button>
          <button @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const posts = ref([]);
const categories = ref([]);
const page = ref(1);
const totalPages = ref(1);
const search = ref('');
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingPost = ref(null);
const deletingPost = ref(null);
const tagsInput = ref('');
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
  tags: [],
  category_id: '',
  is_featured: false,
  allow_comments: true,
  published_at: '',
});

function fetchPosts(p = 1) {
  page.value = p;
  let params = { page: p };
  if (search.value) params.search = search.value;
  axios.get('/api/posts', { params })
    .then(res => {
      posts.value = res.data.data || res.data;
      totalPages.value = res.data.last_page || 1;
    });
}

function fetchCategories() {
  axios.get('/api/categories')
    .then(res => {
      categories.value = res.data.data || res.data;
    });
}

function openCreateModal() {
  editingPost.value = null;
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
    tags: [],
    category_id: '',
    is_featured: false,
    allow_comments: true,
    published_at: '',
  };
  tagsInput.value = '';
  showModal.value = true;
}

function openEditModal(post) {
  editingPost.value = post;
  form.value = {
    title: post.title,
    slug: post.slug,
    content: post.content,
    excerpt: post.excerpt || '',
    featured_image: post.featured_image || '',
    status: post.status,
    meta_title: post.meta_title || '',
    meta_description: post.meta_description || '',
    meta_keywords: post.meta_keywords || '',
    tags: post.tags || [],
    category_id: post.category_id || '',
    is_featured: post.is_featured || false,
    allow_comments: post.allow_comments !== false,
    published_at: post.published_at ? new Date(post.published_at).toISOString().slice(0, 16) : '',
  };
  tagsInput.value = (post.tags || []).join(', ');
  showModal.value = true;
}

function updateTags() {
  form.value.tags = tagsInput.value.split(',').map(tag => tag.trim()).filter(tag => tag);
}

function closeModal() {
  showModal.value = false;
}

function savePost() {
  updateTags();
  if (editingPost.value) {
    axios.put(`/api/posts/${editingPost.value.id}`, form.value)
      .then(() => {
        fetchPosts(page.value);
        closeModal();
      });
  } else {
    axios.post('/api/posts', form.value)
      .then(() => {
        fetchPosts(1);
        closeModal();
      });
  }
}

function confirmDelete(post) {
  deletingPost.value = post;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
}

function deletePost() {
  axios.delete(`/api/posts/${deletingPost.value.id}`)
    .then(() => {
      fetchPosts(page.value);
      closeDeleteModal();
    });
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

onMounted(() => {
  fetchPosts();
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
.post-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1rem;
}
.post-table th, .post-table td {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}
.post-table th {
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
