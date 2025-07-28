<template>
  <div class="login-container">
    <h2>Admin Login</h2>
    <form @submit.prevent="login">
      <div>
        <label>Email</label>
        <input v-model="email" type="email" required />
      </div>
      <div>
        <label>Password</label>
        <input v-model="password" type="password" required />
      </div>
      <button type="submit" :disabled="loading">Login</button>
      <div v-if="error" class="error">{{ error }}</div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const email = ref('');
const password = ref('');
const error = ref('');
const loading = ref(false);
const router = useRouter();

async function login() {
  error.value = '';
  loading.value = true;
  try {
    const response = await axios.post('/api/login', {
      email: email.value,
      password: password.value,
      device_name: 'admin-panel',
    });

    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token);
      // Set the default authorization header for all future requests
      axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
      router.push('/');
    } else {
      error.value = 'Invalid response from server.';
    }
  } catch (e) {
    if (e.response?.data?.message) {
      error.value = e.response.data.message;
    } else {
      error.value = 'Login failed. Please try again.';
    }
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 5rem auto;
  padding: 2rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.login-container h2 {
  margin-bottom: 1.5rem;
}
.login-container label {
  display: block;
  margin-bottom: 0.5rem;
}
.login-container input {
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.login-container button {
  width: 100%;
  padding: 0.75rem;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
}
.login-container .error {
  color: #dc2626;
  margin-top: 1rem;
}
</style>
