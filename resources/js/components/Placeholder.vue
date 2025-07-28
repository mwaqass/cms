<template>
  <div class="placeholder">
    <template v-if="authorized">
      <h1>{{ title }}</h1>
      <p>This is the {{ title }} section. Content coming soon!</p>
    </template>
    <template v-else>
      <h1>Not Authorized</h1>
      <p>You do not have permission to view this section.</p>
    </template>
  </div>
</template>

<script setup>
const props = defineProps({
  title: String,
  userRole: {
    type: String,
    default: 'admin',
  },
});

// Simple role check: only admin can access Users, Roles, Settings
const adminOnly = ['Users', 'Roles', 'Settings'];
const authorized = !(adminOnly.includes(props.title) && props.userRole !== 'admin');
</script>

<style scoped>
.placeholder {
  padding: 2rem;
  text-align: center;
}
.placeholder h1 {
  font-size: 2rem;
  margin-bottom: 1rem;
}
</style>
