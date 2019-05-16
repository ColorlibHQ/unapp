import './steps.scss';
import Vue from 'vue';
import { EpsilonFetchTranslator } from '../../epsilon-fetch-translator';

declare let wp: any, window: any;

/**
 * Onboarding step
 * @type {ExtendedVue<VueConstructor, any, any, any, Record<never, any>>}
 */
export const onboardingStep: any = Vue.extend( {
  /**
   * Template name
   */
  name: 'onboarding-step',
  /**
   * Accepted props
   */
  props: [ 'info', 'index' ],
  /**
   * Model
   * @returns {{}}
   */
  data: function() {
    return {
      stepLoading: false
    };
  },
  /**
   * Computed data
   */
  computed: {
    usedOnboarding: function() {
      return this.$store.getters.getOnboardingStatus;
    }
  },
  /**
   * Page template
   */
  template: `
    <div class="onboarding-step" :id="'epsilon-' + info.id" :data-index="index">
      <h2>
      {{ info.title }}
      </h2>
      <p v-for="paragraph in info.content.paragraphs" v-html="paragraph"></p>
      
      <template v-if="info.id === 'plugins'">
        <plugins-queue></plugins-queue>
      </template>
      
      <template v-if="info.id === 'demos'">
        <demos-onboarding :path="info.demos" ></demos-onboarding>
      </template>
      
      <template v-if="info.fields">
        <option-page :fields="info.fields"></option-page>
      </template>
      
      <div class="epsilon-buttons">
        <template v-for="button in info.buttons">
          <a href="#" @click="changeStep($event, button.action, index)" class="button button-primary button-hero" v-bind:class="{ disabled: stepLoading }" v-html="button.label"></a>
        </template>
      </div>
    </div>
  `,
  /**
   * Methods
   */
  methods: {
    /**
     * Step loading
     */
    stopLoading: function() {
      this.stepLoading = false;
    },
    /**
     * Change the step currently viewed
     *
     * @param {Event} e
     * @param {string} action
     * @param {number} index
     * @return
     */
    changeStep: function( e: JQueryEventConstructor, action: string, index: number ) {
      const self = this;
      e.preventDefault();

      if ( this.stepLoading ) {
        return;
      }

      if ( 'next' === action ) {
        if ( 'plugins' === self.info.id ) {
          this.stepLoading = true;
          this.$root.$emit( 'install-plugins', { action: action, from: index } );
          return;
        }

        if ( 'demos' === self.info.id ) {
          this.stepLoading = true;
          this.$root.$emit( 'install-demo', { action: action, from: index } );
          return;
        }
      }

      if ( 'finish' === action ) {
        this.stepLoading = true;
        window.location = this.$store.state.adminUrl;
        return;
      }

      if ( 'customizer' === action ) {
        this.stepLoading = true;
        this.$store.commit( 'setTrackingStatus', true );
        this.$store.commit( 'setOnboardingFlag', true );

        setTimeout( function() {
          if ( this.usedOnboarding ) {
            window.location = self.$store.state.adminUrl + '/customize.php';
          } else {
            setTimeout( function() {
              window.location = self.$store.state.adminUrl + '/customize.php';
            }, 700 );
          }
        }, 700 );

        return;
      }

      this.$root.$emit( 'change-step', { action: action, from: index } );
    },
  },
  created: function() {
    this.$root.$on( 'changed-step', this.stopLoading );
  },
  mounted: function() {
    this.$nextTick( function() {
      jQuery( this.$el ).find( '#hidden-permissions-toggle' ).on( 'click', function( e: JQueryEventConstructor ) {
        e.preventDefault();
        jQuery( jQuery( this ).attr( 'href' ) ).slideToggle();
      } );
    } );
  }
} );
Vue.component( 'onboarding-step', onboardingStep );
