import './bootstrap';
import * as bootstrap from 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';

import { createApp, h, defineAsyncComponent } from 'vue/dist/vue.esm-bundler.js';
import { createInertiaApp } from '@inertiajs/vue3'
import BaseLayout from '@/Layouts/BaseLayout.vue';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]
    page.default.layout = page.default.layout || BaseLayout
    // alert(page.default.layout)
    return page
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });
    app.use(plugin)
      .mount(el)
  },
})