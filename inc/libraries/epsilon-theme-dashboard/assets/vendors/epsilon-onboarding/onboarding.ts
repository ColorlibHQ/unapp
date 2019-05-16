import { dashboardPluginsQueue } from '../epsilon-common/plugins-queue/plugins-queue';

declare let require: any, wp: any;
import Vue from 'vue';
import Store from './store/store';

import './onboarding.scss';
import { onboardingContainer } from './onboarding-container/onboarding-container';
import { epsilonToggle } from '../epsilon-common/epsilon-fields/epsilon-toggle/epsilon-toggle';
import { dashboardOptionPage } from '../epsilon-common/option-page/option-page';
import { dashboardDemosOnboarding } from '../epsilon-common/demos-onboarding/demos-onboarding';

const epsilonOnboardingVue = new Vue( {
  /**
   * Element
   */
  el: '#epsilon-onboarding-app',
  /**
   * Store
   */
  store: Store,
  /**
   * App components
   */
  components: {
    'onboarding-container': onboardingContainer,
    'plugins-queue': dashboardPluginsQueue,
    'demos-onboarding': dashboardDemosOnboarding,
    'option-page': dashboardOptionPage,
    'epsilon-toggle': epsilonToggle,
  },
  /**
   * Template
   */
  template: `<onboarding-container></onboarding-container>`,
} );
