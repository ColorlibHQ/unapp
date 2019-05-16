import './tabs-content.scss';
import Vue from 'vue';
import { EpsilonFetchTranslator } from '../../../epsilon-fetch-translator';

declare let wp: any, ajaxurl: any;

/**
 * Tabs component
 * @type {ExtendedVue<VueConstructor, any, any, any, Record<never, any>>}
 */
export const dashboardTabsContent: any = Vue.extend( {
  /**
   * Component name
   */
  name: 'dashboard-tabs-content',
  /**
   * Model
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
    /**
     * Gets import state
     * @returns {(state: any) => any}
     */
    importedDemo: function() {
      return this.$store.getters.getImportStatus;
    },
  },
  /**
   * Component template
   */
  template: `
      <div>
        <template v-for="(tab, index) in tabs">
          <div class="epsilon-dashboard-tab" :class="{ active: activeTab === index }" :id="tab.id" :key="index">
          
            <template v-if="tab.type === 'info'">
              <div class="row">
                  <div class="col" v-for="col in tab.content" :class="{ standout: col.type === 'standout' }">
                      <h3>{{ col.title }}</h3>
                      <p v-html="col.paragraph"></p>
                      <p v-html="col.action"></p>
                  </div>
              </div>
            </template>
            
            <template v-else-if="tab.type === 'actions'">
                <recommended-actions></recommended-actions>
            </template>
            
            <template v-else-if="tab.type === 'demos'">
              <h3 v-if="!importedDemo">{{ tab.content.title }}</h3>
              <h3 v-else>{{ tab.content.titleAlternate }}</h3>
              
              <p v-html="tab.content.paragraph"></p>
              <demos :path="tab.content.demos"></demos>
            </template>
            
            <template v-else-if="tab.type === 'plugins'">
                <plugins :expanded="true"></plugins>
            </template>
            
            <template v-else-if="tab.type === 'registration'">
                <registration></registration>
            </template>
            
            <template v-else-if="tab.type === 'option-page'">
                <h3>{{ tab.content.title }}</h3>
                <p v-for="paragraph in tab.content.paragraphs" v-html="paragraph"></p>
                <option-page :fields="tab.fields"></option-page>
            </template>
            
            <template v-else-if="tab.type === 'comparison-table'">
                <comparison-tables :features="tab.features"></comparison-tables>
            </template>
          </div>
        </template>
      </div>
  `,
} );
Vue.component( 'dashboard-tabs-content', dashboardTabsContent );
