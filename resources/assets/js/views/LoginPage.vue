<template>
    <div>
        <message :success="success" :message="message"></message>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        <form class="form-horizontal" v-on:submit.prevent="login()">
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Name</label>
                                <div class="col-md-6">
                                    <input type="name" class="form-control" id="name" aria-describedby="error-message-name" v-model="user.name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="descrption" class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="password" aria-describedby="error-message-description" v-model="user.password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success">Sign in</button>
                                    <!-- <button class="btn btn-link" v-on:click="routeToRegisterForm()">Forgot Password</button> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Message from '../components/Message';
import UserServices from '../services/UserServices';

export default {
    components:{
        'message': Message
    },
    data (){
        return{
            user: {
                name: "",
                password: ""
            },
            message: "",
            success: false,
            size: 'small',
            errors: []
        }
    },
    methods: {
        routeToRegisterForm(){
            var path = '/signup';
            this.$router.push({ path: path });
        },
        login(){
            UserServices.login(this.user).then(response => {
                //Generate token and redirect to dashboard if the credential is valid, otherwise show the error message.
                if(response.data){                
                    UserServices.generateToken(this.user).then(response => { 
                        if(!response.errors){
                            this.success = true;
                            this.$auth.setToken(response.data.access_token,response.data.expires_in + Date.now());
                            this.$router.push({ name: 'dashboard'});
                        }else{
                            this.message = response.message;
                            this.status = false;
                        }
                    }).catch((e) => {
                        this.errors.push(e);
                    }); 
                }else{
                    this.message = 'Invalid credential';
                    this.status = false;
                }
            }).catch((e) => {
                this.errors.push(e);
            });
        }
    }
}
</script>
