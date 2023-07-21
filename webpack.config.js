
const {join,resolve} = require('path')

const srcPath = join(__dirname,'src')

const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const FixStyleOnlyEntriesPlugin = require('webpack-fix-style-only-entries')

module.exports = {
    entry: {
        'js/advanced/advanced': join(srcPath,'ts/advanced/advanced.ts'),
        'js/buy/buy': join(srcPath,'ts/buy/buy.ts'),
        'js/cart/cart': join(srcPath,'ts/cart/cart.ts'),
        'js/confirm/confirm': join(srcPath,'ts/confirm/confirm.ts'),
        'js/confirm/confirm.functions': join(srcPath,'ts/confirm/confirm.functions.ts'),
        /* 'js/constants/constants': join(srcPath,'ts/constants/constants.ts'), */
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
        'css/advanced/advanced': join(srcPath,'scss/advanced/advanced.scss'),
        'css/buy/buy': join(srcPath,'scss/buy/buy.scss'),
        'css/cart/cart': join(srcPath,'scss/cart/cart.scss'),
        'css/confirm/confirm': join(srcPath,'scss/confirm/confirm.scss'),
        'css/contacts/contacts': join(srcPath,'scss/contacts/contacts.scss'),
        'css/create/create': join(srcPath,'scss/create/create.scss'),
        'css/edit/edit': join(srcPath,'scss/edit/edit.scss'),
        'css/footer/footer': join(srcPath,'scss/footer/footer.scss'),
        'css/info/info': join(srcPath,'scss/info/info.scss'),
        'css/insertions/insertions': join(srcPath,'scss/insertions/insertions.scss'),
        'css/log_into/log_into': join(srcPath,'scss/log_into/log_into.scss'),
        'css/orders/orders': join(srcPath,'scss/orders/orders.scss'),
        'css/product/product': join(srcPath,'scss/product/product.scss'),
        'css/recovery/recovery': join(srcPath,'scss/recovery/recovery.scss'),
        'css/search/search': join(srcPath,'scss/search/search.scss'),
        'css/subscribe/subscribe': join(srcPath,'scss/subscribe/subscribe.scss'),
        'css/welcome/welcome': join(srcPath,'scss/welcome/welcome.scss'),
        'css/recovery/recovery': join(srcPath,'scss/recovery/recovery.scss'),
        
        
    },
    output: {
        path: resolve(__dirname,'dist'),
        filename: '[name].js',
        clean: true
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
    plugins: [
        new FixStyleOnlyEntriesPlugin(),
        new MiniCssExtractPlugin({
        filename: '[name].css'
    })],
    resolve: {
      extensions: ['.tsx','.ts','.js']  
    },
    devServer: {
        port: 5000,
        open: true,
        static: resolve(__dirname),
    },
}