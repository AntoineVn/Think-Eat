<template>
    <div class="login-box">
        <form v-on:submit.prevent="loginUser">
            <h3>Login</h3>

            <div class="form-group">
                <label>Email</label>
                <input v-model="email" type="email" class="form-control form-control-lg" />
            </div>

            <div class="form-group">
                <label>Password</label>
                <input v-model="password" type="password" class="form-control form-control-lg" />
            </div>

            <button type="submit" class="btn btn-dark btn-lg btn-block">Login</button>
            <router-link type="submit" class="btn btn-dark btn-lg btn-block" to="/">Cancel</router-link>

            <p class="forgot-password text-right mt-2 mb-4">
                <router-link to="/forgot-password">Forgot password ?</router-link>
            </p>
            <p class="forgot-password text-right">
                Not registered ?
                <router-link to="/register">Register Now</router-link>
            </p>

        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: '',
                password: ''
            }
        },

        methods: {

            async loginUser() {
                
                // Prend les valeurs du formulaire

                const body_login = {   
                    email: this.email,
                    password: this.password
                }

                // Requete Api dans le back UserController => login ()

                const request_login = new Request ( 'http://127.0.0.1:8000/api/login' ,
                    {
                    method: "POST",
                    body : JSON.stringify(body_login)
                    }
                );

                // Reponse de l'API

                const res = await fetch(request_login); 
                const post = await res.json();
                
                if (post.token) /* Si les identifiants sont corrects on récupère un token 
                                qui permettra d'afficher les vues si le User est connecté */
                {
                    console.log("Connecté")
                    this.$cookies.set('token',post.token)
                    // Vérification du token
                    const get_token = this.$cookies.get('token')
                    console.log(get_token)
                    this.$store.commit('addToken', get_token)
                    
                    const request_user = new Request ( 'http://127.0.0.1:8000/api/profile' ,
                        {
                        method: "GET",
                        headers: {
                             'Authorization': 'Bearer ' + post.token
                        }
                       
                        }
                        
                    );
                    const res1 = await fetch(request_user); 
                    const post1 = await res1.json();
                    console.log(post1.user.id)

                    this.$router.push("/dashboard")
                } else {
                    alert("Wrong password or email")
                }
            }

            

        }
    }
</script>

<style scoped>

.login-box {
    width: 400px;
    height: auto;
    background-color: white;
    margin-top: 60px;
    border-radius: 5px;
    padding: 40px;
    margin: auto;
    margin-top: 60px;
    border: 1px solid #E4E6E7;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.4);
}

h3 {
    text-align: center;
    margin: 0;
    padding: 0;
    font-weight: 800;
    font-size: 18px;
}

p {
    text-align: center;
    font-size: 14px;
    padding-bottom: 10px;
}

label {
    width: 100px;
    display: inline-block;
    padding-top: 20px;
}

.btn {
    margin: 20px;
}
</style>