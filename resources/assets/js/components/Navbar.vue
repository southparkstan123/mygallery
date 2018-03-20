<template>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
            </button>
            </div>
            <nav class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <router-link to="/" tag="li" v-if="isAuth">
                        <a>Dashboard</a>
                    </router-link>
                    <router-link to="/galleries" tag="li" v-if="isAuth">
                        <a>Gallery</a>
                    </router-link>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <router-link to="/login" tag="li" v-if="!isAuth">
                        <a><span class="glyphicon glyphicon-log-in"></span> Login</a>
                    </router-link>
                    <router-link to="/signup" tag="li" v-if="!isAuth">
                        <a><span class="glyphicon glyphicon-user"></span> Register</a>
                    </router-link>
                    <li v-if="isAuth" class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">View User Detail
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#">
                                    <span class="glyphicon glyphicon-user"></span> {{ username }}
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="glyphicon glyphicon-envelope"></span> {{ email }}
                                </a>
                            </li>
                            <li>
                                <a href="#" v-on:click.prevent="logout()">
                                    <span class="glyphicon glyphicon-log-out"></span> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </nav>
</template>
<script>
export default {
    props: ['isAuth','username','email'],
    methods: {
        logout(){
            this.$auth.destroyToken();
            this.$router.push({ path: '/login'});
        }
    },
}
</script>
