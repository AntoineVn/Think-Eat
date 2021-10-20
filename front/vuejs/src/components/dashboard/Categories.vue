<template>
    <div class="category">

      <div class="category_list1">
        <router-link :to="{name : 'Aliments', params : {id: 1}}">  Meat, fish and eggs </router-link>
      </div>

      <div class="category_list2">
        <router-link :to="{name : 'Aliments', params : {id: 2}}"> Milk products </router-link>
      </div>
      
      <div class="category_list3">
        <router-link :to="{name : 'Aliments', params : {id: 3}}"> Cooking oils and fats</router-link> 
      </div>

      <div class="category_list4">
        <router-link :to="{name : 'Aliments', params : {id: 4}}"> Vegetables, legumes and fruits </router-link> 
      </div>

      <div class="category_list5">
        <router-link :to="{name : 'Aliments', params : {id: 5}}"> Cereals, cereal-based products and grains</router-link> 
      </div>

      <div class="category_list6">
        <router-link :to="{name : 'Aliments', params : {id: 6}}"> Sugar and sweet products </router-link>
      </div>

      <div class="category_list7">
        <router-link :to="{name : 'Aliments', params : {id: 7}}"> Beverages </router-link>  
      </div>
        
        
         
        
        <!-- <div v-for="cat in categories" :key="cat.id">
            <router-link :to="{name : 'Aliments', params : {id: cat.id}}"> {{cat.name}} </router-link> 
        </div> -->

  </div>
</template>

<script type="text/javascript">

  export default {
    data() {
      return {
          categories : ""
      };
    },
    methods: {
        async getCategories () {
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
    }
  };
</script>

<style scoped>
  .italic {font-style: italic;}

  .category {
    font-size: 20px;
  }
  a {
    color: white;
  }
  a:hover {
    color: black;
  }
  .category_list1 {
    height: 90px;
    border-bottom: 5px solid;
    padding-top: 28px;
  }
  .category_list2 {
    height: 90px;
    border-bottom: 5px solid;
    padding-top: 28px;
  }
  .category_list3 {
    height: 90px;
    border-bottom: 5px solid;
    padding-top: 28px;
  }
  .category_list4 {
    height: 90px;
    border-bottom: 5px solid;
    padding-top: 28px;
  }
  .category_list5 {
    height: 90px;
    border-bottom: 5px solid;
    padding-top: 28px;
  }
  .category_list6 {
    height: 90px;
    border-bottom: 5px solid;
    padding-top: 28px;
  }
  .category_list7 {
    height: 90px;
    padding-top: 20px;
  }

</style>