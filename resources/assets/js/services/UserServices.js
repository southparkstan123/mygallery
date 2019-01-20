import axios from 'axios';

export default class UserServices{
    static async login(userObj){
        try {
            let data = new FormData();
            data.append('username',userObj.name);
            data.append('password',userObj.password);

            console.log(userObj.name);
    
            return await axios.post('/api/v1/user/checkUser', data, {
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });
        } catch (error) {
            return error
        }
    }

    static async createUser(userObj){
        try {
            let data = new FormData();

            data.append('name', userObj.name);
            data.append('password', userObj.password);
            data.append('password_confirmation', userObj.retypePassword);
            data.append('email', userObj.email);
            
            return await axios.post('api/v1/user/signup', data, {
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded' , 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
        } catch (error) {
            return error
        }
    }

    static async getUserFromToken(accessToken){
        try {
            return await axios.get('/api/user',{
                headers:{
                    'Authorization' : 'Bearer ' + accessToken
                }
            })
        } catch (error) {
            return error
        }
    }

    static async generateToken(userObj){
        try {
            let formData = new FormData();

            formData.append('client_id','2');
            formData.append('client_secret','OxBasA78CX7OSKaJ1jBOWmq5lz2jSLI0rm7PrjdK');
            formData.append('grant_type','password');
            formData.append('username',userObj.name);
            formData.append('password',userObj.password);
 
            return await axios.post('/oauth/token',formData, {
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
        } catch (error) {
            
        }
    }
}