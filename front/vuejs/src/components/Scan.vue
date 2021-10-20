<template>
  <div>
    {{ this.barcode }}
    <div v-if="barcode" class="level">
      <div class="level-item has-text-centered">
        <div>
          <p class="title" type="number">{{ this.barcode }}</p>
        </div>
      </div>
    </div>
    <br>
      <label for="name">Ean</label>
      <input type="text" id="named">
    <nav class="level" role="navigation" aria-label="main navigation">
      <div v-show="!scannerActive" class="level-item">
        <a @click="start" class="button">Start Scanner</a>
      </div>

      <div v-show="scannerActive" class="level-item">
        <a @click="stop" class="button">Stop Scanner</a>
      </div>
    </nav>
    <div id="videoWindow" class="video"></div>
  </div>
</template>

<script>
import Quagga from '@ericblade/quagga2'
export default {
  name: 'Scan',
  data() {
    return {
      scannerActive: false,
      barcode: '',

    }
  },
  methods: {
    
    start() {
      this.scannerActive = true;

      Quagga.init({
        inputStream: {
          name: "live",
          type: "LiveStream",
          target: document.querySelector("#videoWindow"),
          constraints: {
            width: '450',
            height: '400',
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
        console.log("initialization complete");
        Quagga.start();
        
      });
      var ref = this 
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

    async datareturn($barcode) {
      const url = 'https://world.openfoodfacts.org/api/v0/product/' + $barcode + '.json'
      const request_aliment = new Request ( url,
        {
        method: "GET",
        });

        // Reponse de l'API
        
        const res = await fetch(request_aliment)
        const post = await res.json()
        //console.log(post.product.image_url, post.product.generic_name, post.product.nutriscore_grade)
        document.getElementById('name_product').value = post.product.generic_name;
        document.getElementById('img_product').value = post.product.image_url;
        
        var oImg = document.createElement("img");
        oImg.setAttribute('src', post.product.image_url);
        oImg.setAttribute('alt', 'Not found');
        oImg.setAttribute('height', '100px');
        oImg.setAttribute('width', '100px');
        document.getElementById("img").appendChild(oImg);
    },
      
    stop() {
      this.scannerActive = false,
      Quagga.stop()
    },
  }                                                                                                                          ,    
};

</script>

<style scoped>




</style>

