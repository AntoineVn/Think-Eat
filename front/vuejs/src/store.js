import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

/* eslint-disable no-param-reassign */
export default new Vuex.Store({
  state: {
    kitchen : [],
    user : [],
    allusers : [],
    token : []
  },
  mutations: {
    addAliment(state, payload){
        state.kitchen.push(payload) ;
    },
    updateAliment(state,payload){
      var updatedalim = state.kitchen.find(alim => alim.id == payload.id)
      updatedalim.name = payload.name;
      updatedalim.image = payload.image;
      updatedalim.image = payload.image;
      updatedalim.expire_date = payload.expire_date;
      updatedalim.quantity = payload.quantity;
      updatedalim.price = payload.price;
      updatedalim.category_id = payload.category_id;
      updatedalim.status = payload.status
    },
    deleteAliment(state,id){
      var index = state.kitchen.findIndex(cat => cat.id == id);
      state.kitchen.splice(index, 1);
    },

    addUser(state, payload){
      state.user.push(payload) ;
    },
    updateStatus(state, payload){
      var updatedalim = state.kitchen.find(alim => alim.id == payload.id)
      updatedalim.status = payload.status
    },
    addallUsers(state,payload){
      state.allusers.push(payload) ;
    },
    updateUser(state,payload){
      var tab = state.user.find(user => user.id == payload.id)
      tab = payload
    },
    addToken(state,payload){
      state.token.push(payload);
    },
    clearAll(state){
      state.token.splice(0, 1);
      state.kitchen = [];
      state.user = [];
    },
    deleteUser(state,id){
      var index = state.allusers.findIndex(user => user.id == id);
      state.allusers.splice(index, 1);
    }
  },
})