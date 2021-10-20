<template>
    <div class="login-box">
        <form v-on:submit.prevent ="onSubmit">
            <h3>Forgot your password ?</h3>
            <p>Enter your email address to receive the instructions on how to reset your password.</p>

            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control form-control-lg" v-model="user.email"/>
            </div>
            <br>

            <button type="submit" class="btn btn-dark btn-lg btn-block">Reset password</button>
            <router-link type="submit" class="btn btn-dark btn-lg btn-block" to="/">Cancel</router-link>

        </form>
    </div>
</template>

<script>
/* import * as auth from */
    export default {
        name:'ForgotPasswordRequest',
        data() {
            return {
                user: {
                    email: '',
                    password: ''
                } 
            }
        },
    methods: {
/*         onSubmit: async function() {
            try {
                const respone = await
            } catch (error)
        }, */
    }}


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