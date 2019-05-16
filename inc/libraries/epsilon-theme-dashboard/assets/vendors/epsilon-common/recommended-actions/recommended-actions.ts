import './recommended-actions.scss';
import Vue from 'vue';
import { EpsilonFetchTranslator } from '../../epsilon-fetch-translator';

declare let wp: any, ajaxurl: string, jQuery: any;

/**
 * Recommended actions
 * @type {ExtendedVue<VueConstructor, any, any, any, Record<never, any>>}
 */
export const dashboardRecommendedActions: any = Vue.extend( {
  /**
   * Recommended actions
   */
  name: 'recommended-actions',
  /**
   * Data model
   * @returns {{actions: any[]}}
   */
  data: function() {
    return {
      translations: {
        noActionsLeft: this.$store.state.translations.noActionsLeft,
        skipAction: this.$store.state.translations.skipAction,
      },
    };
  },

  computed: {
    actions: function() {
      return this.$store.getters.getActions;
    }
  },
  /**
   * Recommended action template
   */
  template: `
    <div class="epsilon-dashboard-recommended-actions">
      <template v-if="actions.length == 0">
        {{ translations.noActionsLeft }}
      </template>
      <transition-group tag="ul" class="epsilon-dashboard-recommended-actions--list" name="list-complete" mode="in-out" v-if="actions.length">
        <li v-for="(action, index) in actions" class="epsilon-dashboard-recommended-actions--action list-complete-item" v-if="action.visible" :key="action.id">
          <template>
            <span class="state-holder" :class="'state-' + action.state">
                <transition name="tray" mode="in-out">
                    <i v-if="action.state === 'complete'" class="dashicons dashicons-yes"></i>
                    <i v-else-if="action.state === 'loading'" class="dashicons dashicons-admin-generic"></i>
                    <i v-else-if="action.state === 'error'" class="dashicons dashicons-dismiss"></i>
                </transition>
            </span>
          </template>
          <h4>{{ action.title }}</h4>
          <p>{{ action.description }}</p>
          <div class="action-initiators" v-if="action.actions">
            <template>
              <template v-for="(init, i) in action.actions">
                  <a class="button button-primary" href="#" @click="initAction($event, index, i)">{{ init.label }}</a>
              </template>
            </template>
          </div>
        </li>
      </transition-group>
    </div>
  `,
  /**
   * Method object
   */
  methods: {
    /**
     * Removes a required action
     * @param {number} index
     */
    removeAction: function( index: number ) {
      this.$store.commit( 'removeAction', index );
    },
    /**
     * Handle plugin installation/activation
     */
    handlePlugin: function( index: number, actionIndex: number ) {
      const self = this;
      let fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: this.$store.state.ajax_nonce,
            args: {
              action: [ 'Epsilon_Dashboard_Helper', 'create_plugin_activation_link' ],
              nonce: this.$store.state.ajax_nonce,
              args: { slug: this.actions[ index ].plugin_slug },
            },
          };

      jQuery( document ).one( 'wp-plugin-install-success', function( event: JQueryEventConstructor, response: any ) {
        self._activatePlugin( index, response );
      } );

      if ( ! this.actions[ index ].actions[ actionIndex ].handler ) {
        this._installPlugin( index );
      } else {
        fetchObj = new EpsilonFetchTranslator( data );

        fetch( ajaxurl, fetchObj ).then( function( res ) {
          return res.json();
        } ).then( function( json ) {
          if ( json.url && 'ok' === json.message ) {
            self._activatePlugin( index, { activateUrl: json.url } );
          }
        } );
      }
    },
    /**
     * Handles plugin installation
     * @param {number} index
     * @private
     */
    _installPlugin: function( index: number ) {
      wp.updates.installPlugin( {
        slug: this.actions[ index ].plugin_slug,
      } );
    },

    /**
     * Handles plugin activation
     *
     * @param index
     * @param response
     */
    _activatePlugin: function( index: number, response: any ) {
      const self = this;
      jQuery.ajax( {
        async: true,
        type: 'GET',
        dataType: 'html',
        url: response.activateUrl,
        success: function( response: any ) {
          self.actions[ index ].state = 'complete';
          setTimeout( function() {
            self.removeAction( index );
          }, 500 );
        }
      } );
    },
    /**
     * Handle recommended actions ajax requests
     * @param {number} index
     * @param {number} actionIndex
     */
    handleAjax: function( index: number, actionIndex: number ) {
      const self = this;
      let currentAction = this.actions[ index ].actions[ actionIndex ],
          fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: this.$store.state.ajax_nonce,
            args: {
              action: currentAction.handler,
              nonce: this.$store.state.ajax_nonce,
              args: [],
            },
          };

      fetchObj = new EpsilonFetchTranslator( data );

      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        if ( json.status && 'ok' === json.message ) {
          self.actions[ index ].state = 'complete';
          setTimeout( function() {
            self.removeAction( index );
          }, 500 );
        }
      } );
    },
    /**
     * Handle theme_mod, option
     * @param {number} index
     * @param {number} actionIndex
     */
    handleOption: function( index: number, actionIndex: number ) {
      const self = this;
      let currentAction = this.actions[ index ].actions[ actionIndex ],
          fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: this.$store.state.ajax_nonce,
            args: {
              action: [ 'Epsilon_Dashboard_Helper', 'set_options' ],
              nonce: this.$store.state.ajax_nonce,
              args: currentAction.handler,
            },
          };

      fetchObj = new EpsilonFetchTranslator( data );

      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        if ( json.status && 'ok' === json.message ) {
          self.actions[ index ].state = 'complete';
          setTimeout( function() {
            self.removeAction( index );
          }, 500 );
        }
      } );
    },
    /**
     * Skips a required action
     *
     * @param {number} index
     * @param {number} actionIndex
     */
    skipAction( index: number, actionIndex: number ) {
      const self = this;
      let currentAction = this.actions[ index ].actions[ actionIndex ],
          temp: any = {},
          fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: this.$store.state.ajax_nonce,
            args: {
              action: [ 'Epsilon_Dashboard_Helper', 'set_visibility_option' ],
              nonce: this.$store.state.ajax_nonce,
              args: {
                option: '_actions_left',
                theme: this.$store.state.theme,
                actions: {},
              },
            },
          };

      temp[ this.actions[ index ].id ] = 'false';
      data.args.args.actions = temp;
      fetchObj = new EpsilonFetchTranslator( data );

      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        if ( json.status ) {
          self.actions[ index ].state = 'complete';
          setTimeout( function() {
            self.removeAction( index );
          }, 500 );
        }
      } );
    },
    /**
     * Checks which of the options are "visibile"
     */
    checkOptionVisibility: function() {
      const self = this;
      this.actions.map( function( element: any, index: number ) {
        if ( true === element.check ) {
          self.$store.commit( 'removeActionById', element.id );
        }
      } );

      let fetchObj: EpsilonFetchTranslator,
          data = {
            action: 'epsilon_dashboard_ajax_callback',
            nonce: this.$store.state.ajax_nonce,
            args: {
              action: [ 'Epsilon_Dashboard_Helper', 'get_visibility_options' ],
              nonce: this.$store.state.ajax_nonce,
              args: {
                theme: this.$store.state.theme,
                option: '_actions_left',
              },
            },
          };

      fetchObj = new EpsilonFetchTranslator( data );

      fetch( ajaxurl, fetchObj ).then( function( res ) {
        return res.json();
      } ).then( function( json ) {
        if ( json.status ) {
          for ( let key in json.option ) {
            self.actions.map( function( element: any, index: number ) {
              if ( element.id === key ) {
                self.$store.commit( 'removeActionById', element.id );
              }
            } );
          }
        }
      } );
    },
    /**
     * Initiate the required action
     *
     * @param {event} event
     * @param {number} index
     * @param {number} i
     */
    initAction: function( event: Event, index: number, i: number ) {
      event.preventDefault();
      this.actions[ index ].state = 'loading';
      let currentAction = this.actions[ index ].actions[ i ];
      switch ( currentAction.type ) {
        case 'ajax':
          this.handleAjax( index, i );
          break;
        case 'change-page':
          const self = this;
          this.$store.state.tabs.map( function( element: any, i: number ) {
            if ( element.id === currentAction.handler ) {
              self.actions[ index ].state = false;
              self.$store.commit( 'changeTab', i );
            }
          } );
          break;
        case 'handle-plugin':
          this.handlePlugin( index, i );
          break;
        case 'skip-action':
          this.skipAction( index, i );
          break;
        default:
          this.handleOption( index, i );
          break;
      }
    }
  },
  /**
   * Before mount, create the data model
   */
  beforeMount: function() {
    const self = this;

    this.actions.map( function( element: any, index: number ) {
      element.visible = true;
      element.actions.push( { label: self.translations.skipAction, type: 'skip-action', handler: null } );
    } );

    self.checkOptionVisibility();
  }
} );
Vue.component( 'recommended-actions', dashboardRecommendedActions );
