import VueRouter from 'vue-router';

import NotFound from '../common/NotFound.vue'
import UserIndex from './pages/Index.vue'
import UserForm from './pages/Form.vue'

const routes = [
    {
        path: '*',
        components: {
            default: NotFound
        }
    },
    {
        path: '/',
        name: 'user-index',
        components: {
            default: UserIndex,
        }
    },
    {
        path: '/create',
        name: 'create',
        components: {
            default: UserForm,
        }
    },
    {
        path: '/update/:id',
        name: 'update',
        components: {
            default: UserForm,
        }
    },
    {
        path: '/view/:id',
        name: 'view',
        components: {
            default: UserForm,
        }
    },
    {
        path: '/delete/:id',
        name: 'delete',
        components: {
            default: UserIndex,
        }
    },

];


let router = new VueRouter({
    routes
});

export default router;
