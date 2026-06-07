import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import router from './router/index.js';
import App from './App.vue';

const app = createApp(App);
app.use(router);

// Global Error Handler to debug crashes
app.config.errorHandler = (err, instance, info) => {
    console.error('Vue Error:', err, info);
    alert(`Error in frontend: ${err.message}\nInfo: ${info}`);
};

app.mount('#app');