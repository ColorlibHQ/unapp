declare let ajaxurl: any;
/**
 *
 * State mutations
 *
 */
export const mutations = {
  /**
   * Sets the visibility of the modal
   * @param state
   * @param {boolean} value
   */
  setVisibility( state: any, value: boolean ) {
    state.visibility = value;
  },
};
