<template>
    <div class="row">
        <div class="col-md-4">
            <v-card flat tile class="first-card">
                <v-container key="New tasks" fluid>
                    <div class="flex font-weight-light" style="font-size: 24px; font-family: 'Roboto', sans-serif;">New tasks</div>
                    <v-progress-circular v-if="new_progress" indeterminate color="purple" style="position: unset !important"></v-progress-circular>
                    <v-row v-else>
                        <div class="flex-grow-1"></div>
                        <v-col v-for="task in new_tasks" :key="task.id" cols="12" sm="6" md="12">
                            <v-card max-width="344" class="mx-auto cards">
                                <v-dialog v-model="dialog" max-width="900">
                                    <template v-slot:activator="{ on }">
                                        <v-card-title v-on="on">
                                            <a @click="show(task.id)" style="color:white; font-size: 18px">{{task.title}}</a>
                                        </v-card-title>
                                    </template>
                                    <v-task :task="task"></v-task>
                                </v-dialog>
                                <v-card-text style="margin-top: -40px;">{{task.short_description}}</v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </div>
        <div class="col-md-4">
            <v-card flat tile class="second-card">
                <v-container key="In procces" fluid>
                    <div class="flex font-weight-light" style="font-size: 24px; font-family: 'Roboto', sans-serif;">In procces</div>
                    <v-progress-circular v-if="active_progress" indeterminate color="purple" style="position: unset !important"></v-progress-circular>
                    <v-row v-else>
                        <div class="flex-grow-1"></div>
                        <v-col v-for="task in active_tasks" :key="task.id" cols="12" sm="6" md="12">
                            <v-card max-width="344" class="mx-auto cards">
                                <v-card-title>{{task.title}}</v-card-title>
                                <v-card-text>{{task.short_description}}</v-card-text>
                                <v-card-actions>
                                    <v-btn text>Show</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </div>
        <div class="col-md-4">
            <v-card flat tile class="third-card">
                <v-container key="Done" fluid>
                    <div class="flex font-weight-light" style="font-size: 24px; font-family: 'Roboto', sans-serif;">Done</div>
                    <v-progress-circular v-if="completed_progress" indeterminate color="purple" style="position: unset !important"></v-progress-circular>
                    <v-row v-else>
                        <div class="flex-grow-1"></div>
                        <v-col v-for="task in completed_tasks" :key="task.id" cols="12" sm="6" md="12">
                            <v-card max-width="344" class="mx-auto cards">
                                <v-card-title>{{task.title}}</v-card-title>
                                <v-card-text>{{task.short_description}}</v-card-text>
                                <v-card-actions>
                                    <v-btn text>Show</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </div>
    </div>
</template>

<script>
    export default {
        mounted: function () {
            let _this = this;
            axios.get('/dict/new_tasks/get')
                .then(function (response) {
                    _this.new_tasks = response.data.tasks;
                    _this.new_progress = false;
                });
            axios.get('/dict/active_tasks/get')
                .then(function (response) {
                    _this.active_tasks = response.data.tasks;
                    _this.active_progress = false;
                });
            axios.get('/dict/completed_tasks/get')
                .then(function (response) {
                    _this.completed_tasks = response.data.tasks;
                    _this.completed_progress = false;
                });
            axios.get('/dict/masters/get')
                .then(function (response) {
                    _this.dicts.masters = response.data.masters;
                });
        },
        data: () => ({
            new_tasks: [],
            active_tasks: [],
            completed_tasks: [],
            dialog: false,
            new_progress: true,
            active_progress: true,
            completed_progress: true,
            dicts: {
                masters: [],
            },
        }),
        methods: {
            show: function (id) {

            }
        },
    }
</script>

<style>
    #app {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 600px;
        max-width: 100%;
        margin: 50px auto;
        position: relative;
    }
    .image {
        width: 400px;
        max-width: 100%;
    }
</style>
