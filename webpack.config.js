
const {join,resolve} = require('path')

const srcPath = join(__dirname,'src')

const MiniCssExtractPlugin = require('mini-css-extract-plugin')

module.exports = {
    entry: {
        'js/advanced/advanced': join(srcPath,'ts/advanced/advanced.ts'),
        'js/buy/buy': join(srcPath,'ts/buy/buy.ts'),
        'js/cart/cart': join(srcPath,'ts/cart/cart.ts'),
        'js/confirm/confirm': join(srcPath,'ts/confirm/confirm.ts'),
        'js/constants/constants': join(srcPath,'ts/constants/constants.ts'),
        'js/contacts/contacts': join(srcPath,'ts/contacts/contacts.ts'),
        'js/create/insertion': join(srcPath,'ts/create/insertion.ts'),
        'js/edit/edit': join(srcPath,'ts/edit/edit.ts'),
        'js/footer/footer': join(srcPath,'ts/footer/footer.ts'),
        'js/info/info': join(srcPath,'ts/info/info.ts'),
        'js/insertions/insertions': join(srcPath,'ts/insertions/insertions.ts'),
        'js/log_into/log_into': join(srcPath,'ts/log_into/log_into.ts'),
        'js/logout/logout': join(srcPath,'ts/logout/logout.ts'),
        'js/orders/orders': join(srcPath,'ts/orders/orders.ts'),
        'js/product/product': join(srcPath,'ts/product/product.ts'),
        'js/recovery/recovery': join(srcPath,'ts/recovery/recovery.ts'),
        'js/reset/reset': join(srcPath,'ts/reset/reset.ts'),
        'js/subscribe/subscribe': join(srcPath,'ts/subscribe/subscribe.ts'),
        'js/welcome/welcome': join(srcPath,'ts/welcome/welcome.ts'),
        'css/advanced/advanced': join(srcPath,'scss/advanced/advanced.scss')
        
    },
    output: {
        path: resolve(__dirname,'dist'),
        filename: '[name].js'
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
                exclude: /node_modules/
            },
            {
                test: /\.(css|s[ac]ss)$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader','sass-loader'],
                exclude: /node_modules/
            }
        ]
    },
    plugins: [new MiniCssExtractPlugin({
        filename: '[name].css'
    })],
    resolve: {
      extensions: ['.tsx','.ts','.js']  
    },
    devServer: {
        port: 5000,
        open: true,
        static: resolve(__dirname)
    },
    mode: 'development'
}