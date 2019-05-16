var path = require( 'path' );
var webpack = require( 'webpack' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const extractSass = new ExtractTextPlugin( {
  filename: '../css/[name].css'
} );

module.exports = {
  entry: {
    dashboard: './assets/vendors/epsilon-dashboard/dashboard.ts',
    onboarding: './assets/vendors/epsilon-onboarding/onboarding.ts',
    feedback: './assets/vendors/epsilon-feedback/feedback.ts'
  },
  output: {
    path: path.resolve( __dirname, './assets/js/' ),
    publicPath: '/assets/js/',
    filename: 'epsilon-[name].js'
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: {
            // Since sass-loader (weirdly) has SCSS as its default parse mode, we map
            // the "scss" and "sass" values for the lang attribute to the right configs here.
            // other preprocessors should work out of the box, no loader config like this necessary.
            'scss': 'vue-style-loader!css-loader!sass-loader',
            'sass': 'vue-style-loader!css-loader!sass-loader?indentedSyntax',
          }
          // other vue-loader options go here
        }
      },
      {
        test: /\.tsx?$/,
        loader: 'ts-loader',
        exclude: /node_modules/,
        options: {
          appendTsSuffixTo: [ /\.vue$/ ]
        }
      },
      {
        test: /\.(png|jpg|gif|svg)$/,
        loader: 'file-loader',
        options: {
          name: '[name].[ext]?[hash]',
          useRelativePath: true
        }
      },
      {
        test: /\.scss$/,
        use: extractSass.extract( {
          use: [
            {
              loader: 'css-loader'
            }, {
              loader: 'sass-loader'
            } ],
          // use style-loader in development
          fallback: 'style-loader'
        } )
      }
    ]
  },
  plugins: [
    extractSass
  ],
  resolve: {
    extensions: [ '.ts', '.js', '.vue', '.json' ],
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  devServer: {
    historyApiFallback: true,
    noInfo: true
  },
  performance: {
    hints: false
  },
  devtool: '#eval-source-map'
};

if ( process.env.NODE_ENV === 'production' ) {
  module.exports.devtool = '#source-map';
// http://vue-loader.vuejs.org/en/workflow/production.html
  module.exports.plugins = (module.exports.plugins || []).concat( [
    new webpack.DefinePlugin( {
      'process.env': {
        NODE_ENV: '"production"'
      }
    } ),
    new webpack.optimize.UglifyJsPlugin( {
      sourceMap: true,
      compress: {
        warnings: false
      }
    } ),
    new webpack.LoaderOptionsPlugin( {
      minimize: true
    } )
  ] );
}
