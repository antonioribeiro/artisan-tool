<template>
    <modal @modal-close="close">
        <div class="containerTty bg-white rounded shadow p-4 m-2 max-w-3xl max-h-screen overflow-y-scroll" style="width: calc(150vh - 16rem); height: calc(100vh - 16rem);">
            <div class="box box-1">
                <div class="mb-1">
                    <h1>{{ command.name }}</h1>
                </div>

                <div class="mb-4 text-xs">
                    <p>{{ command.description }}</p>
                </div>

                <div class="mb-4 text-primary text-lg">
                    <p>
                        <command-argument
                            v-for="argument in command.arguments"
                            :key="argument.name"
                            :argument.sync="argument"
                        ></command-argument>
                    </p>
                </div>

                <div class="flex flex-row justify-center">
                    <option-button
                        v-for="option in command.options"
                        :key="option.name"
                        :option="option"
                        @toggle-selected-option="toggle"
                    >
                    </option-button>
                </div>

                <div class="mb-4 text-primary text-lg">
                    <p>
                        <command-option
                            v-for="option in command.options"
                            :key="option.name"
                            :option.sync="option"
                        ></command-option>
                    </p>
                </div>

                <div class="mb-4 text-primary text-sm">
                    <p>
                        php artisan {{ _compiledCommand }}

                        <span>
                            <button
                                @click="run"
                                class="label bg-danger text-white hover:bg-danger cursor-pointer p-1 rounded"
                            >
                                run >>
                            </button>
                        </span>
                    </p>
                </div>
            </div>

            <div class="box box-2">
                <div class="relative overflow-hidden card h-full">
                    <div class="overflow-y-auto h-full" style="background-color: black;">
                        <pre
                            v-html="console"
                            class="p-4 font-mono text-white text-left leading-loose"
                        ></pre>
                    </div>
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
export default {
    props: ['command'],

    data() {
        return {
            console: '',
            running: false,
        }
    },

    computed: {
        _compiledCommand() {
            return (
                this.command.name +
                ' ' +
                _.join(this._compiledSelectedOptions, ' ') +
                ' ' +
                _.join(this._compiledArguments, ' ')
            )
        },

        _compiledSelectedOptions() {
            return _.map(
                _.filter(this.command.options, option => option.selected),
                option => {
                    return (
                        option.option + (option.value ? '=' + option.value : '')
                    )
                },
            )
        },

        _compiledArguments() {
            return _.map(
                this.command.arguments,
                argument =>
                    (argument.value == 'null' ||
                    argument.value == null ||
                    !argument.value
                        ? ''
                        : argument.value) + ' ',
            )
        },
    },

    methods: {
        close() {
            this.clearRun()

            this.$emit('close')

            this.running = false
        },

        toggle(elementName) {
            this.$set(
                this.command.options[elementName],
                'selected',
                !this.command.options[elementName].selected,
            )
        },

        argumentUpdated(argument, value) {
            this.command.arguments[argument.name].value = value
        },

        run() {
            this.running = true

            Nova.request()
                .get(
                    '/nova-vendor/pragmarx/artisan-tool/artisan-tool-run/' +
                        this._compiledCommand,
                )
                .then(({ data }) => {
                    this.commands = data

                    this.readConsole()
                })
        },

        clearRun() {
            Nova.request().get(
                '/nova-vendor/pragmarx/artisan-tool/artisan-tool-clear-tty',
            )
        },

        readConsole() {
            window.setInterval(() => {
                if (this.running) {
                    Nova.request()
                        .get(
                            '/nova-vendor/pragmarx/artisan-tool/artisan-tool-console-buffer',
                        )
                        .then(({ data }) => {
                            this.console = data
                        })
                }
            }, 1000)
        },
    },

    mounted() {
        this.readConsole()
    },
}
</script>
