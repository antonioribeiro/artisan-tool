<template>
    <span v-if="option.selected">
        <span v-if="option.needsInput">
            <input
                type="text"
                :placeholder="option.name"
                class="border border-danger rounded text-sm text-danger text-center mr-1"
                size="10"
                style="padding: 2px; margin-top: -1px;"
                :value="option.value"
                @input="option.value = $event.target.value"
            >
        </span>
    </span>
</template>

<script>
export default {
    props: ['option'],
}
</script>
