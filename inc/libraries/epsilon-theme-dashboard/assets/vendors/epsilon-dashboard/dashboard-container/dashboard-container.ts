import './dashboard-container.scss';
import Vue from 'vue';

import { dashboardTabs } from '../dashboard-tabs/dashboard-tabs';

declare let wp: any;
/**
 *
 * @type {ExtendedVue<VueConstructor, any, any, any, Record<never, any>>}
 */
export const dashboardContainer: any = Vue.extend( {
  /**
   * Name
   */
  name: 'dashboard-container',
  /**
   * Child components
   */
  components: {
    'dashboard-tabs': dashboardTabs
  },
  /**
   * Data methods
   */
  data: function() {
    return {
      /**
       * Get header from store
       */
      header: this.$store.state.theme.header,
      /**
       * Get intro from store
       */
      intro: this.$store.state.theme.intro,
      /**
       * Get logo from store
       */
      logo: this.$store.state.theme.logo,
    };
  },
  /**
   * Template for the container
   */
  template: `
    <div class="epsilon-dashboard-container">
        <h1> {{ header }} </h1>
        <div class="epsilon-dashboard-container--intro">
            <p>{{ intro }}</p>
            <img :src="logo" />
        </div>
        
        <dashboard-tabs></dashboard-tabs>
    </div>
  `,
} );
Vue.component( 'dashboard-container', dashboardContainer );
