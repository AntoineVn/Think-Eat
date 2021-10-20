<template>
  <div class="ui stackable menu">
    <img src="https://zupimages.net/up/21/14/tunp.png" width="5%">
    <router-link :class="buttonClass('/')" to="/"><i class="home icon"></i>Home</router-link> 
    <router-link :class="buttonClass('/feed')" :to="{name : 'Feed'}">Feed</router-link> <!-- VUE A CREER  -->
    <router-link :class="buttonClass('/dashboard')" v-if="this.iscookie == true"  :to="{name : 'Dashboard'}">Dashboard</router-link>
    <router-link :class="buttonClass('/about')" :to="{name : 'About'}">About</router-link>
   <!--  <a class="item" style="font-size:0.2em"> {{token[0]}}</a>
    <a class="item" style="font-size:0.2em"> {{this.$cookies.get('token') }} </a>  -->
    
    <div class="right menu">
      <router-link :class="buttonClass('/login')" v-if="this.iscookie == false" :to="{name : 'Login'}">Login</router-link> 
      <router-link :class="buttonClass('/register')" v-if="this.iscookie == false" :to="{name : 'Register'}">Register</router-link>
      <router-link :class="buttonClass('/profile')"  v-if="this.iscookie == true" :to="{name : 'Profile'}"><i class="user icon"></i>Profile</router-link>
      <a :class="buttonClass('/logout')" v-if="this.iscookie == true" v-on:click="logoutUser()"> Logout </a>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex';


export default {
    data() {
        return{
            title: "Header Title",
            iscookie: false,
        }
    },
    beforeMount() {
      if(this.$cookies.get('token')){
        this.$store.commit('addToken', this.$cookies.get('token'))
      }
    },
    computed: mapState({
      token : s => s.token
    }),
    watch : {
      token: function () {
        if(this.$store.state.token[0]) {
          this.iscookie = true;
        }
        else {
          this.iscookie = false;
        }
      }
    },
    methods : {
      async logoutUser() {
        const request = new Request ( 'http://127.0.0.1:8000/api/logout' ,
            {
              method: "POST",
            }
        );
        const res = await fetch(request)
        this.$cookies.remove('token')
        this.$store.commit('clearAll')
        this.$router.push("/login")
        /* const post = await res.json() */
      },
      buttonClass(path) {
        return {
          btn: true,
          'ui item active': this.$route.path === path,
          'item': this.$route.path !== path
        };
      }
    },
}
</script>

<style>
header {
    background-color: darkgrey;
    text-align: center;
    position: absolute;
    max-height: 20px;
}
a {
  font-size: 20px;
}
</style>