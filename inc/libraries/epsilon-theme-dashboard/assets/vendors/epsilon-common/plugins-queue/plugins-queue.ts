import './../plugins/plugins.scss';
import './../../epsilon-common/epsilon-fields/epsilon-toggle/epsilon-toggle.scss';
import Vue from 'vue';
import { EpsilonFetchTranslator } from '../../epsilon-fetch-translator';

declare let wp: any, ajaxurl: string, jQuery: any;

export const dashboardPluginsQueue: any = Vue.extend( {
  /**
   * Plugins component for onboarding
   */
  name: 'plugins-queue',
  /**
   * Plugins model
   * @returns {{ plugins: any[] }}
   */
  data: function() {
    return {
      translations: {
        activateOnly: this.$store.state.translations.activateOnly,
        installAndActivate: this.$store.state.translations.installAndActivate,
        installing: this.$store.state.translations.installing,
        skipping: this.$store.state.translations.skipping,
        activating: this.$store.state.translations.activating,
        version: this.$store.state.translations.version,
        recommended: this.$store.state.translations.recommended,
        integration: this.$store.state.translations.integration,
        installPlugins: this.$store.state.translations.installPlugins,
      },
      plugins: [],
      installationQueue: [],
      installerQueue: null,
      pluginsInstalled: [],
      pluginsInstalling: false,
      pluginsFinished: false,
      pluginsQueued: false,
      pluginsCount: 0,
    };
  },
  template: `
    <div>
      <transition-group tag="div" name="demo-complete" class="row">
        <div class="epsilon-minimal-plugin-box demo-complete-item" v-for="(plugin, index) in plugins" :key="index">
          <span v-html="plugin.info.name"></span>
          <em class="epsilon-highlighted">
              <span v-if="plugin.recommended">( {{ translations.recommended }} )</span>
              <span v-else-if="plugin.integration">( {{ translations.integration }} )</span>
          </em>
          
          <template v-if="plugins[index].active">
            <span class="epsilon-plugin-box--action-button completed">
                <span class="dashicons dashicons-yes"></span>
            </span>
          </template>
          <template v-else-if="pluginsQueued">
            <span v-if="installationQueue[index].install" class="epsilon-plugin-box--action-info">{{ translations.installing }}</span>
            <span v-else class="epsilon-plugin-box--action-info">{{ translations.skipping }}</span>
          </template>
          <template v-else>
            <div class="checkbox_switch">
              <div class="onoffswitch">
                <input type="checkbox" :id="'epsilon-plugin' + index" :name="'epsilon-plugin' + index" v-model="installationQueue[index].install" class="onoffswitch-checkbox">
                <label class="onoffswitch-label" :for="'epsilon-plugin' + index"></label>
              </div>
            </div>
          </template>
        </div>
      </transition-group>
    </div>
  `,
  methods: {
    /**
     * Removes duplicates
     * @param {string} id
     */
    removeDupes: function( id: string ) {
      this[ id ] = this[ id ].filter( function( item: any, pos: any, ary: any ) {
        return ! pos || item != ary[ pos - 1 ];
      } );
    },
    /**
     * Map changes in queue
     */
    mapChangesInQueue: function( args: { action: string, from: number } ) {
      const self = this;
      self.pluginsCount = 0;
      self.installationQueue.map( function( element: { install: boolean }, index: number ) {
        if ( self.installationQueue[ index ].install ) {
          self.pluginsCount += 1;
        }
      } );

      if ( 0 === self.pluginsCount ) {
        setTimeout(
            function() {
              self.$root.$emit( 'change-step', args );
            }, 150
        );
      }
    },
    /**
     * Map Plugins
     */
    mapPlugins: function() {
      const self = this;
      self.plugins.map( function( element: { label: string, slug: string, installed: boolean, active: boolean }, index: number ) {
        if ( self.installationQueue[ index ].install ) {
          self.pluginsCount += 1;
        }
      } );
    },

    /**
     *
     * @param {} args
     */
    handlePlugins: function( args: { action: string, from: number } ) {
      const self = this;

      this.pluginsQueued = true;

      if ( 0 === this.pluginsCount ) {
        setTimeout(
            function() {
              self.$root.$emit( 'change-step', args );
            }, 150
        );
        return;
      }

      self.mapChangesInQueue( args );

      self.installerQueue = setInterval( function() {
        self.plugins.map( function( element: { id: string, label: string, slug: string, installed: boolean, active: boolean }, index: number ) {
          if ( self.installationQueue[ index ].install ) {
            element.slug = element.id;
            self._handlePlugin( index, element, args );
          }
        } );
      }, 1000 );
    },
    /**
     * Handles plugin installation
     *
     * @param index
     * @param element
     * @param args
     * @private
     */
    _handlePlugin( index: number, element: any, args: { action: string, from: number } ) {
      const self = this;
      self.removeDupes( 'pluginsInstalled' );

      if ( self.pluginsInstalled.length >= self.pluginsCount ) {
        clearInterval( self.installerQueue );
        if ( ! self.pluginsFinished ) {
          self.$root.$emit( 'change-step', args );
        }

        self.installerQueue = null;
        self.pluginsFinished = true;
        self.pluginsQueued = false;
        return;
      }

      if ( this.pluginsInstalled.indexOf( element.slug ) > - 1 ) {
        return;
      }

      if ( this.pluginsInstalling ) {
        return;
      }

      if ( element.active ) {
        return;
      }

      this.pluginsInstalling = true;

      jQuery( document ).on( 'wp-plugin-install-success', function( event: JQueryEventConstructor, response: any ) {
        self._activatePlugin( index, response );
      } );

      /**
       * Plugin installed and not activated
       */
      if ( element.installed && ! element.active ) {
        this._activatePlugin( index, false );
      }

      if ( ! element.installed ) {
        this._installPlugin( index, element.slug );
      }
    },

    /**
     * Activate a plugin by index
     *
     * @param {number} index
     * @param {any} response
     * @private
     */
    _activatePlugin: function( index: number, response: any ) {
      const self = this;
      this.plugins[ index ].state = 'activating';
      if ( ! response ) {
        response = {
          activateUrl: this.plugins[ index ].url,
          slug: this.plugins[ index ].slug,
        };
      }

      jQuery.ajax( {
        async: true,
        type: 'GET',
        dataType: 'html',
        url: response.activateUrl,
        success: function( res: any ) {
          self.plugins[ index ].active = true;
          self.pluginsInstalled.push( response.slug );
          self.pluginsInstalling = false;

          self.$store.commit( 'setPluginInstalled', response.slug );
        }
      } );
    },

    /**
     * Install a plugin by index
     * @param {number} index
     * @private
     */
    _installPlugin: function( index: number ) {
      this.plugins[ index ].state = 'installing';
      wp.updates.installPlugin( {
        slug: this.plugins[ index ].info.slug,
      } );
    }
  },
  /**
   * Before mount, load resources
   */
  beforeMount: function() {
    const self = this;
    let fetchObj: EpsilonFetchTranslator,
        data = {
          action: 'epsilon_dashboard_ajax_callback',
          nonce: this.$store.state.ajax_nonce,
          args: {
            action: [ 'Epsilon_Dashboard_Helper', 'format_plugins' ],
            nonce: this.$store.state.ajax_nonce,
            args: {
              theme: this.$store.state.theme,
              plugins: this.$store.state.plugins,
            },
          },
        };
    fetchObj = new EpsilonFetchTranslator( data );

    fetch( ajaxurl, fetchObj ).then( function( res ) {
      return res.json();
    } ).then( function( json ) {
      if ( json.status && json.plugins.length ) {
        self.plugins = json.plugins;
        for ( let i = 0; i < self.plugins.length; i ++ ) {
          self.installationQueue.push( { install: ! json.plugins[ i ].active } );
        }
      }

      self.mapPlugins();
    } );
  },
  /**
   * Mounted lifecycle
   */
  created: function() {
    this.$root.$on( 'install-plugins', this.handlePlugins );
  },
} );
Vue.component( 'plugins-queue', dashboardPluginsQueue );
