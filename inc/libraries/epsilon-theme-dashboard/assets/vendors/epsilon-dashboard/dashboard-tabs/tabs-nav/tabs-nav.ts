import './tabs-nav.scss';
import Vue from 'vue';

declare let wp: any;

/**
 * Tabs component
 * @type {ExtendedVue<VueConstructor, any, any, any, Record<never, any>>}
 */
export const dashboardTabsNav: any = Vue.extend( {
  /**
   * Component name
   */
  name: 'dashboard-tabs-nav',
  /**
   * Model
   * @returns {{currentTab: null}}
   */
  data: function() {
    return {
      tabs: this.$store.state.tabs,
    };
  },
  computed: {
    /**
     * Computed properties
     * @returns {any}
     */
    activeTab: function() {
      return this.$store.getters.getActiveTab;
    },
  },
  /**
   * Component template
   */
  template: `
    <nav>
        <template v-for="(tab, index) in tabs">
            <a v-if="hiddenTab(tab.hidden)" :href="'#' + tab.id" :class="{ active: index === activeTab }" @click="changeTab($event, index)">{{ tab.title }}</a>
        </template>
    </nav>
  `,
  /**
   * Methods
   */
  methods: {
    /**
     * Hidden tab ?
     */
    hiddenTab: function( visibility: boolean | string ) {
      if ( ! visibility ) {
        return true;
      }

      return ! this.$store.getters.getFieldRelation( visibility );
    },
    /**
     * Send event to component
     * @param {Event} event
     * @param {string} index
     */
    changeTab: function( event: Event, index: number ): void {
      event.preventDefault();
      this.$store.commit( 'changeTab', index );
    },
  },
} );
Vue.component( 'dashboard-tabs-nav', dashboardTabsNav );
