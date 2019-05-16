declare let require: any, wp: any;
import Vue from 'vue';

import './dashboard.scss';
import { dashboardContainer } from './dashboard-container/dashboard-container';
import { dashboardPlugins } from '../epsilon-common/plugins/plugins';
import { dashboardRecommendedActions } from '../epsilon-common/recommended-actions/recommended-actions';
import { dashboardDemos } from '../epsilon-common/demos/demos';
import { dashboardRegistration } from '../epsilon-common/registration/registration';
import { dashboardOptionPage } from '../epsilon-common/option-page/option-page';
import { dashboardComparisonTables } from '../epsilon-common/comparison-table/comparison-table';
import { epsilonToggle } from '../epsilon-common/epsilon-fields/epsilon-toggle/epsilon-toggle';

import Store from './store/store';

const epsilonDashboardVue = new Vue( {
  /**
   * Element
   */
  el: '#epsilon-dashboard-app',
  /**
   * Store
   */
  store: Store,
  /**
   * App components
   */
  components: {
    'dashboard-container': dashboardContainer,
    'recommended-actions': dashboardRecommendedActions,
    'demos': dashboardDemos,
    'plugins': dashboardPlugins,
    'registration': dashboardRegistration,
    'option-page': dashboardOptionPage,
    'comparison-table': dashboardComparisonTables,
    'epsilon-toggle': epsilonToggle,
  },
  /**
   * Template
   */
  template: `<dashboard-container></dashboard-container>`,
} );
