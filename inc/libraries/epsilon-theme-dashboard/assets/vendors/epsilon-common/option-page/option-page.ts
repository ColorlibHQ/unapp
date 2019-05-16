import './option-page.scss';
import { EpsilonFetchTranslator } from '../../epsilon-fetch-translator';
import Vue from 'vue';

declare let wp: any, ajaxurl: string, jQuery: any;

export const dashboardOptionPage: any = Vue.extend( {
  /**
   * Component page
   */
  name: 'option-page',
  /**
   * Fields
   */
  props: [ 'fields' ],
  /**
   * Component template
   */
  template: `
    <div>
        <template v-for="(field, index) in fields">
            <template v-if="field.type === 'epsilon-toggle'">
                <epsilon-toggle :parent-index="index" :comp-label="field.label" :comp-id="field.id" :relation="field.id"></epsilon-toggle>
            </template>
        </template>
    </div>
  `,
  /**
   * Methods array
   */
  methods: {
    /**
     * Epsilon toggle on/off
     */
    handleEpsilonToggle: function( args: { id: string, status: boolean, parentIndex: number, relation: string | boolean } ) {
      if ( 'undefined' === typeof args.relation ) {
        return;
      }

      const self = this;
      let option: any = {};
      option[ args.id ] = args.status;

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
        self.$store.commit( 'updatePrivacyStatus', { id: args.id, status: args.status } );
      } );

    },
  },
  /**
   * Before mount hook
   */
  created: function() {
    this.$root.$on( 'changed-epsilon-toggle', this.handleEpsilonToggle );
  },
} );

Vue.component( 'option-page', dashboardOptionPage );