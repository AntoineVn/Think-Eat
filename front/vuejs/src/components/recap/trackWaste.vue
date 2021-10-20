<template>
<div class="recap_waste">
    <div class="ui grid">
        <div class="row">
            <div class="one wide column"></div>

            <div class="seven wide column"> <!-- Historique produits gaspillés -->
                <div class="ui celled grid">
                    <div class="row ui selection list" v-for="aliment in storealiments" :key="aliment.id">
                        <div v-if="aliment.status==2 && aliment.user_id==storeuser[0].id" class="item">
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
        <div class="row">
            <highcharts class="hc" :options="chartOptions" ref="chart"></highcharts>
            <highcharts class="hc" :options="chartOptions2" ref="chart"></highcharts>
            <highcharts class="hc" :options="chartOptionsLine" ref="chart"></highcharts>
        </div>
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
            inprocess:0,
            wasted:0,
            given:0,
            eaten:0,
            cat1:0,
            cat2:0,
            cat3:0,
            cat4:0,
            cat5:0,
            cat6:0,
            cat7:0,
            xmonth:[],
            chartOptions: {
                chart: {type: 'pie'},
                title: {text: 'Your stats'},
                series: [{data: []}]
            },
            chartOptions2: {
                chart: {type: 'pie'},
                title: {text: 'Your wastes by category'},
                series: [{data: []}]
            },
             chartOptionsLine: {
                chart: {type: 'spline'},
                xAxis: {categories: []},
                yAxis: [{title: {text: 'Money waste ($$)'}},{text: 'Number of waste',opposite: true,}],
                title: {text: 'Your money wastes !!'},
                series: [{yAxis: 0,name: 'Money waste',data: []}, {yAxis: 1,name: 'Number of waste',data: []}]
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
            this.getPieCount();
            this.getPieCatCount();
            this.getLineWasteChart();
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
        getDataCount(status){
            this.array = this.$store.state.kitchen
            var count = 0
            this.array.forEach(aliment => {
                if(aliment.status == status && aliment.user_id == this.storeuser[0].id){
                  count = count+1  
                } 
            })
            return count
        },
        getPieCount(){
            this.inprocess = this.getDataCount(0)
            if(this.inprocess != 0)
                this.chartOptions.series[0].data.push({y:this.inprocess, name:"In process", color:"#ffd966"})

            this.given = this.getDataCount(1)
            if(this.given != 0)
                this.chartOptions.series[0].data.push({y:this.given, name:"Given", color:"#d966ff"})

            this.wasted = this.getDataCount(2) 
            if(this.wasted != 0)
                this.chartOptions.series[0].data.push({y:this.wasted, name:"Wasted", color:"#ff6666"}) 

            this.eaten = this.getDataCount(3)
            if(this.eaten != 0)
                this.chartOptions.series[0].data.push({y:this.eaten, name:"Eaten", color:"#8cff66"})
        },
        getWasteCatCount(cat){
            this.array = this.$store.state.kitchen
            var count = 0
            this.array.forEach(aliment => {
                if(aliment.status == 2 && aliment.user_id == this.storeuser[0].id && aliment.category_id == cat){
                  count = count+1  
                } 
            })
            return count
        },
        getPieCatCount(){
            this.cat1 = this.getWasteCatCount(1)
            if(this.cat1 != 0)
                this.chartOptions2.series[0].data.push({y:this.cat1, name:"Meat, fish and eggs", color:"#ff6666"})

            this.cat2 = this.getWasteCatCount(2) 
            if(this.cat2 != 0)
            this.chartOptions2.series[0].data.push({y:this.cat2, name:"Milk products", color:"#ffb366"})

            this.cat3 = this.getWasteCatCount(3)
            if(this.cat3 != 0)
                this.chartOptions2.series[0].data.push({y:this.cat3, name:"Cooking oils and fats", color:"#ffff66"})
            
            this.cat4 = this.getWasteCatCount(4)
            if(this.cat4 != 0)
                this.chartOptions2.series[0].data.push({y:this.cat4, name:"Vegetables, legumes and fruits", color:"#b3ff66"})
            
            this.cat5 = this.getWasteCatCount(5)
            if(this.cat5 != 0)
                this.chartOptions2.series[0].data.push({y:this.cat5, name:"Cereals, cereal-based products and grains", color:"#66ffd9"})
            
            this.cat6 = this.getWasteCatCount(6)
            if(this.cat6 != 0)
                this.chartOptions2.series[0].data.push({y:this.cat6, name:" Sugar and sweet products", color:"#6666ff"})
            
            this.cat7 = this.getWasteCatCount(7)
            if(this.cat7 != 0)
                this.chartOptions2.series[0].data.push({y:this.cat7, name:"Beverages", color:"#ff66ff"})
        },
        lastMonth(monthNumber){
            var d1 = this.parseDate(this.getDate())
            d1.setMonth(d1.getMonth() - monthNumber);

            var d2 = this.parseDate(this.getDate())
            d2.setMonth(d2.getMonth() - monthNumber + 1);

            var kitchen = this.$store.state.kitchen
            var count = 0
            var money = 0
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var month = monthNames[d2.getMonth()]

            kitchen.forEach(aliment => {
                var updatedate = aliment.updated_at.slice(0, -9)
                if(this.parseDate(updatedate).getTime() > d1.getTime() && this.parseDate(updatedate).getTime() <= d2.getTime()){
                    if(aliment.status ==2 && aliment.user_id == this.storeuser[0].id){
                        count += 1;
                        money= money + aliment.price
                    }
                }
            })
            var result = {count:count, money:money, month:month}
            console.log(result)
           return result
        },
        getLineWasteChart(){
            this.chartOptionsLine.series[0].data.push(this.lastMonth(1).money);
            this.chartOptionsLine.series[0].data.push(this.lastMonth(2).money);
            this.chartOptionsLine.series[0].data.push(this.lastMonth(3).money);
            this.chartOptionsLine.series[0].data.push(this.lastMonth(4).money);
            this.chartOptionsLine.series[0].data.push(this.lastMonth(5).money);
            this.chartOptionsLine.series[0].data.push(this.lastMonth(6).money);
            this.chartOptionsLine.series[1].data.push(this.lastMonth(1).count);
            this.chartOptionsLine.series[1].data.push(this.lastMonth(2).count);
            this.chartOptionsLine.series[1].data.push(this.lastMonth(3).count);
            this.chartOptionsLine.series[1].data.push(this.lastMonth(4).count);
            this.chartOptionsLine.series[1].data.push(this.lastMonth(5).count);
            this.chartOptionsLine.series[1].data.push(this.lastMonth(6).count);
            
            this.chartOptionsLine.xAxis.categories.push(this.lastMonth(6).month);
            this.chartOptionsLine.xAxis.categories.push(this.lastMonth(5).month);
            this.chartOptionsLine.xAxis.categories.push(this.lastMonth(4).month);
            this.chartOptionsLine.xAxis.categories.push(this.lastMonth(3).month);
            this.chartOptionsLine.xAxis.categories.push(this.lastMonth(2).month);
            this.chartOptionsLine.xAxis.categories.push(this.lastMonth(1).month);
        }

    }

}
</script>

<style scoped>

.recap_waste {
    background-image: url('https://www.frigomagic.com/wp-content/uploads/2020/05/BG-scaled-e1589552182742.jpg');
    background-size: cover;
}

.row.ui.selection.list {
    background-color: white !important;
}
</style>