<template>
  <div class="section">
    <h1>Media Library</h1>
    <div class="actions">
      <div class="filter-controls">
        <select v-model="filterType" @change="fetchMedia(1)">
          <option value="">All Types</option>
          <option value="images">Images</option>
          <option value="videos">Videos</option>
          <option value="documents">Documents</option>
        </select>
        <input v-model="search" @input="fetchMedia(1)" placeholder="Search media..." />
      </div>
      <button @click="openUploadModal">Upload Files</button>
    </div>

    <!-- Gallery View -->
    <div class="media-gallery">
      <div v-for="item in media" :key="item.id" class="media-item" @click="selectMedia(item)">
        <div class="media-preview">
          <img v-if="item.file_type === 'image'" :src="item.url" :alt="item.alt_text" />
          <div v-else class="file-icon">
            <span class="icon">{{ getFileIcon(item.file_type) }}</span>
          </div>
        </div>
        <div class="media-info">
          <div class="media-name">{{ item.name }}</div>
          <div class="media-meta">
            <span>{{ item.formatted_size }}</span>
            <span>{{ formatDate(item.created_at) }}</span>
          </div>
        </div>
        <div class="media-actions">
          <button @click.stop="openEditModal(item)">Edit</button>
          <button @click.stop="confirmDelete(item)" class="delete-btn">Delete</button>
        </div>
      </div>
    </div>

    <div class="pagination">
      <button :disabled="page === 1" @click="fetchMedia(page - 1)">&lt; Prev</button>
      <span>Page {{ page }} of {{ totalPages }}</span>
      <button :disabled="page === totalPages" @click="fetchMedia(page + 1)">Next &gt;</button>
    </div>

    <!-- Upload Modal -->
    <div v-if="showUploadModal" class="modal-overlay">
      <div class="modal modern-modal">
        <button class="close-btn" @click="closeUploadModal">&times;</button>
        <h2>Upload Files</h2>
        <form @submit.prevent="uploadFiles">
          <div class="upload-area" @drop="handleDrop" @dragover.prevent @dragenter.prevent>
            <input
              ref="fileInput"
              type="file"
              multiple
              @change="handleFileSelect"
              accept="image/*,video/*,application/pdf,.doc,.docx,.txt"
              style="display: none;"
            />
            <div class="upload-content">
              <div class="upload-icon">📁</div>
              <p>Drag and drop files here or click to browse</p>
              <button type="button" @click="$refs.fileInput.click()">Choose Files</button>
            </div>
          </div>

          <div v-if="selectedFiles.length > 0" class="selected-files">
            <h3>Selected Files:</h3>
            <div v-for="(file, index) in selectedFiles" :key="index" class="file-item">
              <span>{{ file.name }}</span>
              <span>{{ formatFileSize(file.size) }}</span>
              <button type="button" @click="removeFile(index)" class="remove-btn">×</button>
            </div>
          </div>

          <div class="form-group">
            <label>Alt Text (for images)</label>
            <input v-model="uploadForm.alt_text" placeholder="Descriptive text for accessibility" />
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea v-model="uploadForm.description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="uploadForm.is_public" />
              Public (accessible without authentication)
            </label>
          </div>
          <div class="modal-actions">
            <button class="primary" type="submit" :disabled="uploading">
              {{ uploading ? 'Uploading...' : 'Upload Files' }}
            </button>
            <button class="secondary" type="button" @click="closeUploadModal">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="modal-overlay">
      <div class="modal modern-modal">
        <button class="close-btn" @click="closeEditModal">&times;</button>
        <h2>Edit Media</h2>
        <form @submit.prevent="saveMedia">
          <div class="media-preview-large">
            <img v-if="editingMedia.file_type === 'image'" :src="editingMedia.url" :alt="editingMedia.alt_text" />
            <div v-else class="file-icon-large">
              <span class="icon">{{ getFileIcon(editingMedia.file_type) }}</span>
            </div>
          </div>
          <div class="form-group">
            <label>Name</label>
            <input v-model="editForm.name" required />
          </div>
          <div class="form-group">
            <label>Alt Text</label>
            <input v-model="editForm.alt_text" />
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea v-model="editForm.description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Caption</label>
            <input v-model="editForm.caption" />
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="editForm.is_public" />
              Public
            </label>
          </div>
          <div class="media-info-display">
            <p><strong>File:</strong> {{ editingMedia.filename }}</p>
            <p><strong>Size:</strong> {{ editingMedia.formatted_size }}</p>
            <p><strong>Type:</strong> {{ editingMedia.mime_type }}</p>
            <p><strong>URL:</strong> <a :href="editingMedia.url" target="_blank">{{ editingMedia.url }}</a></p>
          </div>
          <div class="modal-actions">
            <button class="primary" type="submit">Save Changes</button>
            <button class="secondary" type="button" @click="closeEditModal">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal">
        <h2>Delete Media</h2>
        <p>Are you sure you want to delete "{{ deletingMedia?.name }}"?</p>
        <p class="warning">This action cannot be undone.</p>
        <div class="modal-actions">
          <button @click="deleteMedia" class="delete-btn">Yes, Delete</button>
          <button @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const media = ref([]);
const page = ref(1);
const totalPages = ref(1);
const search = ref('');
const filterType = ref('');
const showUploadModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const editingMedia = ref(null);
const deletingMedia = ref(null);
const selectedFiles = ref([]);
const uploading = ref(false);

const uploadForm = ref({
  alt_text: '',
  description: '',
  is_public: true,
});

const editForm = ref({
  name: '',
  alt_text: '',
  description: '',
  caption: '',
  is_public: true,
});

function fetchMedia(p = 1) {
  page.value = p;
  let params = { page: p };
  if (search.value) params.search = search.value;
  if (filterType.value) params.type = filterType.value;

  axios.get('/api/media', { params })
    .then(res => {
      media.value = res.data.data || res.data;
      totalPages.value = res.data.last_page || 1;
    });
}

function openUploadModal() {
  showUploadModal.value = true;
  selectedFiles.value = [];
  uploadForm.value = {
    alt_text: '',
    description: '',
    is_public: true,
  };
}

function closeUploadModal() {
  showUploadModal.value = false;
  selectedFiles.value = [];
}

function handleFileSelect(event) {
  selectedFiles.value = [...event.target.files];
}

function handleDrop(event) {
  event.preventDefault();
  selectedFiles.value = [...event.dataTransfer.files];
}

function removeFile(index) {
  selectedFiles.value.splice(index, 1);
}

function uploadFiles() {
  if (selectedFiles.value.length === 0) return;

  uploading.value = true;
  const formData = new FormData();

  selectedFiles.value.forEach(file => {
    formData.append('file', file);
  });

  formData.append('alt_text', uploadForm.value.alt_text);
  formData.append('description', uploadForm.value.description);
  formData.append('is_public', uploadForm.value.is_public);

  axios.post('/api/media', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
  .then(() => {
    fetchMedia(1);
    closeUploadModal();
  })
  .finally(() => {
    uploading.value = false;
  });
}

function openEditModal(item) {
  editingMedia.value = item;
  editForm.value = {
    name: item.name,
    alt_text: item.alt_text || '',
    description: item.description || '',
    caption: item.caption || '',
    is_public: item.is_public,
  };
  showEditModal.value = true;
}

function closeEditModal() {
  showEditModal.value = false;
}

function saveMedia() {
  axios.put(`/api/media/${editingMedia.value.id}`, editForm.value)
    .then(() => {
      fetchMedia(page.value);
      closeEditModal();
    });
}

function confirmDelete(item) {
  deletingMedia.value = item;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
}

function deleteMedia() {
  axios.delete(`/api/media/${deletingMedia.value.id}`)
    .then(() => {
      fetchMedia(page.value);
      closeDeleteModal();
    });
}

function selectMedia(item) {
  // Copy URL to clipboard
  navigator.clipboard.writeText(item.url);
  // You could also show a toast notification here
}

function getFileIcon(type) {
  switch (type) {
    case 'image': return '🖼️';
    case 'video': return '🎥';
    case 'audio': return '🎵';
    case 'pdf': return '📄';
    default: return '📁';
  }
}

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

onMounted(() => {
  fetchMedia();
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
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}
.filter-controls {
  display: flex;
  gap: 1rem;
}
.media-gallery {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}
.media-item {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
  transition: all 0.2s;
  cursor: pointer;
}
.media-item:hover {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}
.media-preview {
  height: 200px;
  background: #f9fafb;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}
.media-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.file-icon {
  font-size: 3rem;
  color: #6b7280;
}
.media-info {
  padding: 1rem;
}
.media-name {
  font-weight: 500;
  margin-bottom: 0.5rem;
  word-break: break-word;
}
.media-meta {
  display: flex;
  justify-content: space-between;
  font-size: 0.875rem;
  color: #6b7280;
}
.media-actions {
  padding: 0 1rem 1rem;
  display: flex;
  gap: 0.5rem;
}
.media-actions button {
  flex: 1;
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}
.upload-area {
  border: 2px dashed #d1d5db;
  border-radius: 8px;
  padding: 2rem;
  text-align: center;
  margin-bottom: 1rem;
  transition: border-color 0.2s;
}
.upload-area:hover {
  border-color: #2563eb;
}
.upload-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}
.upload-icon {
  font-size: 3rem;
}
.selected-files {
  margin: 1rem 0;
}
.file-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem;
  background: #f9fafb;
  border-radius: 4px;
  margin-bottom: 0.5rem;
}
.remove-btn {
  background: #dc2626;
  color: white;
  border: none;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  cursor: pointer;
}
.media-preview-large {
  text-align: center;
  margin-bottom: 1rem;
}
.media-preview-large img {
  max-width: 100%;
  max-height: 300px;
  border-radius: 8px;
}
.file-icon-large {
  font-size: 5rem;
  color: #6b7280;
}
.media-info-display {
  background: #f9fafb;
  padding: 1rem;
  border-radius: 4px;
  margin: 1rem 0;
}
.media-info-display p {
  margin: 0.25rem 0;
}
.warning {
  color: #dc2626;
  font-weight: 500;
}
.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  margin-top: 2rem;
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
