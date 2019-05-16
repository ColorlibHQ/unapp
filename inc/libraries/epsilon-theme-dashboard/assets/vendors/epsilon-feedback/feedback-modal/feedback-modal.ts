import Vue from 'vue';
import './feedback-modal.scss';
import { EpsilonFetchTranslator } from '../../epsilon-fetch-translator';

export const feedbackModal: any = Vue.extend( {
  /**
   * Component name
   */
  name: 'feedback-modal',
  /**
   * Data model
   * @returns {{checkedReasons: any[]}}
   */
  data() {
    return {
      /**
       * Other reason
       */
      checkedReason: '',
      /**
       * Other reason
       */
      otherReason: '',
      /**
       * Show others
       */
      showOther: false,
    };
  },
  /**
   * Computed properties
   */
  computed: {
    /**
     * Computed properties
     * @returns {any}
     */
    options() {
      return this.$store.getters.getOptions;
    },
    /**
     * Return Ajax nonce
     * @returns {any}
     */
    ajaxNonce() {
      return this.$store.getters.getNonce;
    },
    /**
     * Return Ajax url
     * @returns {any}
     */
    ajaxUrl() {
      return this.$store.getters.getAjaxUrl;
    },
    /**
     * Is the modal visible?
     * @returns {any}
     */
    visibility() {
      return this.$store.getters.getVisibility;
    },
    /**
     * Translations
     * @returns {any}
     */
    translations() {
      return this.$store.getters.getTranslations;
    }
  },
  /**
   * Setup watchers
   */
  watch: {
    // whenever question changes, this function will run
    checkedReason( this: any, newVal: string, oldVal: string ) {
      this.showOther = newVal === 'other';
    }
  },
  /**
   * Methods
   */
  methods: {
    /**
     * Sends data to tracking
     */
    sendData( event: Event ) {
      event.preventDefault();
      const data: {
        action: string,
        nonce: string,
        args: {
          action: Array<string>,
          nonce: string,
          args: {
            reason: string,
            otherReason: string,
          },
        },
      } = this._prepData();

      fetch( this.ajaxUrl, new EpsilonFetchTranslator( data ) ).then( function( res ) {
        return res.json();
      } );

      this.$store.commit( 'setVisibility', false );
    },

    /**
     * Prepare data
     *
     * @returns {{action: string; nonce: () => (state: any) => any; args: {action: string[]; nonce: () => (state: any) => any; args: {reason: string | ((newVal: string, oldVal:
     *     string) => void); otherReason: string | any}}}}
     * @private
     */
    _prepData() {
      return {
        action: 'epsilon_dashboard_ajax_callback',
        nonce: this.ajaxNonce,
        args: {
          action: [ 'Epsilon_Uninstall_Feedback', 'send_data' ],
          nonce: this.ajaxNonce,
          args: {
            reason: this.checkedReason,
            otherReason: this.otherReason,
          },
        },
      };
    },
    /**
     * Close modal
     *
     * @param {Event} event
     */
    closeModal( event: Event ) {
      event.preventDefault();
      // should ajax request to hide forever (transient)
      const data: {
        action: string,
        nonce: string,
        args: {
          action: Array<string>,
          nonce: string,
          args: {},
        },
      } = {
        action: 'epsilon_dashboard_ajax_callback',
        nonce: this.ajaxNonce,
        args: {
          action: [ 'Epsilon_Uninstall_Feedback', 'set_feedback_visibility' ],
          nonce: this.ajaxNonce,
          args: {},
        },
      };

      fetch( this.ajaxUrl, new EpsilonFetchTranslator( data ) ).then( function( res ) {
        return res.json();
      } );

      this.$store.commit( 'setVisibility', false );
    },
  },
  /**
   * Component template
   */
  template: `
    <transition name="modal">
        <div class="epsilon-feedback-modal" v-if="visibility">
        <header>
            <h3>{{ translations.uninstallFeedbackTitle }}</h3>
        </header>
        <section class="epsilon-feedback-modal--options">
          <div v-for="option in options" :key="option.id">
            <template v-if="option.type === 'radio'">
              <input type="radio" :id="option.id" :value="option.value" v-model="checkedReason"/>        
              <label :for="option.id">{{ option.label }}</label> 
            </template>
            <template v-else-if="option.type === 'textarea'">
              <div v-show="showOther">
                <label :for="option.id">{{ option.label }}</label> <br />
                <textarea :id="option.id" v-model="otherReason"></textarea>
              </div>
            </template>
          </div>
        </section>        
        <footer>
            <a href="#" @click.stop="closeModal"> {{ translations.closeModal }} </a>
            <a href="#" @click.stop="sendData" class="button button-primary"> {{ translations.sendData }} </a>
        </footer>
    </div>
    </transition>
  `,

} );
Vue.component( 'feedback-modal', feedbackModal );
