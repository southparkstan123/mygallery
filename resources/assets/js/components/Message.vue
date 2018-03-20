<template>
    <div class="alert" v-show="message" v-bind:class="{ 'alert-success': success, 'alert-danger': !success }" role="alert">
        {{ message }}
    </div>
</template>
<script>
export default {
    props: ['message', 'success']
}
</script>
