 <template>
    <div class="feed">
        <div class="ui grid" >
            <div class="ui centered row">
                <div class="six wide column">
                    <div class="ui centered">
                        <div class="ui celled grid">
                            <div class="row ui middle aligned selection list" v-for="aliment in storealiments" :key="aliment.id">
                                <div v-if="aliment.status==1" class="item">
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
                                    <div v-for="user in storeallusers" :key="user.id"> 
                                        <div v-if="aliment.user_id == user.id">
                                           <!--  <div v-if="storeuser[0]"> -->
                                                <div v-if="storeuser[0]&& aliment.user_id == storeuser[0].id" class="right floated content">
                                                    <div class="ui icon button" v-on:click="updateStatus(aliment.id, 0)" data-tooltip="Remove your product from the feed"><i class="red undo icon"></i></div>
                                                </div>
                                                <div v-else-if="!storeuser[0] || aliment.user_id != storeuser[0].id" class="right floated content">  
                                                    Located in {{user.location}} - 
                                                    Published by {{user.nickname}} 
                                                    <a :href="'mailto:' + user.email">  <button class="ui icon button"><i class="blue envelope icon"></i> </button></a>
                                                    <div class="ui animated button" tabindex="0">
                                                        <div class="visible content"><i class="green phone icon" style="margin-left:1.5em; margin-right:1.5em"> </i> </div>
                                                        <div class="hidden content">
                                                            {{user.phone}}
                                                        </div>
                                                    </div>
                                                </div>
                                           <!--  </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> 


<script>
import { mapState } from 'vuex';
export default {
    name : 'feed',
    data() {
      return {
            categories:"",
            tokenvar:"",

      };
    },
    beforeMount() {
        this.getUser();
        this.getUsers();
         this.getAliments(); 
         this.getCategories();
    },
    computed : mapState({
      storealiments: s => s.kitchen,
      storeuser : s => s.user,
      storeallusers : s => s.allusers
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
                }
            );
            const res = await fetch(request);
            const post = await res.json();  
            post.aliment.forEach(aliment => {
            if (this.$store.state.kitchen.find(comp => comp.id == aliment.id) == undefined)
            {
                this.$store.commit('addAliment', {
                    id: aliment.id,
                    name: aliment.name,
                    image: aliment.image,
                    expire_date : aliment.expire_date,
                    quantity : aliment.quantity,
                    price : aliment.price,
                    status : aliment.status,
                    category_id : aliment.category_id,
                    user_id : aliment.user_id 
                    });
                }
            });         
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
        async getUsers() {
            const request = new Request(
                'http://127.0.0.1:8000/api/getUsers',
                {
                method: "GET",
                }
            );
            const res = await fetch(request);
            const post = await res.json();
            post.users.forEach(user => {
            if (this.$store.state.allusers.find(comp => comp.id == user.id) == undefined){
                this.$store.commit('addallUsers', user);
                }
            })
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
    }

}
</script>

<style scoped>
.feed {
    background-image: url('https://www.frigomagic.com/wp-content/uploads/2020/05/BG-scaled-e1589552182742.jpg');
    background-size: cover;
}

.row.ui.middle.aligned.selection.list {
    background-color: white !important;
}
</style>