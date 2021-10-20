<template>
<div class="recap_processing">
    <div class="ui grid">
        <div class="one wide column"></div>

        <div class="seven wide column"> <!-- Historique produits mangés -->
            <div class="ui celled grid">
                <div class="row ui selection list" v-for="aliment in storealiments" :key="aliment.id">
                    <div v-if="aliment.status==0 && aliment.user_id==storeuser[0].id" class="item">
                            <div class="ui image">
                                <img v-if="aliment.image" class="ui avatar image" :v-bind:src="aliment.image" alt="food img">
                                <i v-else class="leaf icon"></i>
                            </div>
                            <div class="content">
                                <div class="header">{{aliment.name}} </div>
                                <div v-if="datediff(parseDate(getDate()), parseDate(aliment.expire_date)) > 7" class="description" style="color:green">{{datediff(parseDate(getDate()), parseDate(aliment.expire_date))}} days left </div>
                                <div v-else-if="datediff(parseDate(getDate()), parseDate(aliment.expire_date)) == 1" class="description" style="color:orange"> 1 day left </div>
                                <div v-else-if="datediff(parseDate(getDate()), parseDate(aliment.expire_date)) <= 7 && datediff(parseDate(getDate()), parseDate(aliment.expire_date)) >= 0" class="description" style="color:orange">{{datediff(parseDate(getDate()), parseDate(aliment.expire_date))}} days left </div>
                                <div v-else class="description" style="color:red"> Expired </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="seven wide column"> <!-- Graphes  -->
        </div>

        <div class="one wide column"></div>
    
    </div>
</div>

</template>

<script >
import { mapState } from 'vuex';
export default {
    name : 'recap',
    data() {
      return {
            tokenvar:"",
            data:[],
            array:[],
            processing:0,
            chartOptions: {
                chart: {type: 'pie'},
                title: {text: 'Your stat'},
                series: [{data: [{y:2}]}]
            }
      };
    },
    mounted() {
        
        },
    beforeMount() {
        this.getUser();
        this.getAliments(); 
        this.getCategories();
        
    },
    computed : mapState({
      storealiments: s => s.kitchen,
      storeuser : s => s.user
    }),
    methods : {
        async getCategories() {
            const request = new Request(
                'http://127.0.0.1:8000/api/getCategories ',
            {
                method: "GET",
            }
        );
        const res = await fetch(request);
        const post = await res.json();
        this.categories = post.categories; 
        }, 
        async getAliments() {
            const request = new Request(
                'http://127.0.0.1:8000/api/getAliments/0',
                {
                method: "GET",
                headers: {'Authorization': 'Bearer '+ this.tokenvar}
                }
            );
            const res = await fetch(request);
            const post = await res.json();  
            post.aliment.forEach(aliment => {
            if (this.$store.state.kitchen.find(comp => comp.id == aliment.id) == undefined)
            {
                this.$store.commit('addAliment', aliment);
                }
            }); 
            this.processing = this.getDataProcessing()  

        }, 
        getDate(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            return today
        },
        parseDate(str) {
            var mdy = str.split('-');
            return new Date(mdy[0], mdy[1]-1, mdy[2]); 
        },
        datediff(first, second) {
            return Math.round((second-first)/(1000*60*60*24));
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
        async updateStatus(id, status) {
            var aliment = this.storealiments.find(alim => alim.id == id)
            const request = new Request (
                'http://127.0.0.1:8000/api/updateAliment/' ,
                {
                    method: "PATCH",
                headers: {'Authorization': 'Bearer '+ this.tokenvar},
                body : JSON.stringify({"id":id, "status":status, "name": aliment.name, "image" : aliment.image, "expire_date": aliment.expire_date, 
                "quantity": aliment.quantity, "price": aliment.price, "category_id" : aliment.category_id})
                } 
            )
            const res = await fetch(request)
            const post = await res.json();
            this.$store.commit('updateStatus', {id,status})
            
        },
        getDataProcessing(){
            this.array = this.$store.state.kitchen
            var count = 0
            this.array.forEach(aliment => {
                if(aliment.status == 0 && aliment.user_id == this.storeuser[0].id){
                  count = count+1  
                } 
            })
            return count
        },
    }

}
</script>

<style scoped>
.recap_processing {
    background-image: url('https://www.frigomagic.com/wp-content/uploads/2020/05/BG-scaled-e1589552182742.jpg');
    background-size: cover;
    height: 840px;
}

.row.ui.selection.list {
    background-color: white !important;
}
</style>