import './onboarding-container.scss';
import Vue from 'vue';
import { onboardingStep } from '../steps/steps';
import { onboardingProgress } from '../progress/progress';

declare let EpsilonOnboarding: any, wp: any;

/**
 * This is the main container used in the epsilon app
 * @type {ExtendedVue<VueConstructor, any, any, any, Record<never, any>>}
 */
export const onboardingContainer: any = Vue.extend( {
  /**
   * Name
   */
  name: 'onboarding-container',
  /**
   * Child components
   */
  components: {
    'onboarding-step': onboardingStep,
    'onboarding-progress': onboardingProgress,
  },
  /**
   * Create the model object
   * @returns {Object}
   */
  data: function(): Object {
    return {
      /**
       * The current active page
       */
      currentStep: 0,
      /**
       * Actual pages
       */
      steps: null,
      /**
       * Page count
       */
      stepCount: 0,
      /**
       * Translation object
       */
      translations: {
        notNow: this.$store.state.translations.notNow,
      },
      /**
       * Admin Url
       */
      adminUrl: this.$store.state.adminUrl,
    };
  },
  /**
   * Methods
   */
  methods: {
    /**
     * Get onboarding pages
     */
    getSteps: function(): void {
      this.steps = this.$store.state.steps;
      this.stepCount = this.$store.state.steps.length;
    },
    /**
     * Change page action
     * @param params
     */
    changeStep: function( params: { action: string, from: number } ): void {
      let body = document.getElementsByTagName( 'body' );
      if ( 0 === params.from ) {
        body[ 0 ].classList.add( 'nodistraction' );
      }

      if ( 1 === params.from && 'back' === params.action ) {
        body[ 0 ].classList.remove( 'nodistraction' );
      }

      this.currentStep = 'next' === params.action ? params.from + 1 : params.from - 1;
    },
  },
  /**
   * Template
   */
  template: `
      <div class="epsilon-onboarding-wrapper">
        <div class="epsilon-onboarding-container" v-bind:class="{ hasProgress: 0 < currentStep }">
            <transition name="tray">
                <onboarding-progress v-show="0 < currentStep" :info="{steps: steps}"></onboarding-progress>
            </transition>
            <template v-for="(step, index) in steps">
                <onboarding-step v-bind:class="{ active: index === currentStep }" v-bind:index="index" v-bind:info="step"></onboarding-step>
            </template>
        </div>
        <a :href="adminUrl" class="button button-link">{{ translations.notNow }}</a>
      </div>
    `,
  /**
   * Before mount lifecycle hook
   */
  beforeMount: function(): void {
    this.getSteps();
  },
  /**
   * Created hook
   */
  created: function(): void {
    this.$root.$on( 'change-step', this.changeStep );
  }
} );
Vue.component( 'onboarding-container', onboardingContainer );
