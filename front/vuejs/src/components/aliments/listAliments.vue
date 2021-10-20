 <template>
    <div class="list_aliment">
        <!-- <br/>
        <div class="">
             <router-link :to="{name : 'Dashboard'}" ><h2><i class="long arrow alternate left icon"></i> Back to dashboard </h2> <br/> </router-link>
        </div> -->
        <div class="ui grid" >
        
            <div class="ui centered row">
                <div class="six wide column">
                    <div class="ui centered">
                       {{categorypagename}}
                        <div class="ui celled grid">
                            <div v-if="count == 0">
                                You have no aliments in this categorie !
                            </div>
                            <div class="row ui middle aligned selection list" v-for="aliment in storealiments" :key="aliment.id">
                                <div v-if="aliment.status==0 && aliment.category_id==id && aliment.user_id==storeuser[0].id" class="item">
                                    
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
                                        <div class="right floated content">
                                            <button class="ui icon button" v-on:click="updateStatus(aliment.id, 3)" data-tooltip="Click here if you ate your food (yay, good job)"><i class="green smile icon"></i></button>
                                            <button class="ui icon button" v-on:click="updateStatus(aliment.id, 1)" data-tooltip="Click here to give your food and make it appear in the feed"><i class="purple gift icon"></i></button>
                                            <button class="ui icon button" v-on:click="updateStatus(aliment.id, 2)" data-tooltip="Click here if you wasted your food (shame on you)"><i class="red trash icon"></i></button>
                                            <button class="ui icon button" v-on:click="Object.assign(alimentnewData,aliment),Object.assign(alimentoldData,aliment), openForm()" data-tooltip="Click here to edit your food"><i class="black edit icon"></i></button> 
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="three wide column">
                    <div class='ui centered card' v-show="isCreating">
                        <div class='content'>
                            <div class='ui form'>
                                <div class='field'>
                                    <form action="" class= "ui form">
                                        <div class="field">
                                            <label>Name</label>
                                            <input v-model="alimentnewData.name" type='text'>
                                        </div>
                                        <div class="field">
                                            <label>Photo</label>
                                            <input type="file"  @change="previewFiles" accept="image/png, image/jpeg">
                                        </div>
                                        <div class="field">
                                            <label>Expiry date</label>
                                            <input type="date" v-model="alimentnewData.expire_date" min="">
                                        </div>
                                        <div class="field">
                                            <label>Quantity</label>
                                            <input type="number" v-model="alimentnewData.quantity" min="1">
                                        </div>
                                        <div class="field">
                                            <label>Price</label>
                                            <input type="number" v-model="alimentnewData.price" min="0" step=0.01 />
                                        </div>
                                        <div class="field">
                                            <label>Category</label>
                                            <select  v-model="alimentnewData.category_id">
                                                <option value="">--Food category--</option>
                                                <option v-for="cat in categories" :key="cat.id" v-bind:value="cat.id" v-bind:selected="cat.id==alimentnewData.category_id">{{cat.name}}</option>
                                            </select>
                                        </div>
<!--                                        <div class="field">
                                            <label>Status</label>
                                            <select  v-model="alimentnewData.status">
                                                <option value="">--Please choose your category--</option>
                                                <option v-bind:value="0" v-bind:selected="alimentnewData.status==0">To be eaten</option>
                                                <option v-bind:value="1" v-bind:selected="alimentnewData.status==1">To give</option>
                                                <option v-bind:value="2" v-bind:selected="alimentnewData.status==2">Waste</option>
                                            </select>
                                        </div> -->
                                    </form>
                                </div>
                                <div class='ui two buttons'>
                                    <button class='ui basic green button' v-on:click="updateAliment(), closeForm()">
                                    Update
                                    </button>
                                    <button class='ui basic black button' v-on:click="alimentnewData=alimentoldData, closeForm()">
                                    Cancel
                                    </button>
                                </div>
                                <br>
                                <br>
                                <div class="ui icon button" v-on:click="deleteAliment(alimentnewData.id); closeForm()"  data-tooltip="By clicking on this, you won't be able to get back this food and it won't appear in statistics" data-variation="basic">
                                    <p style="color:red ; font-size:0.75em"> Delete this product </p>
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
    name : 'description',
    props: ['id', 'name'],
    data() {
      return {
            isCreating: false,
            alimentnewData : {},
            alimentoldData : {},
            categories:"",
            categorypagename : "",
            tokenvar:"",
            count: 0,
      };
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
        previewFiles(event) {
            this.alimentnewData.image = event.target.files[0].name;
        },
        openForm() {
            this.isCreating = true;
        },
        closeForm() {
            this.isCreating = false;
        },
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
        /* post.forEach(cat => {
                if (cat.id == this.id)
                {
                    this.categorypagename = cat.name
                }
            });*/
            if(this.id == 1)
                this.categorypagename = "Meat, fish and eggs";
            if(this.id == 2)
                this.categorypagename = "Milk products";
            if(this.id == 3)
                this.categorypagename = "Cooking oils and fats";
            if(this.id == 4)
                this.categorypagename = "Vegetables, legumes and fruits";
            if(this.id == 5)
                this.categorypagename = "Cereals, cereal-based products and grains";
            if(this.id == 6)
                this.categorypagename = "Sugar and sweet products";
            if(this.id == 7)
                this.categorypagename = "Beverage";
        }, 
        async getAliments() {
            const request = new Request(
                'http://127.0.0.1:8000/api/getAliments/' + this.id,
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
                if (aliment.user_id == this.storeuser[0].id && aliment.status == 0) {
                    this.count += 1;
                }
            });      
        }, 
        async updateAliment() {
            const request = new Request (
                'http://127.0.0.1:8000/api/updateAliment/' ,
                {
                method: "PATCH",
                headers: {'Authorization': 'Bearer '+ this.tokenvar},
                body : JSON.stringify({"id":this.alimentnewData.id, "name": this.alimentnewData.name, "image" : this.alimentnewData.image, "expire_date": this.alimentnewData.expire_date, 
                "quantity": this.alimentnewData.quantity, "price": this.alimentnewData.price, "category_id" : this.alimentnewData.category_id, "status":this.alimentnewData.status})
                }
            );
            const res = await fetch(request)
            const post = await res.json();
            this.$store.commit('updateAliment', {
                    id: this.alimentnewData.id,
                    name: this.alimentnewData.name,
                    image: this.alimentnewData.image,
                    expire_date : this.alimentnewData.expire_date,
                    quantity : this.alimentnewData.quantity,
                    price : this.alimentnewData.price,
                    status : this.alimentnewData.status,
                    category_id : this.alimentnewData.category_id,});
            
        },
        async deleteAliment(id) {
            const request = new Request (
                'http://127.0.0.1:8000/api/deleteAliment/',
                {
                method: "DELETE",
                body : JSON.stringify({"id":id}),
                headers: {'Authorization': 'Bearer ' + this.tokenvar}
                }
            );
            const res = await fetch(request)
            const post = await res.json();
            this.$store.commit('deleteAliment', id);
        },
        getDate(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            console.log(today)
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
                console.log(post.user)
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
    }

}
</script>

<style scoped>
.list_aliment {
    background-image: url('https://www.frigomagic.com/wp-content/uploads/2020/05/BG-scaled-e1589552182742.jpg');
    background-size: cover;
    height: 840px;
}

.ui.centered {
    background-color: white;
}
</style>