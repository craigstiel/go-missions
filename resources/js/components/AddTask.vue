<template>
    <div class="row">
        <v-col md="1"></v-col>
        <v-col cols="12" md="10" key=12>
            <v-card class="mx-auto mycontent-left" style="margin-top: -30px">
                <v-toolbar color="purple darken-3" dark>
                    <v-toolbar-title style="font-size: 16px; font-family: 'Roboto', sans-serif; margin-left: 15px">{{ $ml.with('VueJS').get('add_task_caps') }}</v-toolbar-title>
                </v-toolbar>
                <v-card flat>
                    <ValidationObserver ref="obs">
                        <v-card-text>
                            <div style="text-align: center"  v-if="task_progress">
                                <v-progress-circular indeterminate color="purple" style="margin: 10px;padding: 50px"></v-progress-circular>
                            </div>
                            <v-row v-else>
                                <v-col cols="7" md="7" sm="7" key=1>
                                    <ValidationProvider name="title" rules="required">
                                        <v-text-field style="margin-left: 15px" v-model="title" slot-scope="{errors, valid}" :error-messages="errors"
                                                :success="valid" :label="$ml.with('VueJS').get('title')"></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="1" md="1" sm="1" key=2></v-col>
                                <v-col cols="3" md="3" sm="3" key=3 style="margin-top: 20px">
                                    <v-select :items="dicts.items" :label="$ml.with('VueJS').get('priority')" v-model="item" outlined></v-select>
                                </v-col>
                                <v-col cols="7" md="7" sm="7" key=4>
                                    <ValidationProvider name="description" rules="required">
                                        <v-textarea style="margin-left: 15px; margin-top: -30px" outlined :label="$ml.with('VueJS').get('description')" slot-scope="{errors, valid}"
                                                    :error-messages="errors" :success="valid" v-model="description"></v-textarea>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="1" md="1" sm="1" key=5></v-col>
                                <v-col cols="3" md="3" sm="3" key=6 style="margin-top: -10px">
                                    <v-select :items="dicts.types" item-value="id" item-text="name" :label="$ml.with('VueJS').get('type')" v-model="type" outlined></v-select>
                                </v-col>
                                <v-col cols="7" md="7" sm="7" key=7>
                                    <v-image v-model="value" style="margin-top: -20px; margin-left: 15px"></v-image>
                                </v-col>
                                <v-col cols="1" md="1" sm="1" key=8></v-col>
                                <v-col cols="3" md="3" sm="3" key=9 style="margin-top: -50px" v-if="multiple">
                                    <v-select :items="dicts.masters" item-value="id" item-text="name" :label="$ml.with('VueJS').get('master')"
                                              v-model="master" outlined></v-select>
                                </v-col>
                            </v-row>
                            <v-btn @click="add_task()" v-if="!task_progress">{{ $ml.with('VueJS').get('save') }}</v-btn>
                        </v-card-text>
                        <v-dialog v-model="dialog" hide-overlay persistent>
                            <v-row>
                                <v-col md="3"></v-col>
                                <v-col md="6">
                                    <v-card>
                                        <v-card-title class="headline">{{ $ml.with('VueJS').get('add_success') }}.
                                        </v-card-title>
                                        <v-card-actions>
                                            <div class="flex-grow-1"></div>
                                            <v-btn color="green darken-1" text @click="$router.push({name: 'tasks'})">
                                                OK
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                    <v-col md="3"></v-col>
                                </v-col>
                            </v-row>
                        </v-dialog>
                    </ValidationObserver>
                </v-card>
            </v-card>
        </v-col>
    </div>
</template>

<script>
    import { ValidationObserver, ValidationProvider } from "vee-validate";
    export default {
        components: {
            ValidationProvider,
            ValidationObserver
        },
        mounted: function () {
            let _this = this;
            axios.get('/dict/masters/get')
                .then(function (response) {
                    _this.dicts.masters = response.data.masters;
                    _this.task_progress = false;
                });
            axios.get('/dict/task_types/get')
                .then(function (response) {
                    _this.dicts.types = response.data.task_types;
                    _this.task_progress = false;
                });
            axios.get('/dict/multiple/get')
                .then(function (response) {
                    _this.multiple = response.data.multiple;
                });
        },
        data: () => ({
            title: '',
            description: '',
            dicts: {
                masters: [],
                items: ['low', 'medium', 'high'],
                types: [],
            },
            type: null,
            item: null,
            master: null,
            dialog: false,
            task_progress: true,
            multiple: true,
            value: [],
        }),
        methods: {
            add_task: async function () {
                let _this = this;
                const result = await this.$refs.obs.validate();
                if (result) {
                    this.task_progress = true;
                    let data = {
                        title: _this.title,
                        description: _this.description,
                        type: _this.type,
                        priority: _this.item,
                        master: _this.master,
                        images: _this.value,
                    };
                    axios.post('/task/add', data)
                        .then(function (response) {
                            if (response.status === 200) {
                                _this.dialog = true;
                                _this.task_progress = false;
                                _this.$bus.$emit("GetCount");
                            }
                        })
                        .catch((error) => {
                            console.log('error: ' + error);
                        });
                }
            },
        },
    }
</script>

<style>
    .mycontent-left {
        border-right: 1px dashed #333;
    }
    @media screen and (max-width: 600px) {
        body {
            line-height: 10px;
        }
    }
</style>
