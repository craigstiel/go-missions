<template>
    <div class="row">
        <div class="col-md-4">
            <v-card flat tile class="first-card">
                <v-container key="New tasks" fluid>
                    <div class="flex font-weight-light" style="font-size: 24px; font-family: 'Roboto', sans-serif;">New tasks</div>
                    <v-row>
                        <div class="flex-grow-1"></div>
                        <div style="text-align: center" v-if="new_progress">
                            <v-progress-circular indeterminate color="purple" style="margin: 10px; left: -350%"></v-progress-circular>
                        </div>
                        <v-col v-else v-for="task in new_tasks" :key="task.id" cols="12" sm="6" md="12">
                            <v-card max-width="344" class="mx-auto cards">
                                <v-card-title><a @click="show(task)" style="color:white; font-size: 18px">{{task.title}}</a></v-card-title>
                                <v-card-text style="margin-top: -10px;">{{task.short_description}}</v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
                <v-dialog v-model="dialog" hide-overlay persistent width="800">
                    <v-card>
                        <v-task v-model="value"></v-task>
                        <v-card-actions>
                            <div class="flex-grow-1"></div>
                            <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-card>
        </div>
        <div class="col-md-4">
            <v-card flat tile class="second-card">
                <v-container key="In procces" fluid>
                    <div class="flex font-weight-light" style="font-size: 24px; font-family: 'Roboto', sans-serif;">In procces</div>
                    <v-row>
                        <div class="flex-grow-1"></div>
                        <div style="text-align: center" v-if="active_progress">
                            <v-progress-circular indeterminate color="purple" style="margin: 10px; left: -350%"></v-progress-circular>
                        </div>
                        <v-col v-else v-for="task in active_tasks" :key="task.id" cols="12" sm="6" md="12">
                            <v-card max-width="344" class="mx-auto cards">
                                <v-card-title><a @click="show(task)" style="color:white; font-size: 18px">{{task.title}}</a></v-card-title>
                                <v-card-text style="margin-top: -10px;">{{task.short_description}}</v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
                <v-dialog v-model="dialog" hide-overlay persistent width="800">
                    <v-card>
                        <v-task v-model="value"></v-task>
                        <v-card-actions>
                            <div class="flex-grow-1"></div>
                            <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-card>
        </div>
        <div class="col-md-4">
            <v-card flat tile class="third-card">
                <v-container key="Done" fluid>
                    <div class="flex font-weight-light" style="font-size: 24px; font-family: 'Roboto', sans-serif;">Done</div>
                    <v-row>
                        <div class="flex-grow-1"></div>
                        <div style="text-align: center" v-if="completed_progress">
                            <v-progress-circular indeterminate color="purple" style="margin: 10px; left: -350%"></v-progress-circular>
                        </div>
                        <v-col v-else v-for="task in completed_tasks" :key="task.id" cols="12" sm="6" md="12">
                            <v-card max-width="344" class="mx-auto cards">
                                <v-card-title><a @click="show(task)" style="color:white; font-size: 18px">{{task.title}}</a></v-card-title>
                                <v-card-text style="margin-top: -10px;">{{task.short_description}}</v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
                <v-dialog v-model="dialog" hide-overlay persistent width="800">
                    <v-card>
                        <v-task v-model="value" :dialog="dialog"></v-task>
                        <v-card-actions>
                            <div class="flex-grow-1"></div>
                            <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-card>
        </div>
    </div>
</template>

<script>
    export default {
        mounted: function () {
            let _this = this;
            this.get_new_task();
            this.get_active_task();
            this.get_completed_task();
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
            value: null,
            status: null,
            new_progress: true,
            active_progress: true,
            completed_progress: true,
            dicts: {
                masters: [],
            },
        }),
        watch: {
            'value.status': function () {
                if(this.status !== this.value.status) {
                    this.new_progress = true;
                    this.active_progress = true;
                    this.completed_progress = true;
                    this.dialog = false;
                    this.get_new_task();
                    this.get_active_task();
                    this.get_completed_task();
                }
            }
        },
        methods: {
            show: function (task) {
                this.value = task;
                this.status = task.status;
                this.dialog = true;
            },
            get_new_task: function () {
                let _this = this;
                axios.get('/dict/new_tasks/get')
                    .then(function (response) {
                        _this.new_tasks = response.data.tasks;
                        _this.new_progress = false;
                    });
            },
            get_active_task: function () {
                let _this = this;
                axios.get('/dict/active_tasks/get')
                    .then(function (response) {
                        _this.active_tasks = response.data.tasks;
                        _this.active_progress = false;
                    });
            },
            get_completed_task: function () {
                let _this = this;
                axios.get('/dict/completed_tasks/get')
                    .then(function (response) {
                        _this.completed_tasks = response.data.tasks;
                        _this.completed_progress = false;
                    });
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
