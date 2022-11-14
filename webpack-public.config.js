const path = require('path')
const webpack = require('webpack')
const { VueLoaderPlugin } = require('vue-loader')
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const CopyPlugin = require("copy-webpack-plugin")
const TerserPlugin = require("terser-webpack-plugin")

var BUILD_DIR = path.resolve(__dirname, 'public')

module.exports = {    
    mode: 'development',
    // mode: 'production', 
    entry: {
        //'assets/js/public/app.js': './resources/js/public/app.js', // scripts
        //'css/main.min.css': './resources/css/public/main.css' //styles
        app: [
            './resources/js/public/main.js',
            './resources/js/public/app.js', 
            './resources/css/public/main.css'
        ],
    },
    output: {
        path: BUILD_DIR + '/',
        filename: 'assets/js/public/[name].js', 
        //assetModuleFilename: 'images/[name][ext]'
        //sourceMapFilename: "js/[name].js.map"
        // clean: true,
    },
    resolve: {
        extensions: ['\*', '.js', '.jsx', '.vue'], 
        symlinks: false,
        alias: {
            "@": path.resolve(__dirname, '../src'),
            'vue$': 'vue/dist/vue.esm-bundler.js',
            vue: path.resolve(__dirname, `./node_modules/vue`)
        },
    },    
    //devtool: "source-map",
    module: {
        rules: [
            //{
              //  test: /\.js$/,
                //exclude: /node_modules/,
                /*loader: 'babel-loader',
                options: {
                    presets: ['@babel/preset-env']
                }*/
            //},
            {
                test: /\.vue$/,
                exclude: /node_modules/,
                loader: 'vue-loader'
            },
            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader, 
                    {
                        loader: "css-loader",
                        options: {                          
                            url: false
                        },
                    },
                ], //'vue-style-loader',                
            },
        ]
    },
    optimization: {
        minimizer: [            
            new CssMinimizerPlugin(), 
            new TerserPlugin({
                parallel: true,
                terserOptions: {
                  // https://github.com/webpack-contrib/terser-webpack-plugin#terseroptions
                },
              }),           
        ],        
    },
    plugins: [     
        new webpack.DefinePlugin({
            //'process.env': 'development',            
            __VUE_OPTIONS_API__: true,
            __VUE_PROD_DEVTOOLS__: true,
          }),   
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin({
            filename: 'css/main.css', 
        }),
        new CopyPlugin({
            patterns: [{ 
                        from: "resources/js/public/*.*", 
                            filter: async (resourcePath) => {
                                const fileName = path.basename(resourcePath);
                                //console.log(fileName);
                                if (
                                    (
                                        /^(jquery)/i.test(fileName) || 
                                        /^(auth)/i.test(fileName)
                                    )
                                    && /\.js$/i.test(fileName)
                                ) {
                                    return true;
                                }                                

                                return false;
                            },
                            to: "assets/js/public/[name][ext]" 
                        },
                        // { 
                        //     from: "src/images",                             
                        //     to: "images"
                        // },
                        // { 
                        //     from: "src/css",                             
                        //     to: "css"
                        // }
            ],
        }),
    ]
};