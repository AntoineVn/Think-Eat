<template>
  <div class="home">
    <div class="home_header">
      <h1></h1>
    </div>
    <div class="homepage">
      <div class="photohomepage1">
        <img src="https://i.pinimg.com/originals/9d/b7/25/9db725f92243a00b6f62f213249699a4.jpg" alt="photo1" style="width:60%">
      </div>
        
      <div class="titre1">
          <p>
            <h1>Why should you use THINK EAT?</h1> 
            <h2><ul>Think.Eat in an app which will help you to avoid waste by eating before expiration date</ul></h2>
            <h2><ul>More than an app, it's an eco-friendly approach !  </ul></h2>
            <h2><ul></ul></h2>             
      </div>

      <div class="photohomepage2">
        <img src="https://st3.depositphotos.com/3899029/15308/v/600/depositphotos_153088952-stock-illustration-man-and-woman-with-fridge.jpg" alt="photo2" style="width:70%;">
      </div>

      <div class="titre2">
          <p>
            <h1>What you can do with THINK EAT</h1>
              <h2><ul>Follow the expiration date of your food products</ul></h2>
              <h2><ul>Manage your food with a personal interface</ul></h2>
              <h2><ul>Follow your eaten product</ul></h2>
              <h2><ul>Give to other users your leftover food away</ul></h2>          
      </div>

      <div class="photohomepage3">
        <img src="https://img.freepik.com/free-vector/woman-thinking-snacking-fridge-with-unhealthy-food-people-eating-night-diet-vector-concept_53562-5042.jpg?size=626&ext=jpg" alt="photo3" style="width:70%;">
      </div>

      <div class="titre3">
          <p>
            <h1>How to use THINK EAT ?</h1>
              <h2><ul>1) Add your food products by scanning the price tag or by hand</ul></h2>
              <h2><ul>2) Enter the expiration date</ul></h2>
              <h2><ul>3) Choose one of the 7 categories for your product</ul></h2>
              <h2><ul>That's it ! You just need to manage your food from you personnal space</ul></h2>
      </div>

      <div class="photohomepage4">
        <img src="https://thumbs.dreamstime.com/b/affiche-avec-le-texte-qu-aucune-nourriture-n-gaspill%C3%A9-d%C3%A9charge-de-bande-dessin%C3%A9e-des-d%C3%A9chets-illustration-pour-traitement-134189300.jpg" alt="phot04" style="width:80%;">
      </div>

      <div class="titre4">
          <p>
            <h1>Stay away from the WASTE</h1>
              <h2><ul>Let's fight together against food waste !</ul></h2>
              <h2><ul>Think-it ... there is Think.Eat!</ul></h2>
      </div>      
   
      <div class="video">
        <section><iframe width="800" height="600" src="https://www.youtube.com/embed/Obb3roBcsVM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></section>
      </div>

      <div class="titre5">
          <p>
            <h1>Video PREVIEW</h1>
              <h2><ul>Click-on the video to have a preview of the app</ul></h2>
      </div> 
    </div>
    <footer class="bg-light text-center text-lg-start">
      <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
          © 2021 Copyright:ThinkEat
      </div>
    </footer>
  </div>
</template>

<script>
import Header from '@/components/Header.vue'

export default {
  name: 'home',
  components: {
      Header
  }
}

</script>

<style scoped>
/* .home_header {
  background-color: #33ccff;
  text-align: center;
  margin: 30px;
  height: 50px;
  
  
} */

.homepage{
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 1fr 1fr 1fr 1fr;

}

.photohomepage1{
  grid-column: 1;
  grid-row: 1;
  position: relative;
  top:0%;
  left: 20%;
  margin-top: 50px;
}

.photohomepage2{
  grid-column: 2;
  grid-row: 2;
  position: relative;
  left: 17%;
  margin-top: 100px;
}

.photohomepage3{
  grid-column: 1;
  grid-row: 3;
  position: relative;
  top:10%;
  left: 20%;
}

.photohomepage4{
  grid-column: 2;
  grid-row: 4;
  position: relative;
  top:0%;
  left: 10%;
}

.video{
text-align: center;
margin: 20px;
}

.titre1{
  grid-column: 2;
  grid-row: 1;
  display: grid;
  text-align: center;
  background-color:#e6e6e6;
}

.titre2{
  grid-column: 1;
  grid-row: 2;
  display: grid;
  text-align: center;
  background-color:#ffaa80;
}

.titre3{
  grid-column: 2;
  grid-row: 3;
  display: grid;
  text-align: center;
  background-color:#99e699;
}

.titre4{
  grid-column: 1;
  grid-row: 4;
  display: grid;
  text-align: center;
  background-color:#e6e6e6;
}

.titre5{
  grid-column: 2;
  grid-row: 5;
  display: grid;
  text-align: center;
    background-color:#ffaa80;
}

</style>