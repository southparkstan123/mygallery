<template>
    <select class="form-control">
        <option disabled value="">Please select one</option>
        <option value="" v-bind:key="x.id" v-for="x in list">{{x.name}}</option>
    </select>
</template>
<script>
export default {
    props: ['list']
}
</script>
