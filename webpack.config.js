
const path = require('path')

module.exports = {
    entry: {
        index: './ts/index.ts'
    },
    output: {
        path: path.resolve(__dirname,'js'),
        filename: 'bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
                exclude: /node_modules/
            }
        ]
    },
    plugins: [],
    resolve: {
      extension: ['.tsx','.ts','.js']  
    },
    devServer: {
        port: 5000,
        open: true,
        static: path.resolve(__dirname)
    },
    mode: 'development'
}