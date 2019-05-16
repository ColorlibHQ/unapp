declare let require: any, wp: any;
import Vue from 'vue';
import Store from './store/store';
import { feedbackModal } from './feedback-modal/feedback-modal';

import './feedback.scss';


const epsilonFeedbackVue = new Vue( {
  /**
   * Element
   */
  el: '#epsilon-feedback-app',
  /**
   * Store
   */
  store: Store,
  /**
   * App components
   */
  components: {
    'feedback-modal': feedbackModal
  },
  /**
   * Template
   */
  template: `<feedback-modal></feedback-modal>`,
} );
