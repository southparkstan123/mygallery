export default function (Vue){
    Vue.auth = {
        //for login
        setToken (token, expiration) {
            localStorage.setItem("token", token);
            localStorage.setItem("expiration", expiration);
        },
        getToken () {
            var token = localStorage.getItem("token");
            var expiration = localStorage.getItem("expiration");

            // console.log(token);
            // console.log(expiration);

            if(!token || !expiration)
                return null;

            if(Date.now() > parseInt(expiration)){
                this.destroyToken();
                return null;
            }else{

                // console.log(token);
                return token;
            }
        },
        //for logout
        destroyToken () {
            localStorage.removeItem("token");
            localStorage.removeItem("expiration");
        },
        //for checking token
        isAuthenticated () {
            if(this.getToken() != null){
                return true;
            }else{
                return false;
            }
        },
    };

    Object.defineProperties(Vue.prototype,{
       $auth:{
           get: () =>{
               return Vue.auth;
           }
       } 
    })
}