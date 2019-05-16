import './plugins.scss';
import Vue from 'vue';
import { EpsilonFetchTranslator } from '../../epsilon-fetch-translator';

declare let wp: any, ajaxurl: string, jQuery: any;

export const dashboardPlugins: any = Vue.extend( {
  /**
   * Plugins component
   */
  name: 'plugins',
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
        activating: this.$store.state.translations.activating,
        version: this.$store.state.translations.version,
        recommended: this.$store.state.translations.recommended,
        integration: this.$store.state.translations.integration,
      },
      plugins: [],
    };
  },
  /**
   * Props component
   */
  props: [ 'expanded' ],
  /**
   * Template part
   */
  template: `
    <transition-group tag="div" name="demo-complete" class="row">
      <template v-if="expanded">
        <div class="col epsilon-plugin-box demo-complete-item" v-for="(plugin, index) in plugins" :key="index">
          <span v-if="plugin.recommended" class="epsilon-plugin-box--recommended">{{ translations.recommended }}</span>
          <span v-else-if="plugin.integration" class="epsilon-plugin-box--recommended">{{ translations.integration }}</span>
          <img :src="plugin.icon" alt="plugin box image">
          <span class="version">{{ translations.version }} {{ plugin.info.version }}</span>
          <span class="separator">|</span> <span v-html="plugin.info.author"></span>
          
          <div class="epsilon-plugin-box--action-bar">
              <span class="plugin_name"><span v-html="plugin.info.name"></span></span>            
          </div>
          <template v-if="plugins[index].active">
              <span class="epsilon-plugin-box--action-button completed">
                  <span class="dashicons dashicons-yes"></span>
              </span>
          </template>
          <template v-else>
              <span class="epsilon-plugin-box--action-button">
                  <a href="#" :disabled="plugins[index].installing" @click="handlePlugin( $event, index )" class="button"> {{ pluginAction( index ) }} </a>
              </span>
          </template>
        </div>
      </template>
      <template v-else>
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
          <template v-else>
            <span class="epsilon-plugin-box--action-button">
                <a href="#" :disabled="plugins[index].installing" @click="handlePlugin( $event, index )" class="button"> {{ pluginAction( index ) }} </a>
            </span>
          </template>
        </div>
      </template>
    </transition-group>
  `,
  methods: {
    /**
     * Plugin action
     * @param {number} index
     */
    pluginAction: function( index: number ) {
      if ( 'waiting' === this.plugins[ index ].state ) {
        if ( this.plugins[ index ].installed ) {
          return this.translations.activateOnly;
        }

        return this.translations.installAndActivate;
      }

      if ( 'installing' === this.plugins[ index ].state ) {
        return this.translations.installing;
      }

      if ( 'activating' === this.plugins[ index ].state ) {
        return this.translations.activating;
      }
    },
    /**
     * Handle plugin installation
     * @param {event} event
     * @param {number} index
     */
    handlePlugin: function( event: Event, index: number ) {
      event.preventDefault();
      const self = this;
      this.plugins[ index ].installing = true;

      jQuery( document ).one( 'wp-plugin-install-success', function( event: JQueryEventConstructor, response: any ) {
        self.plugins[ index ].installed = true;
        self._activatePlugin( index, response );
      } );

      if ( this.plugins[ index ].installed ) {
        this._activatePlugin( index, false );
        return;
      }

      this._installPlugin( index );
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
        };
      }

      jQuery.ajax( {
        async: true,
        type: 'GET',
        dataType: 'html',
        url: response.activateUrl,
        success: function( res: any ) {
          self.plugins[ index ].state = 'completed';
          self.plugins[ index ].active = true;
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
      }
    } );
  }
} );
Vue.component( 'plugins', dashboardPlugins );
