<template>
    <div>
        <v-app-bar app clipped-left color="purple darken-3">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <span class="overline ml-3 mr-5" style="font-size: 20px !important; font-family: 'Archivo Black', sans-serif;">GO!Missions</span>
            <v-text-field solo-inverted flat hide-details label="Search" prepend-inner-icon="search"></v-text-field>
            <div class="flex-grow-1"></div>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" app clipped color="grey darken-4">
            <v-list dense class="grey darken-4">
                <template v-for="(item, i) in items">
                    <v-row v-if="item.heading" :key="i" align="center">
                        <v-col cols="6">
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-col>
                    </v-row>
                    <v-divider v-else-if="item.divider" :key="i" dark class="my-4"
                               style="padding-bottom: 160%"></v-divider>
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
        </v-navigation-drawer>

        <v-content>
            <v-container fluid class="grey grey darken-4 fill-height">
                <v-row justify="center" align="center">
                    <router-view></router-view>
                </v-row>
            </v-container>
        </v-content>
    </div>
</template>

<script>
    export default {
        props: {
            source: String,
        },
        data: () => ({
            drawer: null,
            items: [
                {icon: 'fas fa-tasks', text: 'Task board', link: 'tasks'},
                {icon: 'add', text: 'Add task', link: 'add_task'},
                {divider: true},
                {icon: 'settings', text: 'Settings'},
                {icon: 'fas fa-users-cog', text: 'Profile'},
            ],
        }),
    }
</script>

<style>
    #keep .v-navigation-drawer__border {
        display: none
    }
</style>
