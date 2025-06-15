import './bootstrap';
import { createApp } from 'vue';
import VideoScroll from './components/VideoScroll.vue';

import axios from 'axios';

axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL || 'https://scrollminds.xyz';


axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Interceptor para errores de autenticación
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

// Crear y montar la app Vue
const app = createApp({});
app.component('video-scroll', VideoScroll);
app.mount('#app');
