Nova.booting((Vue, router) => {
    Vue.component('command-modal', require('./components/CommandModal'))

    Vue.component('command-argument', require('./components/CommandArgument'))

    Vue.component('command-option', require('./components/CommandOption'))

    Vue.component('option-button', require('./components/OptionButton'))

    router.addRoutes([
        {
            name: 'artisan-tool',
            path: '/artisan-tool',
            component: require('./components/Tool'),
        },
    ])
})
