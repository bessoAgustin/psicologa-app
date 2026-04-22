import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import { createApp, h } from 'vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name: string) => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true }) as Record<string, any>;
    const key = `./Pages/${name}.vue`;
    const page = pages[key];

    if (!page) {
      throw new Error(`Página no encontrada: ${name}`);
    }

    return page.default ?? page;
  },

  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el);

    initializeTheme();
  },

  progress: {
    color: '#4B5563',
  },
});
