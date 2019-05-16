import './dashboard-tabs.scss';
import Vue from 'vue';
import { dashboardTabsNav } from './tabs-nav/tabs-nav';
import { dashboardTabsContent } from './tabs-content/tabs-content';

declare let wp: any;

/**
 * Tabs component
 * @type {ExtendedVue<VueConstructor, any, any, any, Record<never, any>>}
 */
export const dashboardTabs: any = Vue.extend( {
  /**
   * Component name
   */
  name: 'dashboard-tabs',
  /**
   * Child components
   */
  components: {
    'dashboard-tabs-nav': dashboardTabsNav,
    'dashboard-tabs-content': dashboardTabsContent,
  },
  /**
   * Component template
   */
  template: `
    <div class="epsilon-dashboard-container--tabs">
        <dashboard-tabs-nav></dashboard-tabs-nav>
        
        <dashboard-tabs-content></dashboard-tabs-content>
    </div>
  `,
} );
Vue.component( 'dashboard-tabs', dashboardTabs );