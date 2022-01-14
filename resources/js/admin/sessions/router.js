import VueRouter from 'vue-router';

import NotFound from '../common/NotFound.vue'
import SessionIndex from './pages/Index.vue'
import SessionForm from './pages/Form.vue'

const routes = [
    {
        path: '*',
        components: {
            default: NotFound
        }
    },
    {
        path: '/',
        name: 'session-index',
        components: {
            default: SessionIndex,
        }
    },
    {
        path: '/create',
        name: 'create',
        components: {
            default: SessionForm,
        }
    },
    {
        path: '/update/:id',
        name: 'update',
        components: {
            default: SessionForm,
        }
    },
    {
        path: '/view/:id',
        name: 'view',
        components: {
            default: SessionForm,
        }
    },
    {
        path: '/delete/:id',
        name: 'delete',
        components: {
            default: SessionIndex,
        }
    },

];


let router = new VueRouter({
    routes
});

export default router;
