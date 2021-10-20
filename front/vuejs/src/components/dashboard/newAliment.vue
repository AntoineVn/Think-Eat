<template>
  <div>
<!-- Boutton d'ajout d'aliment -->
    <button  class="ui animated button btn-block" tabindex="0" v-on:click="openForm(); getUser(); getCategories()" v-show="!isCreating">
       <div class="visible content">Add a new food</div><div class="hidden content"><i class='plus icon'></i></div>
    </button>
<!-- Choix de la méthode Scan ou Manuel -->
    
  <div class="content" v-show="choice" style="width: 500px">
    <div class="fluid ui two buttons" >
      <button class="ui primary button" v-on:click="openFormScan()" >Scan</button>
        <div class="or"></div>
      <button class="ui positive button" v-on:click="openFormManual()" >Manual</button>
    </div>

    <div >
      <button class="fluid ui button" v-on:click="closeForm()">
      Cancel
    </button>
    </div>
  </div> 
<!-- Formulaire d'ajout via le scan -->
    <div class='ui centered card' v-show="FormScan" style="width: 500px">
      <div class='content' style="width: 500px">
        <div class='ui form'>
          <div class='field'>
            <div v-show="!scannerActive" class="level-item">
              <a @click="start" class="button">Start Scanner</a>
            </div>

            <div v-show="scannerActive" class="level-item">
              <a @click="stop" class="button">Retry</a>
            </div>

            <label for="name">Ean</label>
            <input type="text" id="named"><br> <br>

            <div id="videoWindow" class="video"></div>

            <form action="">

              <label>Name</label> <!-- Nom du produit scanné qui s'affichera automatiquement dans le input -->
                <input v-model="alimentName" id="name_product" required minlength="4" value="" type='text'><br><br>

              <div id="img"> <!-- Photo du produit  qui s'affichera automatiquement dans la div -- le lien se mettra dans le input caché -->
                <input type="hidden" v-model="alimentPhoto" id="img_product">
              </div>

              <label>Expiry date</label> <!-- Date d'expiration remplis via le calendrier -->               
                <input type="date" v-model="alimentDate" required minlength="4" :min="getDate()"><br><br>

              <label>Quantity</label>   <!-- Quantité remplis via le input -->               
                <input type="number" v-model="alimentQuantity" required minlength="4" min="1"><br><br>

              <label>Price</label>  <!-- Prix remplis via le input -->      
                <input type="number" v-model="alimentPrice" min="0" step=1 /><br><br>

              <label>Category</label>  <!-- Catégorie via le select -->      
                <select  v-model="alimentCat" required minlength="4" >
                    <option value="">--Please choose your category--</option>
                    <option v-for="cat in categories" :key="cat.id" v-bind:value="cat.id">{{cat.name}}</option>
                </select>

            </form>

          </div>  

            <div class='ui two buttons'>
              <button class='ui basic green button' v-on:click="sendForm()">
                Create
              </button>
              <button class='ui basic black button' v-on:click="closeFormScan()">
                Cancel
              </button>
            </div>

        </div>
      </div>
    </div>
<!-- Formulaire d'ajout en manuel -->
    <div class='ui centered card' v-show="FormManual" style="width: 500px">
      <div class='content' style="width: 500px">
        <div class='ui form'>
          <div class='field'>
            <form action="">

              <label>Name</label> <!-- Nom du produit remplis par l'utilisateur -->
                <input v-model="alimentName" id="name_product" required minlength="4" type='text'><br><br>

              <label>Photo</label>  <!-- l'Utilisateur chosira une image à partir de ses fichiers -->
                <input type="file" src="" @change="previewFiles" accept="image/png, image/jpeg"><br><br>

              <label>Expiry date</label>  <!-- Date d'expiration remplis via le calendrier -->
                <input type="date" v-model="alimentDate" required minlength="4" :min="getDate()"><br><br>
              
              <label>Quantity</label> <!-- Quantité remplis via le input -->
                <input type="number" v-model="alimentQuantity" required minlength="4" min="1"><br><br>

              <label>Price</label>  <!-- Prix remplis via le input -->    
                <input type="number" v-model="alimentPrice" min="0" required minlength="4" step=1 /><br><br>

              <label>Category</label> <!-- Catégorie via le select -->  
                <select  v-model="alimentCat" required minlength="4" >
                    <option value="">--Please choose your category--</option>
                    <option v-for="cat in categories" :key="cat.id" v-bind:value="cat.id">{{cat.name}}</option>
                </select>

            </form>

          </div>  

            <div class='ui two buttons'>
              <button class='ui basic green button' v-on:click="sendForm()">
                Create
              </button>
              <button class='ui basic black button' v-on:click="closeFormManual()">
                Cancel
              </button>
            </div>

        </div>
      </div>
    </div>
  </div>
  
</template>

<script>

import { mapState } from 'vuex'; /* Store */
import Quagga from '@ericblade/quagga2' /* Plug in Codebarre Reader */

export default {

  data() {
    return {
      isCreating: false, /* data qui permet d'afficher le premier formulaire */
      choice: false,  /* Data qui affiche les methodes possibles */
      FormScan: false,  /* Data qui affiche la méthode scan */
      FormManual: false,  /* Data qui affiche la méthode manuel */
      scannerActive: false, /* data pour activé la cam */
      barcode: '',
      categories : "",
      alimentName: '',
      alimentDate: '',
      alimentPhoto:'',
      alimentQuantity:'',
      alimentPrice: '',
      alimentCat:'',
      alimentImg:'',
      api_name:'', 
      tokenvar:"",
      id:-1,
    };
  },
  /* beforeMount() {
    this.getUser()
  }, */
  computed : mapState({
    storeuser : s => s.user
  }),

  methods: {
    getDate(){
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        console.log(today)
        return today
    },

    previewFiles(event) {
        this.alimentPhoto = event.target.files[0].name
    },
    /* Ouvre le formulaire du choix de la methode */
    openForm() {
      this.isCreating = true
      this.choice = true
    },
    /* Ouvre la methode Scan */
    openFormScan() {
      this.FormScan = true
      this.choice = false
    },
    /* Ouvre la methode manuel */
    openFormManual() {
      this.FormManual = true
      this.choice = false
    },
    /* Ferme le formulaire principal */
    closeForm() {
      this.isCreating = false
      this.choice = false
    },
    /* Ferme la méthode scanner et revien sur le choix de la methode */
    closeFormScan() {
      this.FormScan = false
      this.choice = true
    },
    /* Ferme la méthode manuel et revien sur le choix de la methode */
    closeFormManual() {
      this.FormManual = false
      this.choice = true
    },
    /* Requete dans le back pour récuperer toutes les catégories */
    async getCategories () {
      const request = new Request(
          'http://127.0.0.1:8000/api/getCategories ',
          {
          method: "GET",
          }
      )
      const res = await fetch(request)
      const post = await res.json()
      this.categories = post.categories       
    },
    
    async sendForm() {
      if (this.alimentName) {
        await this.postAliment(this.alimentName)
        this.$store.commit('addAliment', {
          name: this.alimentName,
          photo : this.alimentPhoto,
          expiry_date: this.alimentDate,
          quantity: this.alimentQuantity,
          price: this.alimentPrice,
          category_id: this.alimentCat,   
          id: this.id,          
        });
        this.alimentName='',
        this.alimentPhoto='',
        this.alimentDate='',
        this.alimentQuantity='',
        this.alimentPrice='',
        this.alimentCat='',
        this.id = -1,
        this.isCreating = false,
        this.FormScan = false,
        this.FormManual = false
      }
    },

    async postAliment() {
      
      console.log("token = " + this.tokenvar)

      const request = new Request (
        'http://127.0.0.1:8000/api/addAliment' ,
        {
          method: "POST",
          headers: {'Authorization': 'Bearer '+ this.tokenvar},
          body : JSON.stringify({"name": this.alimentName, "image" : this.alimentPhoto, "expire_date": this.alimentDate, "quantity": this.alimentQuantity, "price": this.alimentPrice, "category_id" : this.alimentCat, "user_id":this.storeuser[0].id, "status":"0"})
        }
      );

      const res = await fetch(request)
      const post = await res.json()
      this.id = post.id
      alert("Aliment added")
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
    },

    start() {
      
      this.scannerActive = true; /* Emplacement pour la webcam activé */

      /* Initialisation du plugin pour la détection du code barre */
      Quagga.init({ 
        inputStream: {
          name: "live",
          type: "LiveStream",
          target: document.querySelector("#videoWindow"),
          constraints: {
            width: '250',
            height: '250',
            facingMode: "environment"
          },
        },
        decoder: {
          readers: ['ean_reader',]
        },

      }, function(err) {
        if (err) {
          console.log(err);
          return;
        }
        /* Webcam Activé */
        console.log("initialization complete");
        Quagga.start();
        
      });
      
      var ref = this 
      /* Si un code Barre est détecté */
      Quagga.onDetected(function(result)  {
        console.log('detected: ', result.codeResult.code) 
        var barcode = result.codeResult.code;    
        document.getElementById('named').value = barcode;
        Quagga.stop()
        this.scannerActive = false,
        console.log(this.scannerActive)   
        ref.datareturn(barcode);
      })
    },
    /* Fonction qui envoie une requête api avec le codebarre scanné */
    async datareturn($barcode) {
      const url = 'https://world.openfoodfacts.org/api/v0/product/' + $barcode + '.json'
      const request_aliment = new Request ( url,
        {
        method: "GET",
        });
        // Reponse de l'API
        const res = await fetch(request_aliment)
        const post = await res.json()

        console.log(post.product.image_url, post.product.generic_name, post.product.nutriscore_grade)
/* Enregistre la réponse de la requete dans les variables de l'ajout du produit */
        this.alimentName = post.product.product_name
        this.alimentPhoto = post.product.image_url
/* Les réponses de la requete sont retransmis dans  */
        document.getElementById('name_product').value = this.alimentName
        document.getElementById('img_product').value = post.product.image_url

        var oImg = document.createElement("img")
        oImg.setAttribute('src', post.product.image_url)
        oImg.setAttribute('alt', "Not found")
        oImg.setAttribute('height', '50px')
        oImg.setAttribute('width', '50px')
        document.getElementById('img').appendChild(oImg)
    },
    /* Arrêt de la webcam */
    stop() {
      this.scannerActive = false,
      Quagga.stop()
    },
  }                                                                                                                          ,    
};

</script>

<style scoped>

.ui centered card {
  width: 500px;

}

.ui newaliment {
  width: 500px;
}


</style>

