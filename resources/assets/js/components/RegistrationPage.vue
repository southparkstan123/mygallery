<template>
    <div>
        <message :success="success" :message="message"></message>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Sign Up</div>
                    <div class="panel-body">
                        <form class="form-horizontal" v-on:submit.prevent="createUser()">
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Username</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="name" aria-describedby="error-message-name" v-model="name">
                                    <small id="error-message-name" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.name">{{ message }}</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="descrption" class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="password" aria-describedby="error-message-password" v-model="password">
                                    <small id="error-message-password" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.password">{{ message }}</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="descrption" class="col-md-4 control-label">Retype Password</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="password" aria-describedby="error-message-retype-password" v-model="retypePassword">
                                    <small id="error-message-retype-password" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.retypePassword">{{ message }}</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="descrption" class="col-md-4 control-label">Email Address</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" id="password" aria-describedby="error-message-email" v-model="email">
                                    <small id="error-message-email" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.email">{{ message }}</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success">Register</button>
                                    <button type="reset" class="btn btn-default">Reset</button>
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
import Message from './Message';
export default {
    components: {
        'message': Message
    },
    data (){
        let name = '';
        let password = '';
        let retypePassword = '';
        let email = '';
        let errors = [];
        let message  = '';
        let success  = '';

        return{
            name: name,
            password: password,
            retypePassword: retypePassword,
            email: email,
            errors: errors,
            message: message,
            success: success
        }
    },
    methods: {
        createUser (){
            let data = new FormData();

            data.append('name',this.name);
            data.append('password',this.password);
            data.append('password_confirmation',this.retypePassword);
            data.append('email',this.email);

            const config = {
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded' , 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            };
            
            axios.post('api/v1/user/signup',data, config).then(response => { 
                if(response.data.success){
                    this.message = response.data.message;
                    this.$router.push({ 
                        path: '/login',
                    });
                }else{
                    this.errors = response.data.errors;
                }
                this.success = response.data.success;
                this.message = response.data.message;
            }).catch(e => {
                this.errors.push(e);
            }); 
        },
    }
}
</script>
