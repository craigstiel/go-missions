<template>
<v-select v-title="getTitle()"
        v-model="lang"
        :items="$ml.list"
        style="max-width: 50px; margin-right: 50px; margin-top: 20px;"
        rounded
    >
        <template slot="selection" slot-scope="data">
            <img :src="icons[data.item]" class="country-icon" />
        </template>
        <template slot="item" slot-scope="data">
            <img :src="icons[data.item]" class="country-icon" />
        </template>
    </v-select>
</template>

<script>
    import enIcon from 'svg-country-flags/svg/gb.svg'
    import ruIcon from 'svg-country-flags/svg/ru.svg'

    export default {
        created: function(){
            let st_lang = localStorage.getItem('current_language');
            if(st_lang !== null)
                this.lang = st_lang;
            else this.lang =  this.$ml.current;
            let data = {lang: this.lang};
            axios.post('/set_locale', data);
        },

        data() {
            return {
                lang: null,
                icons: {
                    english: enIcon,
                    russian: ruIcon,
                },
            }
        },
        watch:{
            lang: function () {
                this.$ml.change(this.lang);
                localStorage.setItem('current_language', this.lang);
                let data = {lang: this.lang};
                axios.post('/set_locale', data);
            }
        },
        methods: {
            getTitle: function () {
                let name = this.$route.name;
                return this.$ml.with('VueJS').get(name);
            }
        },
    }
</script>

<style scoped>
    .country-icon {
        width: 20px;
        height: auto;
        display: inline-block;
        vertical-align: baseline;
        border: 1px solid #dee2e6;
        box-shadow: 0px 1px 3px rgba(24, 29, 38, 0.1);
    }

    .country-icon.as-toggle {
        margin-top: 5px;
    }

    .locale-name {
        display: inline-block;
        vertical-align: baseline;
    }
</style>
