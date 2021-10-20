<template>
    <div class="grid-container">
      <div class="title">
        <p v-if="storeuser[0]" class="name_fridge"> {{storeuser[0].nickname.charAt(0).toUpperCase() + storeuser[0].nickname.slice(1)}}'s Fridge </p>
      </div>
      <div class="kitchen">
        <div class="add-aliment">
        <new-aliment class="newaliment"></new-aliment>
      </div>
        
        <div class="frigo">
          <div class="container" v-on:click="toggleDoor">
            <div class="backDoor" >
                <div class ="inside-fridge" v-on:click="toggleDoor">
                <div class="fridge-leg left"></div>
                <div class="fridge-leg right"></div>
                </div>
                <br>
                <categories></categories>
            </div>
            <div class="door" v-on:click="toggleDoor">
                <div class="fridge-outline">
                    <div class="fridge-door"></div>
                    <div class="fridge-shadow"></div>
                    <div class="fridge-handle small"></div>
                    <div class="fridge-handle big"></div>
                    <i class="certificate icon"></i>
                    <i class="star icon"></i>
                    <i class="smile icon"></i>
                </div>
            </div>
          </div>
        </div>

        <div class="recap">
          <router-link :to="{name : 'Recap'}"><button class="ui button">
            Recap
          </button></router-link>
        </div>
        
    </div>
  </div>     
</template>

<script>
import newAliment from './newAliment';
import Categories from './Categories';
import { mapState } from 'vuex';

export default {
  name: 'dashboard',
  components: {
    'new-aliment': newAliment,
    'categories': Categories
  },
  data () {
    return {
      msg: 'Bonjour'
    }
  },
  beforeMount() {
    this.getUser()
  },
  computed : mapState({
      storeuser : s => s.user
    }),
  methods : {
    toggleDoor : function toggleDoor() {
        var element = document.querySelector(".door");
        if (element != null){
          element.addEventListener("click", toggleDoor);
          element.classList.toggle("doorOpen");
        }
    },
    async getUser(){
      const get_token = this.$cookies.get('token')
      this.tokenvar = get_token
      if (get_token){
          const request_user = new Request ( 'http://127.0.0.1:8000/api/profile' ,
          {
              method: "GET",
              headers: {
                  'Authorization': 'Bearer ' + get_token
              }
          });
          const res =  await fetch(request_user); 
          const post = await res.json();
          if (this.$store.state.user.find(comp => comp.id === post.user.id) == undefined){
              this.$store.commit('addUser', post.user);
          }
      }
      else{
          this.$router.push("/")
      }
    }
  }
}
</script>

<style scoped>
.name_fridge {
  font-size: 60px;
  text-align: center;
  font-family: Billipo;
  
}

.backDoor
{
    position: absolute;
    height: 630px;
    width: 350px;
    background-color: #acacac;
    border: 7px solid rgb(112, 112, 112);
    border-radius: 30px;
}

.door
{ 
  transform-origin: right;
  /*Speed of the Door animation*/
  transition: all 0.5s ease-in-out;
  
}


.doorOpen
{
  /*prespectiv creates the door open effect*/
  transform: perspective(3000px) translateZ(0px) translateX(30px) translateY(0px) rotateY(100deg);
}

/* body {
  background-color: #dfeffe;
  padding: 0% 0 0;
} */
body .container {
  position: relative;
  height: 630px;
  width: 350px;
  margin: 0 auto;
  text-align: center;
}

body .container .certificate {
  
  top: 35px;
  left:150px;
  font-size: 50px;
  color: #2711a0;
}
body .container .smile {
  top: 390px;
  left:100px;
  font-size: 50px;
  color:#bd0000;
}
body .container .star {
  top: 280px;
  left:230px;
  font-size: 50px;
  color: yellow;
}

body .container .fridge-outline * {
  position: absolute;
}
body .container .inside-fridge * {
  position: absolute;
}
body .container .fridge-outline {
  position: relative;
  z-index: 10;
  height: 630px;
  width: 350px;
  background-color: #9b9b9b;
  border: 7px solid rgb(112, 112, 112);
  border-radius: 30px;
}
body .container .fridge-outline .fridge-door {
  z-index: 10;
  top: 35%;
  left: 5%;
  width: 90%;
  height: 6px;
  background-color: #616161;
  border-radius: 3px;
}
body .container .fridge-outline .fridge-shadow {
  top: 0;
  bottom: 0;
  width: 18%;
  background-color: rgb(112, 112, 112);
  border-radius: 23px 0 0 23px;
}
body .container .fridge-outline .fridge-handle {
  left: 6%;
  width: 10px;
  background-color: #b3b3b3;
  border-radius: 5px;
}
body .container .fridge-outline .small {
  top: 4%;
  height: 27%;
}
body .container .fridge-outline .big {
  top: 43%;
  height: 52%;
}
body .container .inside-fridge .fridge-leg {
  bottom: -14px;
  width: 20px;
  height: 10px;
  background-color: darkslateblue;
  border-radius: 5px;
}
body .container .inside-fridge .left {
  left: 25%;
}
body .container .inside-fridge .right {
  right: 25%;
}

.grid-container {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-template-rows: 15% 85%;
  gap: 0px 0px;
  grid-template-areas:
    "title title title"
    "kitchen kitchen kitchen";
}
.kitchen {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-template-rows: 1fr;
  gap: 0px 0px;
  grid-template-areas:
    "frigo add-aliment recap";
  grid-area: kitchen;
  background-image: url('https://www.frigomagic.com/wp-content/uploads/2020/05/BG-scaled-e1589552182742.jpg');
  background-size: cover;
  height: 130%;
} 

.title {
  grid-area: title; 
  background-color: rgb(199, 199, 199);
}

.frigo {
  grid-area: frigo;
  text-align: center;
  padding-top: 60px;
  height: 100%;
}

.add-aliment {
  grid-area: add-aliment;
  text-align:center;
  padding-top: 20px;
  height: 100%;
}

.recap {
  grid-area: recap;
  text-align: center; 
  height: 100%;
  padding-top: 20px;
}
</style>