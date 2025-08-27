import { createPinia } from 'pinia';
import createPersistedState from 'pinia-plugin-persistedstate';
import { markRaw } from 'vue';
import router from '../router';

const pinia = createPinia();

// You can add Pinia plugins here
pinia.use(({ store }) => {
    store.router = markRaw(router)
});
pinia.use(createPersistedState);

export default pinia;