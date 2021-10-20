<template>
<div>
    <h1 class="ui centered header"> Who are we ? </h1>

    <div class="ui centered card">
        <div class="image">
            <img src="https://zupimages.net/up/21/14/pcmx.jpg">
        </div>
        <div class="content">
            <a class="header ui centered">The Think eat team </a>
        </div>
    </div>

    <div class="ui items">
    <div class="item">
        <a class="ui tiny image">
        <img src="https://zupimages.net/up/21/14/npyc.jpg">
        </a>
        <div class="content"><i class="gamepad icon"></i>
            <a class="header">Alexandre de Framond</a>
            <div class="description">
                <p>Alexandre de Framond is the <a>project manager</a>. He is also a full-stack developer. He worked mainly on the back of the project, but also on the front</p> 
                <div class="extra"> Contact : alexandre.de-framond@epitech.eu</div>
            </div>
        </div>
    </div>
    <br>
    <div class="item">
        <a class="ui tiny image">
        <img src="https://zupimages.net/up/21/14/vk2m.jpg">
        </a>
        <div class="content"><i class="fire icon"></i>
        <a class="header">Yvon Houariki</a>
        <div class="description">
            <p>Yvon Houariki is the community manager, the food supplier but firstly one of the <a>full-stack developer</a> of the team.  He worked mainly on the front of the project.</p>
            <div class="extra"> Contact : yvon.houriki@epitech.eu</div>
        </div>
        </div>
    </div>
    <br>
    <div class="item">
        <a class="ui tiny image">
        <img src="https://zupimages.net/up/21/14/05rr.jpg">
        </a>
        <div class="content"><i class="star icon"></i>
        <a class="header">Antoine Tat</a>
        <div class="description">
            <p>Antoine Tat is one of the <a>full-stack developer</a> of the team.  He worked mainly on the front of the project. Professional of the scan.</p>
            <div class="extra"> Contact : antoine.tata@epitech.eu</div>
        </div>
        </div>
    </div>
    <br>
    <div class="item">
        <a class="ui tiny image">
        <img src="https://zupimages.net/up/21/14/4m5c.jpg">
        </a>
        <div class="content"><i class="heart icon"></i>
        <a class="header">Myriam Abdelli</a>
        <div class="description">
            <p>Myriam Abdelli is one of the <a>full-stack developer</a> of the team.  She worked mainly on the front of the project, to link it with the back.</p>
            <div class="extra"> Contact : myriam.abdelli@epitech.eu</div>
        </div>
        </div>
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


