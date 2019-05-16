import Vue from 'vue';
import Vuex from 'vuex';
import { mutations } from './mutations';
import { getters } from './getters';

declare let EpsilonDashboard: any;

Vue.use( Vuex );

const state = EpsilonDashboard;

state.importedDemo = false;

export default new Vuex.Store( {
  state,
  mutations,
  getters,
} );