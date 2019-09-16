const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
	mode: "development",
	watch: true,
	
	entry: { 
	  main: 'main.js' ,
	  css: './css/main.scss'
	},

	output: {
	  path: path.resolve(__dirname, 'assets'),
	  filename: '[name].js'
	},
	resolve: {
    	modules: [
    		path.resolve(__dirname, 'js'),
    		path.resolve('../default/js'),	// include JS from default theme
    		path.resolve('../default/css'),	// include CSS from default theme
    		path.resolve('./node_modules'),
    	],
		alias: {
			themeJS: path.resolve(__dirname, "js"),			// path to theme JS
			defaultJS: path.resolve(__dirname, "../default/js"),		// path to default JS
			themeCSS: path.resolve(__dirname, "css"),		// path to theme CSS
			defaultCSS: path.resolve(__dirname, "../default/css")		// path to default CSS
		}
  	},
  module: {
    rules: [
	  {
		test: require.resolve('react'),
		use: [{
			loader: 'expose-loader',
			options: 'React'
	  	}],
	  },
	  {
		test: require.resolve('react-dom'),
		use: [{
			loader: 'expose-loader',
			options: 'ReactDOM'
	  	}],
	  },
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: { "presets": ["@babel/preset-env", "@babel/preset-react"] }
        }
      },
      {
        test: /\.html$/,
        use: [
          {
            loader: "html-loader"
          }
        ]
      },
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader']
      },
      {
        test: /\.scss$/,
        use: ['style-loader', 'css-loader', 'sass-loader']
      },
      { test: /\.(png|woff|woff2|eot|ttf|svg|otf)$/, 
        use: [{
            loader: "url-loader",
            options: { "limit": 100000 }
        }]
       },
       {
        test: require.resolve('jquery'),
        use: [{
            loader: 'expose-loader',
            options: '$'
        }]
    }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css',
      chunkFilename: '[id].css',
    })
  ],
  externals: {
     pawtucketUIApps: 'pawtucketUIApps',
  }
};
