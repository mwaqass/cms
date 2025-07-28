import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import AdminApp from './components/AdminApp.vue';
import AdminLayout from './components/AdminLayout.vue';
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import Users from './components/Users.vue';
import Roles from './components/Roles.vue';
import Pages from './components/Pages.vue';
import Posts from './components/Posts.vue';
import Categories from './components/Categories.vue';
import Media from './components/Media.vue';
import Menus from './components/Menus.vue';
import Settings from './components/Settings.vue';
import Reports from './components/Reports.vue';
import Analytics from './components/Analytics.vue';

const routes = [
    {
        path: '/login',
        component: Login
    },
    {
        path: '/',
        component: AdminLayout,
        children: [
            {
                path: '',
                component: Dashboard
            },
            {
                path: 'users',
                component: Users
            },
            {
                path: 'roles',
                component: Roles
            },
            {
                path: 'pages',
                component: Pages
            },
            {
                path: 'posts',
                component: Posts
            },
            {
                path: 'categories',
                component: Categories
            },
            {
                path: 'media',
                component: Media
            },
            {
                path: 'menus',
                component: Menus
            },
            {
                path: 'settings',
                component: Settings
            },
            {
                path: 'reports',
                component: Reports
            },
            {
                path: 'analytics',
                component: Analytics
            }
        ]
    }
];

const router = createRouter({
    history: createWebHistory('/admin'),
    routes
});

// Navigation guard to check authentication
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('auth_token');

    if (to.path !== '/login' && !token) {
        next('/login');
    } else if (to.path === '/login' && token) {
        next('/');
    } else {
        next();
    }
});

const app = createApp(AdminApp);
app.use(router);
app.mount('#app');
