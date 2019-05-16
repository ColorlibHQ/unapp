import './epsilon-toggle.scss';
import Vue from 'vue';

declare let EpsilonDashboard: any, wp: any;

/**
 * Epsilon Toggle field
 * @type {ExtendedVue<VueConstructor, any, any, any, Record<never, any>>}
 */
export const epsilonToggle: any = Vue.extend( {
  /**
   * Component name
   */
  name: 'epsilon-toggle',
  /**
   * Accepted props
   */
  props: [ 'compId', 'compLabel', 'parentIndex', 'relation' ],
  /**
   * Active state
   */
  computed: {
    active: {
      get() {
        if ( 'string' === typeof this.relation ) {
          return this.$store.getters.getFieldRelation( this.compId );
        }

        return true;
      },
      set( value: boolean ) {
        this.$nextTick( function() {
          this.$root.$emit( 'changed-epsilon-toggle', { id: this.compId, status: value, parentIndex: this.parentIndex, relation: this.relation } );
        } );
      }
    }
  },

  /**
   * Component template
   */
  template: `
		<div class="checkbox_switch">
      <div class="onoffswitch">
        <input type="checkbox" :id="'epsilon-' + compId" :name="'epsilon-' + compId" v-model="active" class="onoffswitch-checkbox" :checked="active">
        <label class="onoffswitch-label" :for="'epsilon-' + compId"></label>
      </div>
      {{ compLabel }}
		</div>
  `,

} );
Vue.component( 'epsilon-toggle', epsilonToggle );