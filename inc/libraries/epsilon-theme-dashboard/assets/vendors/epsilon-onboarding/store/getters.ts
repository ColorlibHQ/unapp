/**
 *
 * State getters
 *
 */
export const getters = {
  /**
   * Get imported state
   * @param state
   * @returns {() => any}
   */
  getImportStatus: function( state: any ) {
    return state.importedDemo;
  },
  /**
   * Get imported state
   * @param state
   * @returns {() => any}
   */
  getOnboardingStatus: function( state: any ) {
    return state.onboardingStatus;
  },
  /**
   * Gets the field value based on a "relation"
   * @param state
   */
  getFieldRelation: ( state: any ) => ( id: string ) => {
    return state.privacy[ id ];
  },
  /**
   * Returns boolean if a step is loading or not
   * @param state
   * @returns {any}
   */
  getStepLoading: ( state: any ) => {
    return state.stepLoading;
  }
};
