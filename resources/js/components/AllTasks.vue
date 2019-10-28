<template>
    <v-row>
        <v-col cols="12" md="12">
        <v-toolbar v-if="user_status === 'admin'" class="col-md-12" color="purple darken-3" dark style="height: 45px;">
            <v-col cols="12" md="2" key=2 style="margin-top: -55px;">
                <div class="my-2 mt-5">
                    <v-btn small color="error" v-if="all_tasks === false" style="align-self: normal;"
                           @click="all_tasks = false" dark>{{ $ml.with('VueJS').get('only_my') }}</v-btn>
                    <v-btn small color="error" v-else style="align-self: normal" outlined @click="all_tasks = false" dark>{{ $ml.with('VueJS').get('only_my') }}</v-btn></div>
            </v-col>
            <v-col cols="12" md="2" key=3 style="margin-top: -55px;" class="all-btn">
                <div class="my-2 mt-5">
                    <v-btn small color="error" v-if="all_tasks === true" style="align-self: normal;" @click="all_tasks = true" dark>{{ $ml.with('VueJS').get('all') }}</v-btn>
                    <v-btn small color="error" v-else style="align-self: normal;" outlined @click="all_tasks = true" dark>{{ $ml.with('VueJS').get('all') }}</v-btn>
                </div>
            </v-col>
        </v-toolbar>
        </v-col>
        <v-col cols="12" md="4" sm="12">
            <v-card flat tile class="first-card">
                <v-container key="New tasks" fluid>
                    <v-badge class="align-self-center titles" style="margin-right: 22px" color="purple lighten-2">
                        <template v-slot:badge>{{ new_tasks.length }}</template>
                        <span style="font-size: 24px; font-family: 'Roboto', sans-serif; margin-left: 20px">
                            {{ $ml.with('VueJS').get('new_tasks') }}</span>
                    </v-badge>
                    <v-row>
                        <div style="text-align: center" v-if="new_progress">
                            <v-progress-circular indeterminate color="purple" style="margin: 10px; left: -350%"></v-progress-circular>
                        </div>
                        <v-col v-else v-for="task in new_tasks" :key="task.id" cols="12" sm="6" md="12">
                            <v-card @click="show(task)" class="mx-auto cards" style="cursor: pointer">
                                <v-card-title><a style="color:white; font-size: 18px; line-height: 20px;">{{task.title}}</a></v-card-title>
                                <v-card-text>{{task.short_description}}</v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-col>
        <v-col cols="12" md="4" sm="12">
            <v-card flat tile class="second-card">
                <v-container key="In procces" fluid>
                    <v-badge class="align-self-center titles" style="margin-right: 22px" color="purple lighten-2">
                        <template v-slot:badge>{{ active_tasks.length }}</template>
                        <span style="font-size: 24px; font-family: 'Roboto', sans-serif; margin-left: 20px">
                            {{ $ml.with('VueJS').get('in_progress') }}</span>
                    </v-badge>
                    <v-row>
                        <div class="flex-grow-1"></div>
                        <div style="text-align: center" v-if="active_progress">
                            <v-progress-circular indeterminate color="purple" style="margin: 10px; left: -350%"></v-progress-circular>
                        </div>
                        <v-col v-else v-for="task in active_tasks" :key="task.id" cols="12" sm="6" md="12">
                            <v-card @click="show(task)" style="cursor: pointer" class="mx-auto cards">
                                <v-card-title><a style="color:white; font-size: 18px; line-height: 20px;">{{task.title}}</a></v-card-title>
                                <v-card-text>{{task.short_description}}</v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
                <v-dialog v-model="dialog">
                    <v-card>
                        <v-task v-model="value"></v-task>
                        <v-card-actions>
                            <div class="flex-grow-1"></div>
                            <v-btn color="blue darken-1" text @click="dialog = false" style="margin-top: -30px">{{ $ml.with('VueJS').get('close') }}</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-card>
        </v-col>
        <v-col cols="12" md="4" sm="12">
            <v-card flat tile class="third-card">
                <v-container key="Done" fluid>
                    <v-badge class="align-self-center titles" style="margin-right: 22px" color="purple lighten-2">
                        <template v-slot:badge>{{ completed_tasks.length }}</template>
                        <span style="font-size: 24px; font-family: 'Roboto', sans-serif; margin-left: 20px">
                            {{ $ml.with('VueJS').get('done') }}</span>
                    </v-badge>
                    <v-row>
                        <div class="flex-grow-1"></div>
                        <div style="text-align: center" v-if="completed_progress">
                            <v-progress-circular indeterminate color="purple" style="margin: 10px; left: -350%"></v-progress-circular>
                        </div>
                        <v-col v-else v-for="task in completed_tasks" :key="task.id" cols="12" sm="6" md="12">
                            <v-card @click="show(task)" class="mx-auto cards" style="cursor: pointer">
                                <v-card-title><a style="color:white; font-size: 18px; line-height: 20px;">{{task.title}}</a></v-card-title>
                                <v-card-text>{{task.short_description}}</v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        mounted: function () {
            let _this = this;
            this.is_admin();
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
            user_status: null,
            status: null,
            new_progress: true,
            all_tasks: false,
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
            },
            all_tasks: function () {
                this.new_progress = true;
                this.active_progress = true;
                this.completed_progress = true;
                this.get_new_task();
                this.get_active_task();
                this.get_completed_task();
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
                if(this.all_tasks === false) {
                    axios.get('/dict/new_tasks/get')
                        .then(function (response) {
                            _this.new_tasks = response.data.tasks;
                            _this.new_progress = false;
                        });
                } else {
                    axios.get('/dict/new_tasks/get/all')
                        .then(function (response) {
                            _this.new_tasks = response.data.tasks;
                            _this.new_progress = false;
                        });
                }
            },
            get_active_task: function () {
                let _this = this;
                if(this.all_tasks === false) {
                    axios.get('/dict/active_tasks/get')
                        .then(function (response) {
                            _this.active_tasks = response.data.tasks;
                            _this.active_progress = false;
                        });
                } else {
                    axios.get('/dict/active_tasks/get/all')
                        .then(function (response) {
                            _this.active_tasks = response.data.tasks;
                            _this.active_progress = false;
                        });
                }
            },
            get_completed_task: function () {
                let _this = this;
                if(this.all_tasks === false) {
                    axios.get('/dict/completed_tasks/get')
                        .then(function (response) {
                            _this.completed_tasks = response.data.tasks;
                            _this.completed_progress = false;
                        });
                } else {
                    axios.get('/dict/completed_tasks/get/all')
                        .then(function (response) {
                            _this.completed_tasks = response.data.tasks;
                            _this.completed_progress = false;
                        });
                }
            },
            is_admin: function () {
                let _this = this;
                axios.get('/profile/is_admin')
                    .then(function (response) {
                        _this.user_status = response.data.user_status;
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
    @media (min-width: 960px) {
        .v-dialog {
            box-shadow: none !important;
            width: 70% !important;
        }
        .all-btn {
            margin-left: -30px
        }
    }
    @media (max-width: 960px) {
        .v-dialog {
            box-shadow: none !important;
            width: 100% !important;
        }
        .all-btn {
            margin-left: -60px
        }
    }
    @media (min-width: 1904px) {
        .all-btn {
            margin-left: -250px
        }
    }
    @media (max-width: 600px) {
        .titles {
            margin-top: 20px;
        }
    }
    @media (min-width: 1264px) and (max-width: 1904px) {
        .all-btn {
            margin-left: -60px
        }
    }
</style>
