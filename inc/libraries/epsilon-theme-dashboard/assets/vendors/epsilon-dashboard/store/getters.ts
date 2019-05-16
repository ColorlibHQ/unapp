/**
 *
 * State getters
 *
 */
export const getters = {
  /**
   * Get active tab
   * @param state
   * @returns {(() => any) | (() => (state: any) => any)}
   */
  getActiveTab: function( state: any ) {
    return parseInt( state.activeTab, 10 );
  },
  /**
   * Get current actions
   * @param state
   */
  getActions: function( state: any ) {
    return state.actions;
  },
  /**
   * Get imported state
   * @param state
   * @returns {() => any}
   */
  getImportStatus: function( state: any ) {
    return state.importedDemo;
  },
  /**
   * Grab license key
   * @param state
   */
  getLicenseKey: function( state: any ) {
    return state.edd.license;
  },
  /**
   * Grab license status
   * @param state
   */
  getLicenseStatus: function( state: any ) {
    return state.edd.status;
  },
  /**
   * Gets the field value based on a "relation"
   * @param state
   */
  getFieldRelation: ( state: any ) => ( id: string ) => {
    return state.privacy[ id ];
  }
};
