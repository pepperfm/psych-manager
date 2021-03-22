import VueRouter from 'vue-router';

import NotFound from '../common/NotFound.vue'
import CategoryIndex from './pages/Index.vue'
import CategoryForm from './pages/Form.vue'

const routes = [
    {
        path: '*',
        components: {
            default: NotFound
        }
    },
    {
        path: '/',
        name: 'category-index',
        components: {
            default: CategoryIndex,
        }
    },
    {
        path: '/create',
        name: 'create',
        components: {
            default: CategoryForm,
        }
    },
    {
        path: '/update/:id',
        name: 'update',
        components: {
            default: CategoryForm,
        }
    },
    {
        path: '/view/:id',
        name: 'view',
        components: {
            default: CategoryForm,
        }
    },
    {
        path: '/delete/:id',
        name: 'delete',
        components: {
            default: CategoryIndex,
        }
    },

];


let router = new VueRouter({
    routes
});

export default router;
