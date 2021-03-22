import VueRouter from 'vue-router';

import NotFound from '../common/NotFound.vue'
import ProfileForm from './pages/Form.vue'

const routes = [
    {
        path: '*',
        components: {
            default: NotFound
        }
    },
    {
        path: '/update/:id',
        name: 'update',
        components: {
            default: ProfileForm,
        }
    },
    {
        path: '/',
        name: 'view',
        components: {
            default: ProfileForm,
        }
    },
];


let router = new VueRouter({
    routes
});

export default router;
