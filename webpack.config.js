
const {join, resolve} = require('path')

const tsSrc = join(__dirname,'ts')

module.exports = {
    entry: {
        'advanced': join(tsSrc,'advanced/advanced.ts'),
        'buy': join(tsSrc,'buy/buy.ts'),
        'cart': join(tsSrc,'cart/cart.ts'),
        'confirm': join(tsSrc,'confirm/confirm.ts'),
        'constants': join(tsSrc,'constants/constants.ts'),
        'contacts': join(tsSrc,'contacts/contacts.ts'),
        'create': join(tsSrc,'create/insertion.ts'),
        'edit': join(tsSrc,'edit/edit.ts'),
        'footer': join(tsSrc,'footer/footer.ts'),
        'info': join(tsSrc,'info/info.ts'),
        'insertions': join(tsSrc,'insertions/insertions.ts'),
        'log_into': join(tsSrc,'log_into/log_into.ts'),
        'logout': join(tsSrc,'logout/logout.ts'),
        'orders': join(tsSrc,'orders/orders.ts'),
        'product': join(tsSrc,'product/product.ts'),
        'recovery': join(tsSrc,'recovery/recovery.ts'),
        'reset': join(tsSrc,'reset/reset.ts'),
        'subscribe': join(tsSrc,'subscribe/subscribe.ts'),
        'welcome': join(tsSrc,'welcome/welcome.ts'),
    },
    output: {
        path: path.resolve(__dirname,'js'),
        filename: '[name].js'
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