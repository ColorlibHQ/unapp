import { EpsilonFetchTranslator } from '../../epsilon-fetch-translator';

declare let ajaxurl: any;
/**
 *
 * State mutations
 *
 */
export const mutations = {
  /**
   * Add an action
   * @param state
   * @param element
   */
  addAction( state: any, element: any ) {
    state.actions.push( element );
  },
  /**
   * Remove action
   * @param state
   * @param {number} index
   */
  removeAction( state: any, index: number ) {
    state.actions.splice( index, 1 );
  },
  /**
   * Removes action by id
   * @param state
   * @param {string} id
   */
  removeActionById( state: any, id: string ) {
    state.actions = state.actions.filter( function( element: any ) {
      return id !== element.id;
    } );
  },
  /**
   * Sets the current tab
   * @param state
   * @param {number} index
   */
  changeTab( state: any, index: number ) {
    state.activeTab = index;

    let fetchObj: EpsilonFetchTranslator,
        data = {
          action: 'epsilon_dashboard_ajax_callback',
          nonce: state.ajax_nonce,
          args: {
            action: [ 'Epsilon_Dashboard_Helper', 'set_user_meta' ],
            nonce: state.ajax_nonce,
            args: {
              tab: index,
              option: 'epsilon_active_tab',
            },
          },
        };

    fetchObj = new EpsilonFetchTranslator( data );
    fetch( ajaxurl, fetchObj ).then( function( res ) {
      return res.json();
    } );
  },
  /**
   * Update license key
   * @param state
   * @param {string} value
   */
  updateLicenseKey( state: any, value: string ) {
    state.edd.license = value;
  },
  /**
   * Update license status
   * @param state
   * @param {string} value
   */
  updateLicenseStatus( state: any, value: string ) {
    state.edd.status = value;
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
        console.log( json );
        if ( json.status && 'ok' === json.message ) {
          state.importedDemo = true;
        }
      } );
    }
  }
};
