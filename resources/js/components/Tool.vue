<template>
    <div>
        <heading class="mb-6">Artisan</heading>

        <div class="flex justify-between">
            <div class="relative h-9 flex items-center mb-6">
                <icon type="search" class="absolute ml-3 text-70" />

                <input
                    class="appearance-none form-control form-input w-search pl-search"
                    placeholder="Search"
                    type="search"
                    v-model="search"
                >
            </div>
        </div>

        <div class="container mx-auto flex">
            <div class="flex flex-wrap justify-between">
                <div
                    v-for="command in _commands"
                    @click="selectedCommand = command"
                    class="card flex items-center px-2 pl-1 pr-1 m-1 -mx-3 w-1/4 cursor-pointer hover:bg-primary-10%"
                >
                    <div class="container">
                        <div class="flex w-full">
                            <div class="flex flex-wrap w-full m-2 justify-center p-1">
                                <p class="font-semibold text-lg flex text-primary">
                                    {{ command.name }}
                                </p>

                                <p class="font-light text-xs flex w-full justify-center">
                                    {{ command.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <command-modal
            v-if="selectedCommand"
            @close="selectedCommand = null"
            :command="selectedCommand"
        />
    </div>
</template>

<script>
export default {
    data() {
        return {
            commands: false,
            search: '',
            selectedCommand: null,
        }
    },

    computed: {
        _commands() {
            return _.filter(this.commands, command => {
                return _.includes(command.name, this.search)
            })
        },
    },

    mounted() {
        Nova.request()
            .get('/nova-vendor/pragmarx/artisan-tool/artisan-tool')
            .then(({ data }) => {
                this.commands = data
            })
    },
}
</script>
