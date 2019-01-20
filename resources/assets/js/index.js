require('./bootstrap');

import Vue from 'vue';
import VueResource from 'vue-resource';
import App from './App';
import Auth from './auth/auth.js';
import Router from './router.js';

Vue.use(VueResource);
Vue.use(Auth);

Vue.http.headers.common['Authorization'] = "Bearer" + Vue.auth.getToken();

Vue.http.interceptors.push((request, next) => {
    next(e => {
        if(e.response.status === 404){
            alert(e.response.status.toString(), e.response.body.error, 'error');
        }else if(e.response.status === 500){
            alert(e.response.status.toString(), 'Internal error!');
        }else if(e.response.status === 401){
            alert(e.response.status.toString(), 'Unauthenticated.');
        }

    })
})

Router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.forVisitors)){
        if(Vue.auth.isAuthenticated()){
            next({
                name: 'dashboard'
            })
        }else{
            next();
        }
    }else if(to.matched.some(record => record.meta.forAuth)){
        if(!Vue.auth.isAuthenticated()){
            next({
                name: 'login'
            })
        }else{
            next();
        }
    }else{
        next();
    }
});

var parent = new Vue({
    router: Router,
    render: h => h(App),
    el: '#app'
});
    