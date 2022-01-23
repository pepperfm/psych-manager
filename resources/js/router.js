import VueRouter from 'vue-router';

import NotFound from './components/layout/NotFound';

import DefaultHeader from './components/layout/Header';
import DefaultAside from './components/layout/Aside';

import Login from "./components/login/Login"
import LoginAside from "./components/login/LoginAside"
import LoginHeader from "./components/login/LoginHeader"

import Main from "./components/Main";
import Clients from "./components/clients/Index"
import ClientForm from "./components/clients/Form"
import Sessions from  "./components/sessions/Index"

const routes = [
  {
    path: '*',
    components: {
      default: NotFound
    }
  },
  {
    path: '/',
    name: 'main',
    components: { aside: DefaultAside, header: DefaultHeader, default: Main },
    meta: { auth: true, menuitem: '1' }
  },
  {
    path: '/login',
    name: 'login',
    components: { aside: LoginAside, header: LoginHeader, default: Login },
    meta: { auth: false, menuitem: '0' }
  },
  {
    path: '/clients',
    name: 'clients',
    components: { aside: DefaultAside, header: DefaultHeader, default: Clients },
    meta: { auth: false, menuitem: '2' }
  },
  {
    path: '/sessions',
    name: 'sessions',
    components: { aside: DefaultAside, header: DefaultHeader, default: Sessions },
    meta: { auth: false, menuitem: '3' }
  },
  {
    path: '/clients/:id',
    name: 'clients.update',
    components: { aside: DefaultAside, header: DefaultHeader, default: ClientForm },
    meta: { auth: true, menuitem: '4-2' }
  },
  {
    path: '/create',
    name: 'create',
    components: { aside: DefaultAside, header: DefaultHeader, default: ClientForm },
    meta: { auth: true, menuitem: '4-2' }
  },
]

const router = new VueRouter({
  base: '/panel',
  mode: 'history',
  routes
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.auth)) {
    if (!window.$identity.isGuest) {
      next()
      return
    }

    next({
      path: '/login',
      query: { redirect: to.fullPath }
    });
  } else {
    next();
  }
});

//  Fix error NavigationDuplicated second option
const originalPush = VueRouter.prototype.push;
VueRouter.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => {
    if (err.name !== 'NavigationDuplicated') throw err
  });
}

export default router;
