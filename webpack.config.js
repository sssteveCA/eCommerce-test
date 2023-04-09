
const {join,resolve} = require('path')

const tsSrc = join(__dirname,'ts')

module.exports = {
    entry: {
        'advanced/advanced': join(tsSrc,'advanced/advanced.ts'),
        'buy/buy': join(tsSrc,'buy/buy.ts'),
        'cart/cart': join(tsSrc,'cart/cart.ts'),
        'confirm/confirm': join(tsSrc,'confirm/confirm.ts'),
        'constants/constants': join(tsSrc,'constants/constants.ts'),
        'contacts/contacts': join(tsSrc,'contacts/contacts.ts'),
        'create/insertion': join(tsSrc,'create/insertion.ts'),
        'edit/edit': join(tsSrc,'edit/edit.ts'),
        'footer/footer': join(tsSrc,'footer/footer.ts'),
        'info/info': join(tsSrc,'info/info.ts'),
        'insertions/insertions': join(tsSrc,'insertions/insertions.ts'),
        'log_into/log_into': join(tsSrc,'log_into/log_into.ts'),
        'logout/logout': join(tsSrc,'logout/logout.ts'),
        'orders/orders': join(tsSrc,'orders/orders.ts'),
        'product/product': join(tsSrc,'product/product.ts'),
        'recovery/recovery': join(tsSrc,'recovery/recovery.ts'),
        'reset/reset': join(tsSrc,'reset/reset.ts'),
        'subscribe/subscribe': join(tsSrc,'subscribe/subscribe.ts'),
        'welcome/welcome': join(tsSrc,'welcome/welcome.ts'),
    },
    output: {
        path: resolve(__dirname,'js'),
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
                test: /\.s[ac]ss$/,
                use: 'sass-loader',
                exclude: /node_modules/
            }
        ]
    },
    plugins: [],
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