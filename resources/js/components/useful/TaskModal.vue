<template>
    <v-card-text>
        <v-container>
            <v-row v-if="task">
                <v-col cols="12" md="3"><v-btn x-small color="primary">{{ status_name }}</v-btn></v-col>
                <v-col cols="12" md="9"></v-col>
                <v-col cols="12" md="8" style="margin-top: -20px"><v-card-title>{{task.title}}</v-card-title></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px" v-if="task.user_position !== 'company'">
                    <div class="my-2 mt-5">
                        <v-btn v-if="task.status === 0" small color="success" dark @click="to_work()">{{$ml.with('VueJS').get('to_work')}}</v-btn>
                        <v-btn v-if="task.status === 1" small color="success" dark @click="done()">{{$ml.with('VueJS').get('to_done')}}</v-btn>
                    </div></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px">
                    <div class="my-2 mt-5" v-if="task.user_position === 'admin'"><v-btn @click="delete_task()" small color="error" dark>
                        {{ $ml.with('VueJS').get('delete') }}</v-btn></div>
                    <div class="my-2 mt-5" v-else></div></v-col>
                <v-col cols="12" md="10" style="margin-top: -30px"><v-card-text>{{task.description}}</v-card-text></v-col>
                <v-col cols="12" md="2" style="margin-top: -30px"><v-card-text>{{task.priority}}</v-card-text></v-col>
                <v-col cols="12" md="12" style="margin-top: -30px" v-if="task.images[0]">
                    <v-card>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" md="6" v-for="image in task.images" :key="image.id">
                                    <v-container>
                                        <v-prew-image class="image" :src="image.code"></v-prew-image>
                                    </v-container>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="2" style="margin-top: -20px"><v-card-text>{{$ml.with('VueJS').get('type')}}:</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -20px"><v-card-text :style="{'color': task.type_color}">{{task.type}}</v-card-text></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px"></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px"><v-card-text>{{$ml.with('VueJS').get('master')}}:</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -20px" v-if="task.user_position !== 'admin'"><v-card-text>{{task.master}}</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -20px" v-else>
                    <v-select :items="dicts.masters" item-text="name" item-value="id" dense solo :label="task.master" v-model="master"></v-select>
                    <v-btn @click="change_master()" :loading="loading" small color="error" dark>{{$ml.with('VueJS').get('change')}}</v-btn>
                </v-col>
                <v-col cols="12" md="2" style="margin-top: -50px"><v-card-text>{{$ml.with('VueJS').get('date')}}:</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -50px"><v-card-text>{{task.created_at}}</v-card-text></v-col>
                <v-col cols="12" md="7" style="margin-top: -50px"></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px; margin-left: 15px">{{$ml.with('VueJS').get('client')}}:</v-col>
                <v-col cols="12" md="5" style="margin-top: -20px">
                    <v-card>
                        <v-card-text>
                            <v-container>
                                <v-card-title style="font-size: 16px">{{task.client}}</v-card-title>
                                <v-card-text style="margin-top: -20px">{{task.client_phone}}</v-card-text>
                                <v-card-text style="margin-top: -20px">{{task.client_email}}</v-card-text>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
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

</style>
