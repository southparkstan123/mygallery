import Vue from 'vue';
import VueRouter from 'vue-router';

import GalleryList from './views/GalleryList';
import GalleryForm from './views/GalleryForm';
import AddGallery from './views/AddGallery';

import PageNotFound from './views/PageNotFound';
import Dashboard from './views/Dashboard';
import LoginPage from './views/LoginPage';
import RegistrationPage from './views/RegistrationPage';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { 
            path: '*', 
            name: 'notFound',
            component: PageNotFound ,
            meta: {
                forVisitors: true,
            }
        },
        { 
            path: '/', 
            name: 'dashboard',
            component: Dashboard,
            meta: {
                forAuth: true,
            }
        },
        { 
            path: '/galleries',
            name: 'listGalleries', 
            component: GalleryList,
            meta: {
                forAuth: true,
            }
        },
        { 
            path: '/gallery_add',
            name: 'addGallery', 
            component: AddGallery,
            meta: {
                forAuth: true,
            }
        },
        { 
            path: '/galleries/:id', 
            name: 'editImages', 
            component: GalleryForm,
            meta: {
                forAuth: true,
            }
        },
        { 
            path: '/login', 
            name: 'login', 
            component: LoginPage,
            meta: {
                forVisitors: true,
            }
        },
        { 
            path: '/signup', 
            name: 'registration', 
            component: RegistrationPage,
            meta: {
                forVisitors: true,
            }
        }
    ]
});

export default router;