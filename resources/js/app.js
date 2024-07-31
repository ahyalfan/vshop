import './bootstrap';
import '../css/app.css';
import 'element-plus/dist/index.css' //ini cssnya elementplus
import 'sweetalert2/dist/sweetalert2.min.css'; // alert2

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import ElementPlus from 'element-plus' //ini kita akan memakai component dari elementplus
import VueSweetalert2 from 'vue-sweetalert2'; //vue alert


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            app.use(plugin)
            app.use(ZiggyVue)
            app.use(ElementPlus)
            app.use(VueSweetalert2),
            window.Swal = app.config.globalProperties.$swal
            app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
