import { EpsilonFetchTranslator } from '../../epsilon-fetch-translator';

declare let ajaxurl: any;
/**
 *
 * State mutations
 *
 */
export const mutations = {
  /**
   * Boolean
   *
   * @param state
   * @param {boolean} bool
   */
  setStepLoading( state: any, bool: boolean ) {
    state.stepLoading = bool;
  },
  /**
   * Sets an installation flag for a plugin
   * @param state
   * @param slug
   */
  setPluginInstalled( state: any, slug: string ) {
    state.plugins[ slug ].installed = true;
  },
  /**
   * Updates privacy status
   *
   * @param state
   * @param args
   */
  updatePrivacyStatus( state: any, args: { id: string, status: boolean } ) {
    if ( 'undefined' !== typeof state.privacy[ args.id ] ) {
      state.privacy[ args.id ] = args.status;
    }
  },
  /**
   * Sets imported flag
   * @param state
   * @param {boolean} change
   */
  setImportedFlag( state: any, change: boolean ) {
    let temp: any = {};
    temp[ state.theme[ 'theme-slug' ] + '_content_imported' ] = true;
    state.importedDemo = true;
    if ( change ) {
      let fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: state.ajax_nonce,
            args: {
              action: [ 'Epsilon_Dashboard_Helper', 'set_options' ],
              nonce: state.ajax_nonce,
              args: {
                theme_mod: temp
              },
            },
          };

      fetchObj = new EpsilonFetchTranslator( data );

      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        if ( json.status && 'ok' === json.message ) {
          state.importedDemo = true;
        }
      } );
    }
  },
  /**
   * Sets imported flag
   * @param state
   * @param {boolean} change
   */
  setOnboardingFlag( state: any, change: boolean ) {
    let temp: any = {};
    temp[ state.theme[ 'theme-slug' ] + '_used_onboarding' ] = true;
    state.importedDemo = true;
    if ( change ) {
      let fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: state.ajax_nonce,
            args: {
              action: [ 'Epsilon_Dashboard_Helper', 'set_options' ],
              nonce: state.ajax_nonce,
              args: {
                theme_mod: temp
              },
            },
          };

      fetchObj = new EpsilonFetchTranslator( data );

      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        if ( json.status && 'ok' === json.message ) {
          return;
        }
      } );
    }
  },
  /**
   * Sets tracking status to true
   * @param state
   */
  setTrackingStatus( state: any ) {
    let temp: any = {};
    temp[ state.theme[ 'theme-slug' ] + '_tracking_enable' ] = true;
    state.importedDemo = true;

    let fetchObj: EpsilonFetchTranslator,
        data = {
          action: 'epsilon_dashboard_ajax_callback',
          nonce: state.ajax_nonce,
          args: {
            action: [ 'Epsilon_Dashboard_Helper', 'set_options' ],
            nonce: state.ajax_nonce,
            args: {
              option: temp
            },
          },
        };

    fetchObj = new EpsilonFetchTranslator( data );

    fetch( ajaxurl, fetchObj ).then( function( res ) {
      return res.json();
    } ).then( function( json ) {
      if ( json.status && 'ok' === json.message ) {
        return;
      }
    } );
  }
};
