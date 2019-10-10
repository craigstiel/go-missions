<template>
    <div>
        <v-app-bar app clipped-left color="purple darken-3">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <span class="overline ml-3 mr-5" style="font-size: 20px !important; font-family: 'Archivo Black', sans-serif;">GO!Missions</span>
<!--            <v-text-field v-if="$auth.check()" solo-inverted flat hide-details label="Search" prepend-inner-icon="search"></v-text-field>-->
            <div class="flex-grow-1"></div>
            <LocaleSwitcher></LocaleSwitcher>
            <v-badge v-if="tasks_count > 0 && $auth.check()" class="align-self-center" style="margin-right: 22px" color="error">
                <template v-slot:badge>{{ tasks_count }}</template>
                <v-icon large color="grey" style="cursor: pointer" @click="$router.push({name: 'tasks'})">mail</v-icon>
            </v-badge>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" app clipped color="grey darken-4">
            <v-list v-if="!$auth.check()" dense class="grey darken-4">
                <<v-list-item @click="$router.push({name: 'register'})">
                <v-list-item-action><v-icon>fas fa-user-plus</v-icon></v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title class="grey--text">{{ $ml.with('VueJS').get('sign_up') }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
                <v-list-item @click="$router.push({name: 'login'})">
                    <v-list-item-action><v-icon>fas fa-sign-in-alt</v-icon></v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="grey--text">{{ $ml.with('VueJS').get('sign_in') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <v-list v-if="$auth.check()" dense class="grey darken-4">
                <v-list-item @click="$router.push({name: 'tasks'})">
                    <v-list-item-action><v-icon>fas fa-tasks</v-icon></v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="grey--text">{{ $ml.with('VueJS').get('all_tasks') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item @click="$router.push({name: 'add_task'})">
                    <v-list-item-action><v-icon>add</v-icon></v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="grey--text">{{ $ml.with('VueJS').get('add_task') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-divider :style="{'padding-bottom': user_status==='admin' ? '142%' : '160%'}"></v-divider>
                <v-list-item @click="$router.push({name: 'profile'})">
                    <v-list-item-action><v-icon>fas fa-users-cog</v-icon></v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="grey--text">{{ $ml.with('VueJS').get('profile') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-if="user_status === 'admin'" @click="$router.push({name: 'settings'})">
                    <v-list-item-action><v-icon>settings</v-icon></v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="grey--text">{{ $ml.with('VueJS').get('settings') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item @click.prevent="$auth.logout()">
                    <v-list-item-action><v-icon>fas fa-sign-out-alt</v-icon></v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="grey--text">{{ $ml.with('VueJS').get('sign_out') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-content>
            <v-container fluid class="grey grey darken-4 fill-height">
                <v-row justify="center" align="center">
                    <router-view></router-view>
                </v-row>
            </v-container>
            <v-footer dark padless style="position: fixed; bottom: 0; width: 100%;">
                <v-card class="flex" flat tile>
                    <v-card-text class="py-2 white--text text-center">
                        {{ new Date().getFullYear() }} — Craigstiel
                    </v-card-text>
                </v-card>
            </v-footer>
        </v-content>
    </div>
</template>

<script>
    import { MLBuilder } from 'vue-multilanguage';
    import LocaleSwitcher from "./useful/LocaleSwitcher";
    export default {
        components: {LocaleSwitcher},
        props: {
            source: String,
        },
        mounted: function () {
            let _this = this;
            this.$bus.$on("GetCount", () => {
                _this.GetCount();
            });
            this.$bus.$on("GetAdmin", () => {
                _this.GetAdmin();
            });
            if(this.$auth.check()) {
                _this.GetCount();
                _this.GetAdmin();
            }
        },
        data: () => ({
            drawer: null,
            tasks_count: null,
            user_status: null,
        }),
        methods:{
            GetCount: function () {
                let _this = this;
                axios.get('/task/count/get')
                    .then(function (response) {
                        _this.tasks_count = response.data.tasks_count;
                    });
            },
            GetAdmin: function () {
                let _this = this;
                axios.get('/profile/is_admin')
                    .then(function (response) {
                        _this.user_status = response.data.user_status;
                    });
            }
        }
    }
</script>

<style>
    #keep .v-navigation-drawer__border {
        display: none
    }
</style>
