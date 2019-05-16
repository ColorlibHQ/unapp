import Vue from 'vue';
import Vuex from 'vuex';
import { mutations } from './mutations';
import { getters } from './getters';

declare let EpsilonFeedback: any;

Vue.use( Vuex );

const state = EpsilonFeedback;

export default new Vuex.Store( {
  state,
  mutations,
  getters,
} );
