declare let ajaxurl: any;
/**
 *
 * State getters
 *
 */
export const getters = {
  /**
   * Get options
   * @param state
   * @returns {(() => any) | (() => (state: any) => any)}
   */
  getOptions( state: any ) {
    return state.options;
  },
  /**
   * Get visibility
   * @param state
   * @returns {any}
   */
  getVisibility( state: any ) {
    return eval( state.visibility );
  },
  /**
   * Gets AJAX url
   * @param state
   */
  getAjaxUrl( state: any ) {
    return ajaxurl;
  },
  /**
   * Gets ajax nonce
   * @param state
   */
  getNonce( state: any ) {
    return state.ajax_nonce;
  },
  /**
   * Translations
   * @param state
   * @returns {any}
   */
  getTranslations( state: any ) {
    return state.translations;
  }
};
