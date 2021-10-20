 <template>
    <div class="profile">
        <div class='ui centered card'>
            <div class='content'>
                <div v-if="(this.storeuser[0])" class='ui form'>
                    <div class='field'>
                        <form action="" class= "ui form">
                            <div class="field">
                                <label>Firstname</label>
                                <input v-model="usernewData.firstname" type='text'>
                            </div>
                            <div class="field">
                                <label>Lastname</label>
                                <input v-model="usernewData.lastname" type='text'>
                            </div>
                            <div class="field">
                                <label>Nickname</label>
                                <input v-model="usernewData.nickname" type='text'>
                            </div>
                            <div class="field">
                                <label>Email</label>
                                <input v-model="usernewData.email" type='text'>
                            </div>
                            <div class="field">
                                <label>Phone</label>
                                <input v-model="usernewData.phone" type='text'>
                            </div>
                            <div class="field">
                                <label>Location</label>
                                <input v-model="usernewData.location" type='text'>
                            </div>
                            <div class="field">
                                <label>Password</label>
                                <input v-model="newPassword" type='password'>
                            </div>
                            <div class="field">
                                <label>Password confirmation</label>
                                <input v-model="newPassword_confirmation" type='password'>
                            </div>
                            
                            
                        </form>
                    </div>
                    <div class='ui two buttons'>
                        <button class='ui basic green button' v-on:click="updateUser()">
                        Update
                        </button>
                        <button class='ui basic black button' v-on:click="usernewData=useroldData">
                        Restore
                        </button>
                    </div>
                     <a v-on:click="deleteUser()"> Delete my account </a>
                </div>
            </div>
        </div>
    </div>
</template> 


<script>
import { mapState } from 'vuex';
export default {
    name : 'description',
    props: ['id'],
    data() {
      return {
            usernewData : {},
            useroldData : {},
            categories:"",
            categorypagename : "",
            tokenvar:"",
            newPassword : "",
            newPassword_confirmation : ""

      };
    },
    beforeMount() {
        this.getUser();
        Object.assign(this.usernewData, this.storeuser[0])
        Object.assign(this.useroldData, this.storeuser[0])
    },
    computed : mapState({
      storeuser : s => s.user
    }),
    methods : {
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
                console.log(this.storeuser[0])
                if (this.$store.state.user.find(comp => comp.id === post.user.id) == undefined){
                    this.$store.commit('addUser', post.user);
                }
                console.log(this.storeuser[0])
                Object.assign(this.usernewData, this.storeuser[0])
                Object.assign(this.useroldData, this.storeuser[0])
            }
            else{
                this.$router.push("/")
            }
        },
        async updateUser() {
            console.log(this.newPassword)
            if (this.newPassword === ""){
                console.log("pas de mot de passe à update")
            const request = new Request (
                'http://127.0.0.1:8000/api/updateUser/' ,
                {
                method: "PATCH",
                headers: {'Authorization': 'Bearer '+ this.tokenvar},
                body : JSON.stringify({"id":this.storeuser[0].id, "firstname": this.usernewData.firstname, "lastname" : this.usernewData.lastname, 
                "nickname": this.usernewData.nickname, "email": this.usernewData.email, "phone": this.usernewData.phone, 
                "location" : this.usernewData.location})
                }
            )
            const res = await fetch(request)
            const post = await res.json();
            }
            else{
                console.log("mot de passe à update")
                const request = new Request (
                    'http://127.0.0.1:8000/api/updateUser/' ,
                    {
                    method: "PATCH",
                    headers: {'Authorization': 'Bearer '+ this.tokenvar},
                    body : JSON.stringify({"id":this.storeuser[0].id, "firstname": this.usernewData.firstname, "lastname" : this.usernewData.lastname, 
                    "nickname": this.usernewData.nickname, "email": this.usernewData.email, "phone": this.usernewData.phone, 
                    "location" : this.usernewData.location, "password" : this.newPassword, "password_confirmation" : this.newPassword_confirmation})
                    }
                )
            const res = await fetch(request)
            const post = await res.json();
            }
            this.$store.commit('updateUser', this.usernewData);
        },
        async deleteUser() {
            const request = new Request (
                'http://127.0.0.1:8000/api/deleteUser/' ,
                {
                method: "DELETE",
                headers: {'Authorization': 'Bearer '+ this.tokenvar},
                body : JSON.stringify({"id":this.storeuser[0].id})
                }
            )
            const res = await fetch(request)
            const post = await res.json()
            this.$store.commit('deleteUser', this.storeuser[0].id)            
            this.$cookies.remove('token')
            this.$store.commit('clearAll')
            this.$router.push("/")
        },
    }
}
</script>

<style scoped>
.profile {
    background-image: url('https://www.frigomagic.com/wp-content/uploads/2020/05/BG-scaled-e1589552182742.jpg');
    background-size: cover;
    height: 825px;
}
</style>