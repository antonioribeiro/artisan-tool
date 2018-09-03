<template>
    <button
        :class="_buttonClasses + ' text-xs text-blue-dark font-semibold py-1 px-2 m-1 border border-blue rounded'"
        @click="toggle"
    >
        {{ option.name }}
    </button>
</template>

<script>
export default {
    props: ['option'],

    computed: {
        _buttonClasses() {
            return this.option.selected
                ? 'hover:bg-danger-10% hover:border-transparent hover:text-white bg-danger text-white'
                : 'hover:bg-primary-10% hover:border-transparent hover:text-black bg-transparent text-black'
        },
    },

    methods: {
        toggle() {
            this.$emit('toggle-selected-option', this.option.name)
        },
    },
}
</script>
