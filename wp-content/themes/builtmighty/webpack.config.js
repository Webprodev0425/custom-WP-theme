const path = require("path")

// minification/compression
const UglifyJSPlugin = require("uglifyjs-webpack-plugin")
const TerserJSPlugin = require("terser-webpack-plugin")
const CompressionPlugin = require("compression-webpack-plugin")

// scss config stuff
const MiniCssExtractPlugin = require("mini-css-extract-plugin")
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin")

const { CleanWebpackPlugin } = require("clean-webpack-plugin")

const config = {
  entry: {
    theme: ["./assets/js/frontend.js", "./assets/sass/style.scss"],
    admin: ["./assets/sass/admin-style.scss"],
  },
  output: {
    chunkFilename: "./assets/js/build/lazy/[id].[hash].min.js",
    filename: "./assets/js/build/[name].min.js",
    path: path.resolve(__dirname),
    publicPath: "/wp-content/themes/builtmighty/",
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: "babel-loader",
        options: {
          presets: ["@babel/preset-env"],
        },
      },
      {
        test: /\.(sass|scss)$/,
        use: [MiniCssExtractPlugin.loader, "css-loader", "sass-loader"],
      },
      {
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, "css-loader"],
      },
    ],
  },
  plugins: [
    // extract css into dedicated file
    new MiniCssExtractPlugin({
      filename: "./assets/css/build/[name].min.css",
    }),
    // let's clean up our build cache
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ["./assets/js/build/lazy"],
    }),
    // compress it all for better mobile experience
    // new CompressionPlugin({
    //   filename: "[path][base].br",
    //   algorithm: "brotliCompress",
    //   test: /\.(js|css|html|svg)$/,
    //   compressionOptions: {
    //     // zlib’s `level` option matches Brotli’s `BROTLI_PARAM_QUALITY` option.
    //     level: 11,
    //   },
    //   minRatio: 0.8,
    //   deleteOriginalAssets: false,
    // }),
  ],
  optimization: {
    minimizer: [
      // enable the js minification plugin
      new UglifyJSPlugin({
        cache: true,
        parallel: true,
      }),
      new TerserJSPlugin({}),
      new OptimizeCSSAssetsPlugin({}),
    ],
  },
}

module.exports = config
