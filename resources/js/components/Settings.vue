<template>
    <v-row>
        <v-col cols="1"></v-col>
        <v-col cols="12" md="10" key=12>
            <v-card class="mx-auto mycontent-left">
                <div>
                    <v-toolbar color="purple darken-3" dark flat>
                        <template>
                            <v-tabs v-model="tab" background-color="transparent">
                                <v-tabs-slider color="blue lighten-1"></v-tabs-slider>
                                <v-tab>{{$ml.with('VueJS').get('masters')}}</v-tab>
                                <v-tab>{{$ml.with('VueJS').get('types')}}</v-tab>
                            </v-tabs>
                        </template>
                    </v-toolbar>
                    <v-tabs-items v-model="tab">
                        <v-tab-item>
                            <v-card flat>
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="5" md="3" sm="3" key=1>
                                            <v-checkbox v-model="mult" :label="$ml.with('VueJS').get('for_admin')"
                                                        color="error" value="error" hide-details></v-checkbox>
                                        </v-col>
                                        <v-col cols="7" md="2" sm="3" key=2>
                                            <div class="my-2 mt-5">
                                                <v-btn @click="approve_multiple()" small color="error" dark>{{$ml.with('VueJS').get('approve')}}</v-btn>
                                            </div>
                                        </v-col>
                                    </v-row>
                                    <v-card v-if="multiple && dicts.new_masters[0]">
                                        <v-card-text style="margin-top: 20px; min-height: 50px">
                                            <div style="text-align: center" v-if="new_progress">
                                                <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                                            </div>
                                            <div v-if="!new_progress">
                                                <div class="flex font-weight-light" style="font-size: 18px; font-family: 'Roboto', sans-serif; padding-bottom: 20px">
                                                    {{$ml.with('VueJS').get('new_masters')}}
                                                </div>
                                                <v-row>
                                                    <v-card max-width="250" min-height="70" class="second-card col-md-3 ml-3" :key="master.id"
                                                            v-for="master in dicts.new_masters">
                                                        <v-btn class="delete-button" @click="delete_new_master(master.id)" color="error" fab
                                                               x-small dark><i class="fas fa-times"></i></v-btn>
                                                        <v-btn class="add-button" @click="approve_master(master.id)" color="success" fab x-small dark><i
                                                            class="fas fa-check"></i></v-btn>
                                                        <v-card-text style="font-size: 16px;">{{ master.name }}
                                                        </v-card-text>
                                                    </v-card>
                                                </v-row>
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                    <v-card v-if="dicts.active_masters[0]">
                                        <v-card-text style="margin-top: 20px; min-height: 50px">
                                            <div style="text-align: center" v-if="active_progress">
                                                <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                                            </div>
                                            <div v-if="!active_progress">
                                                <div class="flex font-weight-light" style="font-size: 18px; font-family: 'Roboto', sans-serif; padding-bottom: 20px">
                                                    {{$ml.with('VueJS').get('active_masters')}}
                                                </div>
                                                <v-row>
                                                    <v-card max-width="230" min-height="70" class="second-card col-md-3 ml-3" :key="master.id"
                                                            v-for="master in dicts.active_masters">
                                                        <v-btn class="delete-button" @click="delete_active_master(master.id)"
                                                               v-if="master.position !== 'admin'" color="error" fab
                                                               x-small dark><i class="fas fa-times"></i></v-btn>
                                                        <v-btn class="add-button" @click="disapprove_master(master.id)"
                                                               v-if="master.position !== 'admin'" color="warning" fab
                                                               x-small dark><i class="fas fa-minus"></i></v-btn>
                                                        <v-btn class="delete-button" v-if="master.position === 'admin'" color="error" x-small dark>
                                                            {{$ml.with('VueJS').get('admin')}}
                                                        </v-btn>
                                                        <v-card-text style="font-size: 16px;">{{ master.name }}
                                                        </v-card-text>
                                                    </v-card>
                                                </v-row>
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                    <v-card v-if="!multiple">
                                        <v-card-text style="margin-top: 20px; min-height: 50px">
                                            <div style="text-align: center" v-if="new_progress">
                                                <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                                            </div>
                                            <div v-if="!new_progress">
                                                <div class="flex font-weight-light"
                                                     style="font-size: 18px; font-family: 'Roboto', sans-serif; padding-bottom: 20px">
                                                    Masters
                                                </div>
                                                <v-row>
                                                    <v-card max-width="250" min-height="70" class="second-card col-md-3 ml-3" :key="master.id"
                                                            v-for="master in dicts.new_masters">
                                                        <v-card-text style="font-size: 16px;">{{ master.name }}</v-card-text>
                                                    </v-card>
                                                </v-row>
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item>
                            <v-card flat>
                                <v-card-text>
                                    <v-dialog v-model="dialog" persistent max-width="600px">
                                        <template v-slot:activator="{ on }">
                                            <div class="my-2">
                                                <v-btn color="error" v-on="on" dark large>{{$ml.with('VueJS').get('add_type')}}</v-btn>
                                            </div>
                                        </template>
                                        <v-form ref="form" lazy-validation>
                                            <v-card>
                                                <ValidationObserver ref="obs">
                                                <v-card-title>
                                                    <span class="headline">{{$ml.with('VueJS').get('add_type')}}</span>
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col cols="6" sm="6" md="6">
                                                                <ValidationProvider name="name" rules="required">
                                                                <v-text-field :label="$ml.with('VueJS').get('type_name')"  :success="valid"
                                                                    v-model="type.name" slot-scope="{errors, valid}" :error-messages="errors"></v-text-field>
                                                                </ValidationProvider>
                                                            </v-col>
                                                            <v-col cols="6" sm="6" md="6">
                                                                <ValidationProvider name="system_name" rules="required">
                                                                <v-text-field :label="$ml.with('VueJS').get('type_system_name')"
                                                                    hint="In English, please." v-model="type.system_name" :success="valid"
                                                                              slot-scope="{errors, valid}" :error-messages="errors"></v-text-field>
                                                                </ValidationProvider>
                                                            </v-col>
                                                        </v-row>
                                                        <v-col cols="12" sm="12" md="12">
                                                            <v-color-picker v-model="color" class="mx-auto"></v-color-picker>
                                                        </v-col>
                                                    </v-container>
                                                </v-card-text>
                                                <v-card-actions>
                                                    <div class="flex-grow-1"></div>
                                                    <v-btn color="blue darken-1" text @click="dialog = false">
                                                        {{$ml.with('VueJS').get('close')}}
                                                    </v-btn>
                                                    <v-btn color="blue darken-1" text @click="add_type()">
                                                        {{$ml.with('VueJS').get('save')}}
                                                    </v-btn>
                                                </v-card-actions>
                                                </ValidationObserver>
                                            </v-card>
                                        </v-form>
                                    </v-dialog>
                                    <v-card>
                                        <v-card-text style="min-height: 50px">
                                            <div style="text-align: center" v-if="type_progress">
                                                <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                                            </div>
                                            <div v-if="!type_progress">
                                                <div class="flex font-weight-light" style="font-size: 18px; font-family: 'Roboto', sans-serif; padding-bottom: 20px">
                                                    {{$ml.with('VueJS').get('types')}}
                                                </div>
                                                <v-row>
                                                    <v-card max-width="250" min-height="70" :style="{'background-color': type.color}"
                                                            class="col-md-3 ml-3 mt-3" :key="type.id" v-for="type in dicts.types">
                                                        <v-card-text style="font-size: 16px;">{{ type.name }}
                                                        </v-card-text>
                                                        <v-dialog v-model="delete_type_dialog" persistent max-width="400px">
                                                            <template v-slot:activator="{ on }">
                                                                <div class="my-2">
                                                                    <v-btn class="delete-button" v-on="on" color="error"
                                                                           fab x-small dark><i class="fas fa-times"></i>
                                                                    </v-btn>
                                                                </div>
                                                            </template>
                                                            <v-form ref="delete">
                                                                <v-card>
                                                                    <v-card-title>
                                                                        <span class="headline" style="color: #BD1818;">{{$ml.with('VueJS').get('delete_type')}}</span>
                                                                    </v-card-title>
                                                                    <v-card-text>{{$ml.with('VueJS').get('del_type')}}</v-card-text>
                                                                    <v-card-actions>
                                                                        <div class="flex-grow-1"></div>
                                                                        <v-btn color="blue darken-1" text @click="delete_type_dialog = false">
                                                                            {{$ml.with('VueJS').get('close')}}
                                                                        </v-btn>
                                                                        <v-btn color="blue darken-1" text @click="delete_type(type.id)">
                                                                            {{$ml.with('VueJS').get('delete')}}
                                                                        </v-btn>
                                                                    </v-card-actions>
                                                                </v-card>
                                                            </v-form>
                                                        </v-dialog>
                                                    </v-card>
                                                </v-row>
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                    </v-tabs-items>
                </div>
            </v-card>
        </v-col>
        <v-col cols="1"></v-col>
    </v-row>
</template>

Playground
<script>
    import { ValidationObserver, ValidationProvider } from "vee-validate";
    export default {
        components: {
            ValidationProvider,
            ValidationObserver
        },
        mounted: function () {
            this.get_active_masters();
            this.get_new_masters();
            this.get_types();
            this.get_multiple();
        },
        data() {
            return {
                tab: null,
                multiple: null,
                mult: null,
                color: '#8E00FF',
                new_progress: true,
                active_progress: true,
                type_progress: true,
                dialog: false,
                delete_type_dialog: false,
                type: {
                    name: null,
                    system_name: null
                },
                dicts: {
                    active_masters: [],
                    new_masters: [],
                    types: [],
                },
            }
        },
        methods: {
            get_active_masters: function () {
                let _this = this;
                axios.get('/dict/masters/get')
                    .then(function (response) {
                        _this.dicts.active_masters = response.data.masters;
                        _this.active_progress = false;
                    });
            },
            get_new_masters: function () {
                let _this = this;
                axios.get('/dict/new_masters/get')
                    .then(function (response) {
                        _this.dicts.new_masters = response.data.new_masters;
                        _this.new_progress = false;
                    });
            },
            get_types: function () {
                let _this = this;
                axios.get('/dict/task_types/get')
                    .then(function (response) {
                        _this.dicts.types = response.data.task_types;
                        _this.type_progress = false;
                    });
            },
            get_multiple: function () {
                let _this = this;
                axios.get('/dict/multiple/get')
                    .then(function (response) {
                        _this.multiple = response.data.multiple;
                        _this.mult = _this.multiple === 0 ? 'error' : null;
                    });
            },
            delete_new_master: function () {
                let _this = this;
                this.new_progress = true;
                axios.delete('/masters/delete/' + id)
                    .then(function (response) {
                        _this.get_new_masters();
                    });
            },
            delete_active_master: function (id) {
                let _this = this;
                this.active_progress = true;
                axios.delete('/masters/delete/' + id)
                    .then(function (response) {
                        _this.get_active_masters();
                    });
            },
            approve_master: function (id) {
                let _this = this;
                this.new_progress = true;
                this.active_progress = true;
                axios.put('/masters/approve/' + id)
                    .then(function (response) {
                        _this.get_new_masters();
                        _this.get_active_masters();
                    });
            },
            disapprove_master: function (id) {
                let _this = this;
                this.new_progress = true;
                this.active_progress = true;
                axios.put('/masters/disapprove/' + id)
                    .then(function (response) {
                        _this.get_new_masters();
                        _this.get_active_masters();
                    });
            },
            approve_multiple: function () {
                let _this = this;
                let data = {
                    multiple: this.mult === 'error' ? false : true,
                };
                axios.post('/multiple/approve', data)
                    .then(function () {
                        _this.get_new_masters();
                        _this.get_multiple();
                        _this.get_active_masters();
                    });
            },
            add_type: async function () {
                let _this = this;
                const result = await this.$refs.obs.validate();
                if (result) {
                    let data = {
                        name: _this.type.name,
                        system_name: _this.type.system_name,
                        color: _this.color,
                    };
                    axios.post('/types/add', data)
                        .then(function () {
                            _this.get_types();
                            _this.dialog = false;
                        });
                }
            },
            delete_type: function (id) {
                let _this = this;
                this.type_progress = true;
                axios.delete('/types/delete/' + id)
                    .then(function (response) {
                        _this.get_types();
                    });
            }
        }
    }
</script>

<style>
    .mycontent-left {
        border-right: 1px dashed #333;
    }
    .v-application--is-ltr .v-tabs-bar.v-tabs-bar--is-mobile:not(.v-tabs-bar--show-arrows)>.v-slide-group__wrapper>
    .v-tabs-bar__content>.v-tabs-slider-wrapper+.v-tab {
        margin-left: 0 !important;
    }
</style>
