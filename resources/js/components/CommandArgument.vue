<template>
    <input
        type="text"
        :placeholder="argument.description"
        class="border border-danger rounded text-sm text-danger text-center mr-1"
        size="40"
        style="padding: 2px; margin-top: -1px;"
        :value="argument.value"
        @input="argument.value = $event.target.value"
    >
</template>

<script>
export default {
    props: ['argument'],
}
</script>
