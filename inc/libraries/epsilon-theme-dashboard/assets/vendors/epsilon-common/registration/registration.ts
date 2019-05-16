import './registration.scss';
import Vue from 'vue';
import { EpsilonFetchTranslator } from '../../epsilon-fetch-translator';

declare let wp: any, ajaxurl: string, jQuery: any;

export const dashboardRegistration: any = Vue.extend( {
  /**
   * Registration
   */
  name: 'registration',
  /**
   * Data model
   */
  data: function() {
    return {
      processing: false,
      licenseExpiry: this.$store.state.edd.expires,
      translations: {
        licenseKey: this.$store.state.translations.licenseKey,
        saveLicense: this.$store.state.translations.saveLicense,
        changeLicense: this.$store.state.translations.changeLicense,
        checkLicense: this.$store.state.translations.checkLicense,
        activateLicense: this.$store.state.translations.activateLicense,
        deactivateLicense: this.$store.state.translations.deactivateLicense,
        status: this.$store.state.translations.status,
        expires: this.$store.state.translations.expires,
      }
    };
  },
  /**
   * Computed array
   */
  computed: {
    /**
     * License key
     * @returns {any}
     */
    licenseKey: {
      get() {
        return this.$store.getters.getLicenseKey;
      },
      set( value: string ) {
        this.$store.commit( 'updateLicenseKey', value );
      }
    },
    /**
     * License status
     * @returns {(state: any) => any}
     */
    licenseStatus: function() {
      return this.$store.getters.getLicenseStatus;
    }
  },
  /**
   * Methods array
   */
  methods: {
    /**
     * License deactivator
     */
    deactivateLicense: function() {
      const self = this;
      this.processing = true;
      let fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: this.$store.state.ajax_nonce,
            args: {
              action: [ 'EDD_Theme_Helper', 'deactivate_license' ],
              nonce: this.$store.state.ajax_nonce,
              args: {
                license: this.licenseKey,
                theme: this.$store.state.theme,
                downloadId: this.$store.state.downloadId,
              },
            },
          };

      fetchObj = new EpsilonFetchTranslator( data );
      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        self.processing = false;
        self.$store.commit( 'updateLicenseStatus', json.licenseStatus );
      } );
    },
    /**
     * Activate a license key
     */
    activateLicense: function() {
      const self = this;
      this.processing = true;
      let fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: this.$store.state.ajax_nonce,
            args: {
              action: [ 'EDD_Theme_Helper', 'activate_license' ],
              nonce: this.$store.state.ajax_nonce,
              args: {
                license: this.licenseKey,
                theme: this.$store.state.theme,
                downloadId: this.$store.state.downloadId,
              },
            },
          };

      fetchObj = new EpsilonFetchTranslator( data );
      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        self.processing = false;

        self.$store.commit( 'updateLicenseStatus', json.licenseStatus );
      } );
    },
    /**
     * Check a license for "validity"
     */
    checkLicense: function() {
      const self = this;
      this.processing = true;

      this.saveLicense();

      let fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: this.$store.state.ajax_nonce,
            args: {
              action: [ 'EDD_Theme_Helper', 'check_license' ],
              nonce: this.$store.state.ajax_nonce,
              args: {
                license: this.licenseKey,
                theme: this.$store.state.theme,
                downloadId: this.$store.state.downloadId,
              },
            },
          };

      fetchObj = new EpsilonFetchTranslator( data );

      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        self.processing = false;
        self.licenseExpiry = json.expires;

        self.$store.commit( 'updateLicenseStatus', json.licenseStatus );
      } );

    },
    /**
     * Save license in databse
     */
    saveLicense: function() {
      const self = this;
      this.processing = true;

      let option: any = {};
      option[ this.$store.state.edd.licenseOption ] = this.licenseKey;

      let fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: this.$store.state.ajax_nonce,
            args: {
              action: [ 'Epsilon_Dashboard_Helper', 'set_options' ],
              nonce: this.$store.state.ajax_nonce,
              args: {
                option: option
              },
            },
          };

      fetchObj = new EpsilonFetchTranslator( data );

      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        self.processing = false;
        self.$store.commit( 'updateLicenseStatus', 'check' );
      } );
    }
  },
  /**
   * Template
   */
  template: `
  <div class="epsilon-registration-container">
    <div class="epsilon-field-control-group">
      <label for="epsilon-license-key">{{ translations.licenseKey }}</label>
      <input id="epsilon-license-key" v-model="licenseKey" />
      <div class="epsilon-license-info" v-if="licenseStatus">
        <p>{{ translations.status }} {{ licenseStatus }}</p>
        <p v-if="licenseExpiry">{{ translations.expires }} {{ licenseExpiry }}</p>
      </div>
    </div>
    <button class="button" :disabled="processing" @click="checkLicense()"> {{ translations.checkLicense }} </button>
    <template v-if="licenseStatus === 'valid'">
        <button v-if="licenseStatus === 'valid'" class="button" @click="deactivateLicense()"> {{ translations.deactivateLicense }} </button>
    </template>
    <template v-else-if="licenseStatus">
        <button v-if="licenseStatus" class="button" :disabled="processing" @click="activateLicense()"> {{ translations.activateLicense }} </button>
    </template>
  </div>
  `,
} );

Vue.component( 'registration', dashboardRegistration );