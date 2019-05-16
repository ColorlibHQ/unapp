import './comparison-table.scss';
import Vue from 'vue';

declare let wp: any, ajaxurl: string, jQuery: any;

export const dashboardComparisonTables: any = Vue.extend( {
  /**
   * Component name
   */
  name: 'comparison-tables',
  /**
   * Component table
   */
  template: `
    <div>
        <table class="epsilon-free-pro-table">
            <thead>
              <tr>
                <th></th>
                <th>{{ features.names[0] }}</th>
                <th>{{ features.names[1] }}</th>
              </tr>
          </thead>
          <tbody>
            <tr v-for="feature in features.comparison">
              <td class="feature"><h3 v-html="feature.label"></h3></td>
              <td class="epsilon-feature" v-html="feature.one"></td>
              <td class="epsilon-feature" v-html="feature.two"></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2" class="text-right" v-html="features.upsell"></td>
            </tr>
          </tbody>
        </table>
    </div>
  `,
  /**
   * Component props
   */
  props: [ 'features' ],

} );
Vue.component( 'comparison-tables', dashboardComparisonTables );