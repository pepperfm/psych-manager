import VueRouter from 'vue-router';

import NotFound from '../common/NotFound.vue'
import LoginForm from './pages/Form.vue'
import ProfileForm from '../profile/pages/Form.vue'

const routes = [
    {
        path: '*',
        components: {
            default: NotFound
        }
    },
    {
        path: '/',
        name: 'login-index',
        components: {
            default: LoginForm,
        }
    },
    {
        path: '/profile',
        name: 'profile',
        components: {
            default: ProfileForm,
        }
    },
];


let router = new VueRouter({
    routes
});

export default router;
