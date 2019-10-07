<template>
    <v-card-text>
        <v-container>
            <v-row v-if="task">
                <v-col cols="12" md="2"><v-btn x-small color="primary">{{ task.status_name }}</v-btn></v-col>
                <v-col cols="12" md="10"></v-col>
                <v-col cols="12" md="8" style="margin-top: -20px"><v-card-title>{{task.title}}</v-card-title></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px" v-if="task.user_position !== 'company'">
                    <div class="my-2 mt-5">
                        <v-btn v-if="task.status === 0" small color="success" dark @click="to_work()">to work</v-btn>
                        <v-btn v-if="task.status === 1" small color="success" dark @click="done()">done</v-btn>
                    </div></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px">
                    <div class="my-2 mt-5" v-if="task.user_position === 'admin'"><v-btn @click="delete_task()" small color="error" dark>delete</v-btn></div>
                    <div class="my-2 mt-5" v-else></div></v-col>
                <v-col cols="12" md="10" style="margin-top: -30px"><v-card-text>{{task.description}}</v-card-text></v-col>
                <v-col cols="12" md="2" style="margin-top: -30px"><v-card-text>{{task.priority}}</v-card-text></v-col>
                <v-col cols="12" md="12" style="margin-top: -30px" v-if="task.images[0]">
                    <v-card>
                        <v-card-text>
                            <v-container v-for="image in task.images" :key="image.id" >
                                <v-prew-image class="image" :src="image.code"></v-prew-image>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="2" style="margin-top: -20px"><v-card-text>Type:</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -20px"><v-card-text :style="{'color': task.type_color}">{{task.type}}</v-card-text></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px"></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px"><v-card-text>Master:</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -20px" v-if="task.user_position !== 'admin'"><v-card-text>{{task.master}}</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -20px" v-else><v-select :items="dicts.masters" item-value="id" item-text="name" label="Master" v-model="task.master"></v-select></v-col>
                <v-col cols="12" md="2" style="margin-top: -50px"><v-card-text>Date:</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -50px"><v-card-text>{{task.created_at}}</v-card-text></v-col>
                <v-col cols="12" md="7" style="margin-top: -50px"></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px; margin-left: 15px">Client:</v-col>
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
                    this.task.status_name = 'New task';
                    break;
                case 1:
                    this.task.status_name = 'Active task';
                    break;
                case 2:
                    this.task.status_name = 'Completed task';
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
        }),
        watch: {
            value: function () {
                this.task = this.value;
                switch (this.task.status) {
                    case 0:
                        this.task.status_name = 'New task';
                        break;
                    case 1:
                        this.task.status_name = 'Active task';
                        break;
                    case 2:
                        this.task.status_name = 'Completed task';
                        break;
                }
            },
        },
        methods: {
            to_work: function () {
                let _this = this;
                if(this.task.user_position) {
                    axios.put('/task/to_work/' + _this.task.id)
                        .then(function (response) {
                            _this.value.status = 1;
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
                        });
                }
            },
        }
    }
</script>

<style scoped>

</style>
