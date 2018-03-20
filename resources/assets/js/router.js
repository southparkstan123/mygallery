import Vue from 'vue';
import VueRouter from 'vue-router';

import GalleryList from './components/GalleryList';
import GalleryForm from './components/GalleryForm';
import AddGallery from './components/AddGallery';

import PageNotFound from './components/PageNotFound';
import Dashboard from './components/Dashboard';
import LoginPage from './components/LoginPage';
import RegistrationPage from './components/RegistrationPage';

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