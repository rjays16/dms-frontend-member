import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const state = {
  token: null,
  // apiUrl: "http://dms-api.local",
  apiUrl: process.env.MIX_API_SERVER_DOMAIN
}

const mutations = {
  SET_TOKEN (state, token) {
    state.token = token
  },
  REMOVE_TOKEN (state) {
    state.token = null
  }
}

export default new Vuex.Store({
  state,
  mutations
})
