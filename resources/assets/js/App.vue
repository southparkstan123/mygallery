<template>
    <div>
        <nav-bar :isAuth="isAuth" :username="username" :email="email"></nav-bar>
        <div id="content" class="container">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import Navbar from './components/Navbar'
import UserServices from './services/UserServices'

export default {
    components: {
        'nav-bar': Navbar,
    },
    data () {
        return {
            isAuth: this.$auth.isAuthenticated(),
            username: null,
            email: null
        }
    },
    methods: {
        getUsernameFromToken(){
            let accessToken = localStorage.getItem('token');

            if(accessToken){
                UserServices.getUserFromToken(accessToken).then(response => {
                    this.username = response.data.name;
                    this.email = response.data.email;
                    this.isAuth = this.$auth.isAuthenticated();
                }).catch(e => {
                    this.$auth.destroyToken();
                    this.$router.push({ name: 'login' });
                }); 
            }
        }
    },
    updated (){
        this.isAuth = this.$auth.isAuthenticated();
        this.getUsernameFromToken();
    },
}
</script>
