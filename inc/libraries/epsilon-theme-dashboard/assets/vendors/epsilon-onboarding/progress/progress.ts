import './progress.scss';
import Vue from 'vue';

declare let EpsilonOnboarding: any, wp: any;

/**
 * Onboarding progress
 * @type {ExtendedVue<VueConstructor, any, any, any, Record<never, any>>}
 */
export const onboardingProgress: any = Vue.extend( {
  /**
   * Template name
   */
  name: 'onboarding-progress',
  /**
   * Accepted props
   */
  props: [ 'info' ],
  /**
   * Model
   * @returns {{}}
   */
  data: function() {
    return {
      /**
       * Progress steps
       */
      progressSteps: null,
      /**
       * Current page
       */
      currentStep: 0,
      /**
       * Step width ( for animations )
       */
      progressStepWidth: null,
      /**
       * Full width ( for animation )
       */
      progressFullWidth: null,
      /**
       * Actual animator
       */
      computedWidth: 0,
    };
  },
  methods: {
    /**
     * Get the progress steps
     */
    computedInfo: function() {
      this.progressSteps = this.info.steps;
    },
    /**
     * After page changes, update progress bar as well
     * @param {} params
     */
    changedStep: function( params: { action: string, from: number } ) {
      this.currentStep = 'next' === params.action ? this.currentStep + 1 : this.currentStep - 1;
      this.calculateWidth();
    },
    /**
     * Calculates width and returns a css valid string in pixels
     */
    calculateWidth: function(): void {
      let self = this;
      setTimeout( function() {
        if ( null === self.progressStepWidth ) {
          self.calculateOneStep();
        }

        if ( 0 === self.currentStep ) {
          self.computedWidth = 0;
        } else if ( self.currentStep === (self.progressSteps.length - 1) ) {
          self.computedWidth = self.progressFullWidth;
        } else {
          self.computedWidth = self.progressStepWidth + (self.currentStep * (2 * self.progressStepWidth));
        }

      }, 600 );

    },
    /**
     * calculate one step of progress bar
     */
    calculateOneStep: function(): void {
      let self = this, list = this.$el.getElementsByTagName( 'ul' ),
          width;

      for ( let i = 0; i < list.length; i ++ ) {
        width = list[ i ].offsetWidth;
      }
      self.progressFullWidth = width;

      this.progressStepWidth = (width / self.progressSteps.length) / 2;
    },
  },
  /**
   * Page template
   */
  template: `
    <div class="epsilon-onboarding-progress">
        <ul>
            <li v-bind:class="{ active: index === currentStep, passed: index < currentStep }" v-for="(step, index) in progressSteps">
                {{ step.progress }}
            </li>
        </ul>
        <span class="completed-span" v-bind:style="{ width: computedWidth + 'px'}"></span>
    </div>
  `,
  /**
   * Mounted lifecycle
   */
  created: function() {
    this.computedInfo();
    this.$root.$on( 'change-step', this.changedStep );
  },

} );
Vue.component( 'onboarding-progress', onboardingProgress );
