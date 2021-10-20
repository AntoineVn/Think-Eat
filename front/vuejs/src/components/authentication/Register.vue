<template>
    <div class="login-box">
        <form v-on:submit.prevent="postUser">
            <h3>Register</h3>

            <div class="form-group">
                <label>Firstname</label>
                <input v-model="firstname" type="text" class="form-control form-control-lg" required
       minlength="4"/>
            </div>

            <div class="form-group">
                <label>Lastname</label>
                <input v-model="lastname" type="text" class="form-control form-control-lg" required
       minlength="4"/>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input v-model="email" type="email" class="form-control form-control-lg" required />
            </div>

            <div class="form-group">
                <label>Nickname</label>
                <input v-model="nickname" type="text" class="form-control form-control-lg" required
       minlength="4"/>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input v-model="password" type="password" class="form-control form-control-lg" required
       minlength="6"/>
            </div>

            <div class="form-group">
                <label>Password confirmation</label>
                <input v-model="password_confirmation" type="password" class="form-control form-control-lg" required
       minlength="6"/>
            </div>

            <div class="form-group">
                <label>Location</label> 
                <input v-model="location" type="text" class="form-control form-control-lg" required/>
            </div>
      

            <div class="form-group">
                <label>Phone</label>
                <input v-model="phone" require type="integer" class="form-control form-control-lg" required
       maxlength="10" />
            </div>

            <button type="submit" class="btn btn-dark btn-lg btn-block">Register</button>
            <router-link type="submit" class="btn btn-dark btn-lg btn-block" to="/">Cancel</router-link>

            <p class="forgot-password text-right">
                Already registered ?
                <router-link to="/login">Login Now</router-link>
            </p>
        </form>
    </div>
</template>

<script>
export default {
  name: 'Register',
  data() {
      return {
          firstname: '',
          lastname: '',
          email: '',
          nickname: '',
          password: '',
          password_confirmation: '',
          location: '',
          phone: '',
          error: null
      }
  },
  methods: {

    postUser: async function() {

        // Prend les valeurs du formulaire

        const body_register = {
            firstname: this.firstname,
            lastname: this.lastname,
            email: this.email,  
            nickname: this.nickname,
            password: this.password,
            password_confirmation: this.password_confirmation,
            location: this.location,
            phone: this.phone
        }

        // Requete Api dans le back UserController => register()

        const request = new Request (
            'http://127.0.0.1:8000/api/register' ,
            {
            method: "POST",
            body : JSON.stringify(body_register)
            }
        );

        // Réponse de l'API

        const res = await fetch(request);
        const post = await res.json();
        if (post.token)
        {
            this.$router.push("/");
            alert("You've successfuly created your account ! Welcome to Think Eat !")
        }
        else
        {
            alert("There's been an issue with the creation of your account, please check your credentials")
        }
        }},

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
}
</style>