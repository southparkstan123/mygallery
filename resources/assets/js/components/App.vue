<template>
    <div>
        <nav-bar :isAuth="isAuth" :username="username" :email="email"></nav-bar>
        <div id="content" class="container">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import Navbar from './Navbar'
export default {
    components: {
        'nav-bar': Navbar,
    },
    data (){
        let isAuth = this.$auth.isAuthenticated();
        let username = null;
        let email = null;
        return {
            isAuth: isAuth,
            username: username,
            email: email
        }
    },
    methods: {
        getUsernameFromToken(){
            let access_token = localStorage.getItem('token');
            if(access_token){
                let config = {
                    headers:{
                        'Authorization' : 'Bearer ' + access_token
                    }
                };
                axios.get('/api/user',config).then(response => {
                    this.username = response.data.name;
                    this.email = response.data.email;
                    this.isAuth = this.$auth.isAuthenticated();
                }).catch(e => {
                    alert(e.response.data.error, 'error');
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
    // mounted (){
    //     this.isAuth = this.$auth.isAuthenticated();
    //     this.getUsernameFromToken();
    // },
}
</script>
