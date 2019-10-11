<template>
    <v-card-text>
<!--        <v-container>-->
            <v-row v-if="task">
                <v-col cols="12" md="4" sm="4"><v-chip class="ma-2" color="primary" text-color="white">{{ status_name }}</v-chip></v-col>
                <v-col md="8" sm="8"></v-col>
                <v-col cols="12" md="8" sm="8" class="mg-task-20 xs-task-50 sm-task-30"><v-card-title>{{task.title}}</v-card-title></v-col>
                <v-col cols="6" md="2" sm="2" class="mg-task-20 xs-task-30 sm-task-30" v-if="task.user_position !== 'company'">
                    <div class="my-2 mt-5">
                        <v-btn v-if="task.status === 0" small color="success" dark @click="to_work()">{{$ml.with('VueJS').get('to_work')}}</v-btn>
                        <v-btn v-if="task.status === 1" small color="success" dark @click="done()">{{$ml.with('VueJS').get('to_done')}}</v-btn>
                    </div></v-col>
                <v-col cols="6" md="2" sm="2" class="mg-task-20 xs-task-30 sm-task-30">
                    <div class="my-2 mt-5" v-if="task.user_position === 'admin'"><v-btn @click="delete_task()" small color="error" dark>
                        {{ $ml.with('VueJS').get('delete') }}</v-btn></div>
                    <div class="my-2 mt-5" v-else></div></v-col>
                <v-col cols="12" md="10" sm="10" class="mg-task-30 sm-task-30"><v-card-text>{{task.description}}</v-card-text></v-col>
                <v-col md="2" sm="2" class="mg-task-30 sm-task-30"></v-col>
                <v-col cols="12" md="12" sm="12" class="mg-task-30 sm-task-30" style="padding-bottom: 20px" v-if="task.images[0]">
                    <v-card>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" md="6" sm="6" v-for="image in task.images" :key="image.id">
                                    <v-container>
                                        <v-prew-image class="image" :src="image.code"></v-prew-image>
                                    </v-container>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="2" sm="2" class="mg-task-20 sm-task-30"><v-card-text>{{$ml.with('VueJS').get('type')}}:</v-card-text></v-col>
                <v-col cols="12" md="3" sm="3" class="mg-task-20 sm-task-30"><v-card-text class="null-pad" :style="{'color': task.type_color}">
                    {{task.type}}</v-card-text></v-col>
                <v-col md="2" sm="1" class="mg-task-20 sm-task-30"></v-col>
                <v-col cols="5" md="2" sm="2" class="mg-task-20 sm-task-30"><v-card-text class="null-pad">{{$ml.with('VueJS').get('master')}}:</v-card-text></v-col>
                <v-col cols="7" md="3" sm="4" class="mg-task-20 sm-task-30" v-if="task.user_position !== 'admin'"><v-card-text>{{task.master}}</v-card-text></v-col>
                <v-col cols="7" md="3" sm="4" class="mg-task-20 sm-task-30" v-else>
                    <v-row>
                        <v-col cols="7" md="12" sm="12">
                            <v-select :items="dicts.masters" item-text="name" item-value="id" dense solo :label="task.master" v-model="master"></v-select>
                        </v-col>
                        <v-col cols="5" md="12" sm="12">
                            <v-btn @click="change_master()" :loading="loading" class="mg-task-30 sm-task-30" small color="error" dark>{{$ml.with('VueJS').get('change')}}</v-btn>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="5" md="2" sm="2" class="mg-task-120 sm-task-120"><v-card-text>{{$ml.with('VueJS').get('date')}}:</v-card-text></v-col>
                <v-col cols="7" md="3" sm="5" class="mg-task-120 sm-task-120"><v-card-text>{{task.created_at}}</v-card-text></v-col>
                <v-col cols="0" md="7" sm="5" class="mg-task-120 sm-task-120"></v-col>
                <v-col cols="12" md="2" sm="2" class="mg-task-50 sm-task-50" style="margin-left: 15px">{{$ml.with('VueJS').get('client')}}:</v-col>
                <v-col cols="12" md="5" sm="5" class="mg-task-50 sm-task-50">
                    <v-card>
                        <v-card-text>
                            <v-container>
                                <v-card-title style="font-size: 16px">{{task.client}}</v-card-title>
                                <v-card-text>{{task.client_phone}}</v-card-text>
                                <v-card-text class="mg-task-20 sm-task-20">{{task.client_email}}</v-card-text>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="2" sm="2"><v-card-text class="mg-task-20 null-pad md-null-pad">{{$ml.with('VueJS').get('priority')}}:</v-card-text></v-col>
                <v-col cols="12" md="3" sm="3" style="margin-left: -30px" class="mg-task-20 null-pad">
                    <v-chip v-if="task.priority === 'medium'" class="ma-2" color="warning" text-color="white"><v-avatar left>
                        <v-icon>mdi-chevron-up</v-icon></v-avatar>{{$ml.with('VueJS').get('medium')}}</v-chip>
                    <v-chip v-if="task.priority === 'low'" class="ma-2" color="success" text-color="white"><v-avatar left>
                        <v-icon>mdi-chevron-down</v-icon></v-avatar>{{$ml.with('VueJS').get('low')}}</v-chip>
                    <v-chip v-if="task.priority === 'high'" class="ma-2" color="error" text-color="white"><v-avatar left>
                        <v-icon>mdi-chevron-double-up</v-icon></v-avatar>{{$ml.with('VueJS').get('high')}}</v-chip>
                </v-col>
            </v-row>
<!--        </v-container>-->
    </v-card-text>
</template>

<script>
    export default {
        props: ['value'],
        mounted: function () {
            this.task = this.value;
            switch (this.task.status) {
                case 0:
                    this.task.status_name = 'new_task';
                    break;
                case 1:
                    this.task.status_name = 'active_task';
                    break;
                case 2:
                    this.task.status_name = 'completed_task';
                    break;
            }
            let _this = this;
            axios.get('/dict/masters/get')
                .then(function (response) {
                    _this.dicts.masters = response.data.masters;
                });
        },
        data: () => ({
            dicts: {
                masters: [],
            },
            task: null,
            master: null,
            loader: null,
            loading: false,
        }),
        watch: {
            value: function () {
                this.task = this.value;
                switch (this.task.status) {
                    case 0:
                        this.task.status_name = 'new_task';
                        break;
                    case 1:
                        this.task.status_name = 'active_task';
                        break;
                    case 2:
                        this.task.status_name = 'completed_task';
                        break;
                }
            },
            loader () {
                const l = this.loader;
                this[l] = !this[l];
                setTimeout(() => (this[l] = false), 3000);
                this.loader = null;
            },
        },
        computed: {
            status_name: function() {
                return this.$ml.with('VueJS').get(this.task.status_name);
            }
        },
        methods: {
            to_work: function () {
                let _this = this;
                if(this.task.user_position) {
                    axios.put('/task/to_work/' + _this.task.id)
                        .then(function (response) {
                            _this.value.status = 1;
                            _this.$bus.$emit("GetCount");
                        });
                }
            },
            done: function () {
                let _this = this;
                if(this.task.user_position) {
                    axios.put('/task/done/' + _this.task.id)
                        .then(function (response) {
                            _this.value.status = 2;
                        });
                }
            },
            delete_task: function () {
                let _this = this;
                if(this.task.user_position === 'admin') {
                    axios.delete('/task/delete/' + _this.task.id)
                        .then(function (response) {
                            _this.value.status = 3;
                            _this.$bus.$emit("GetCount");
                        });
                }
            },
            change_master: function () {
                let _this = this;
                if(this.task.user_position === 'admin' && this.master !== null) {
                    let data = {
                        master: this.master,
                    };
                    this.loader = 'loading';
                    axios.put('/task/master/change/' + _this.task.id, data);
                }
            }
        }
    }
</script>

<style scoped>
    @media screen and (min-width: 960px) {
        .mg-task-20 {
            margin-top: -20px;
        }
        .mg-task-80 {
            margin-top: -80px;
        }
        .mg-task-30 {
            margin-top: -30px;
        }
        .mg-task-120 {
            margin-top: -120px;
        }
        .mg-task-50 {
            margin-top: -50px;
        }
        .md-null-pad {
            padding-right: 0 !important;
        }
    }
    @media screen and (max-width: 600px) {
        .xs-task-50 {
            margin-top: -50px;
        }
        .xs-task-30 {
            margin-top: -30px;
        }
    }
    @media (min-width: 600px) and (max-width: 960px) {
        .sm-task-30 {
            margin-top: -30px;
        }
        .sm-task-20 {
            margin-top: -20px;
        }
        .sm-task-50 {
            margin-top: -50px;
        }
        .sm-task-120 {
            margin-top: -120px;
        }
        .container {
            padding: 0 !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }
        .null-pad {
            padding-right: 0 !important;
        }
    }
</style>
