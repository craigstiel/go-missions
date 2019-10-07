<template>
    <div>
        <v-app-bar app clipped-left color="purple darken-3">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <span class="overline ml-3 mr-5" style="font-size: 20px !important; font-family: 'Archivo Black', sans-serif;">GO!Missions</span>
<!--            <v-text-field v-if="$auth.check()" solo-inverted flat hide-details label="Search" prepend-inner-icon="search"></v-text-field>-->
            <div class="flex-grow-1"></div>
            <v-badge v-if="tasks_count > 0" class="align-self-center" style="margin-right: 22px" color="error">
                <template v-slot:badge>{{ tasks_count }}</template>
                <v-icon large color="grey" style="cursor: pointer" @click="$router.push({name: 'tasks'})">mail</v-icon>
            </v-badge>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" app clipped color="grey darken-4">
            <v-list v-if="!$auth.check()" dense class="grey darken-4">
                <template v-for="(item, i) in auth">
                    <v-divider v-if="item.divider" :key="i" dark class="my-4"
                               style="padding-bottom: 142%"></v-divider>
                    <v-list-item v-else :key="i" @click="$router.push({name: item.link})">
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="grey--text">
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-list>
            <v-list v-if="$auth.check()" dense class="grey darken-4">
                <template v-for="(item, i) in items">
                    <v-divider v-if="item.divider" :key="i" dark class="my-4"
                               style="padding-bottom: 142%"></v-divider>
                    <v-list-item v-else :key="i" @click="$router.push({name: item.link})">
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="grey--text">
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
                <v-list-item @click.prevent="$auth.logout()">
                    <v-list-item-action><v-icon>fas fa-sign-out-alt</v-icon></v-list-item-action>
                    <v-list-item-content><v-list-item-title class="grey--text">Sign out</v-list-item-title></v-list-item-content>
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
    export default {
        props: {
            source: String,
        },
        mounted: function () {
            let _this = this;
            axios.get('/task/count/get')
                .then(function (response) {
                    _this.tasks_count = response.data.tasks_count;
                });
        },
        data: () => ({
            drawer: null,
            tasks_count: null,
            items: [
                {icon: 'fas fa-tasks', text: 'Task board', link: 'tasks'},
                {icon: 'add', text: 'Add task', link: 'add_task'},
                {divider: true},
                {icon: 'settings', text: 'Settings', link: 'settings'},
                {icon: 'fas fa-users-cog', text: 'Profile', link: 'profile'},
            ],
            auth: [
                {icon: 'fas fa-user-plus', text: 'Sign up', link: 'register'},
                {icon: 'fas fa-sign-in-alt', text: 'Sign in', link: 'login'},
            ],
        }),
    }
</script>

<style>
    #keep .v-navigation-drawer__border {
        display: none
    }
</style>
