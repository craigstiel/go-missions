<template>
    <div class="row">
        <v-col md="1"></v-col>
        <v-col cols="12" md="10" key=12>
            <v-card class="mx-auto mycontent-left">
                <v-toolbar color="purple darken-3" dark>
                    <template>
                        <v-tabs v-model="tab" background-color="transparent">
                            <v-tabs-slider color="blue lighten-1"></v-tabs-slider>
                            <v-tab>{{$ml.with('VueJS').get('change_pass')}}</v-tab>
                            <v-tab>{{$ml.with('VueJS').get('change_profile')}}</v-tab>
                        </v-tabs>
                    </template>
                </v-toolbar>
                <v-tabs-items v-model="tab">
                    <v-tab-item>
                        <ValidationObserver ref="obs">
                            <v-card flat style="min-height: 50px">
                                <div style="text-align: center" v-if="profile_progress">
                                    <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                                </div>
                                <v-card-text v-else>
                                    <v-row>
                                        <v-col cols="6" md="6" sm="6" key=1>
                                            <ValidationProvider name="name" rules="required">
                                                <v-text-field style="margin-left: 15px" v-model="profile.name" slot-scope="{errors, valid}" :error-messages="errors"
                                                              :success="valid" :label="$ml.with('VueJS').get('name')"></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="6" md="6" sm="6" key=2>
                                            <ValidationProvider name="email" rules="required|email">
                                                <v-text-field v-if="profile.position === 'admin'" style="margin-left: 15px" label="E-mail" v-model="profile.email"
                                                              slot-scope="{errors, valid}" :error-messages="errors" :success="valid"></v-text-field>
                                                <v-text-field v-else style="margin-left: 15px" label="Email" v-model="profile.email" readonly></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col v-if="profile.position" cols="6" md="6" sm="6" key=3 style="margin-top: -30px">
                                            <v-text-field style="margin-left: 15px" label="Telegram" v-model="profile.telegram"></v-text-field>
                                        </v-col>
                                        <v-col v-else cols="6" md="6" sm="6" key=4 style="margin-top: -30px">
                                            <ValidationProvider name="phone" rules="required|tel">
                                                <v-text-field style="margin-left: 15px" :label="$ml.with('VueJS').get('phone')" type="tel"
                                                              slot-scope="{errors, valid}" :error-messages="errors" :success="valid" v-model="profile.phone"></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="6" md="6" sm="6" key=5></v-col>
                                        <v-col cols="12" md="6" sm="6" key=6 v-if="profile.position">
                                            <v-card>
                                                <v-card-text>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col cols="5" md="5" sm="5" key=11>
                                                                <v-card-text>{{ $ml.with('VueJS').get('position') }}:</v-card-text>
                                                            </v-col>
                                                            <v-col cols="7" md="7" sm="7" key=12>
                                                                <v-card-text>{{position}}</v-card-text>
                                                            </v-col>
                                                            <v-col cols="5" md="5" sm="5" key=13 style="margin-top: -30px">
                                                                <v-card-text>{{ $ml.with('VueJS').get('active') }}:</v-card-text>
                                                            </v-col>
                                                            <v-col cols="7" md="3" sm="3" key=14 style="margin-top: -30px">
                                                                <v-card-text>{{is_active}}</v-card-text>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-card-text>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                    <v-btn v-if="!profile_progress" @click="change_profile()">{{ $ml.with('VueJS').get('change') }}</v-btn>
                                </v-card-text>
                            </v-card>
                        </ValidationObserver>
                        <v-dialog v-model="dialog" hide-overlay persistent width="300">
                            <v-card>
                                <v-card-title class="headline">{{ $ml.with('VueJS').get('profile_success') }}.
                                </v-card-title>
                                <v-card-actions>
                                    <div class="flex-grow-1"></div>
                                    <v-btn color="green darken-1" text @click="dialog = false">OK</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat style="min-height: 50px">
                            <div style="text-align: center" v-if="profile_progress">
                                <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                            </div>
                            <v-card-text v-else>
                                <ValidationObserver ref="pas">
                                    <v-row>
                                        <v-col cols="12" md="6" sm="6" key=9>
                                            <ValidationProvider name="password" rules="'required|min:8|max:35|confirmed'">
                                                <v-text-field v-model="new_password"
                                                              :append-icon="show2 ? 'visibility' : 'visibility_off'"
                                                              slot-scope="{errors, valid}" :error-messages="errors"
                                                              :success="valid"
                                                              :type="show2 ? 'text' : 'password'"
                                                              :label="$ml.with('VueJS').get('new_pass')"
                                                              hint="At least 8 characters" counter
                                                              @click:append="show2 = !show1"></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="6" key=10>
                                            <ValidationProvider name="password_confirmation" rules="'required|confirmed:password'">
                                                <v-text-field v-model="confirm_new_password"
                                                              :append-icon="show3 ? 'visibility' : 'visibility_off'"
                                                              slot-scope="{errors, valid}" :error-messages="errors"
                                                              :success="valid"
                                                              :type="show3 ? 'text' : 'password'" name="input-10-1"
                                                              :label="$ml.with('VueJS').get('confirm_new_pass')"
                                                              hint="At least 8 characters" counter
                                                              @click:append="show3 = !show3"></v-text-field>
                                            </ValidationProvider>
                                            <span class="md-error">{{errors.first('password_confirmation')}}</span>
                                        </v-col>
                                        <div class="alert alert-danger" v-show="errors.any()">
                                            <div v-if="errors.has('password')">{{ errors.first('password') }}</div>
                                            <div v-if="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</div>
                                        </div>
                                    </v-row>
                                    <v-btn v-if="!profile_progress" @click="change_password()">{{ $ml.with('VueJS').get('change') }}</v-btn>
                                </ValidationObserver>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs-items>
                <v-dialog v-model="dialog_pas" hide-overlay persistent width="300">
                    <v-card>
                        <v-card-title class="headline">{{ $ml.with('VueJS').get('pass_success') }}.</v-card-title>
                        <v-card-actions>
                            <div class="flex-grow-1"></div>
                            <v-btn color="green darken-1" text @click="dialog_pas = false">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-card>
        </v-col>
        <v-col md="1"></v-col>
    </div>
</template>

<script>
    import {ValidationObserver, ValidationProvider} from "vee-validate";
    import {MLBuilder} from 'vue-multilanguage';

    export default {
        components: {
            ValidationProvider,
            ValidationObserver
        },
        mounted: function () {
            let _this = this;
            axios.get('/dict/profile/get')
                .then(function (response) {
                    _this.profile = response.data.profile;
                    _this.profile_progress = false;
                });
        },
        data() {
            return {
                profile: {
                    name: '',
                    email: '',
                    password: '',
                    phone: '',
                    telegram: '',
                },
                show2: false,
                show3: false,
                profile_progress: true,
                new_password: null,
                confirm_new_password: null,
                tab: null,
                dialog: false,
                dialog_pas: false,
                passwordRules: {
                    required: value => !!value || 'Password is required.',
                    min: v => v.length >= 8 || 'Min 8 characters',
                    emailMatch: () => ('The email and password you entered don\'t match'),
                },
            };
        },
        watch: {
            confirm_new_password: function () {
                if (this.new_password === this.confirm_new_password) {
                    this.profile.password = this.new_password;
                }
            },
        },
        computed: {
            is_active: function () {
                if (this.profile.is_active === 1)
                    return this.$ml.with('VueJS').get('yes');
                else
                    return this.$ml.with('VueJS').get('no');
            },
            position: function () {
                if (this.profile.position === 'master')
                    return this.$ml.with('VueJS').get('master');
                else
                    return this.$ml.with('VueJS').get('admin');
            },
        },
        methods: {
            change_profile: async function () {
                let _this = this;
                const result = await this.$refs.obs.validate();
                if (result) {
                    this.profile_progress = true;
                    let data = {
                        name: _this.profile.name,
                        email: _this.profile.email,
                        phone: _this.profile.phone,
                        telegram: _this.profile.telegram,
                    };
                    axios.put('/profile/edit', data)
                        .then(function () {
                            _this.dialog = true;
                            _this.profile_progress = false;
                        });
                }
            },
            change_password: async function () {
                let _this = this;
                const result = await this.$refs.pas.validate();
                if (result) {
                    this.profile_progress = true;
                    if (this.profile.password === this.confirm_new_password) {
                        let data = {
                            password: _this.profile.password,
                        };
                        axios.put('/profile/password/edit', data)
                            .then(function () {
                                _this.dialog_pas = true;
                                _this.profile_progress = false;
                            });
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
