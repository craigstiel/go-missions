<template>
    <v-app id="keep">
        <v-alert></v-alert>
        <router-view></router-view>
    </v-app>
</template>

<script>
    export default {
        created: function () {},
        props: {},
        data:() => ({}),
        computed:{
            title: function () {
                let name = this.$route.name;
                return this.$ml.with('VueJS').get(name);
            }
        },
    }
</script>
