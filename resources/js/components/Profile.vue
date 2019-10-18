<template>
    <v-row>
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
                        <v-form ref="obs" v-model="valid" lazy-validation style="padding: 20px;">
                            <v-card flat style="min-height: 50px">
                                <div style="text-align: center" v-if="profile_progress">
                                    <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                                </div>
                                <v-card-text v-else>
                                    <v-row>
                                        <v-col cols="6" md="6" sm="6" key=1>
                                            <v-text-field v-model="profile.name" :counter="255" :rules="nameRules"
                                                          :label="$ml.with('VueJS').get('full_name')" required></v-text-field>
                                        </v-col>
                                        <v-col cols="6" md="6" sm="6" key=2>
                                            <v-text-field v-if="profile.position === 'admin'" v-model="profile.email" :rules="emailRules" label="E-mail" required></v-text-field>
                                            <v-text-field v-else v-model="profile.email" :rules="emailRules" label="E-mail" disabled></v-text-field>
                                        </v-col>
                                        <v-col v-if="profile.position" cols="6" md="6" sm="6" key=3 style="margin-top: -30px">
                                            <v-text-field style="margin-left: 15px" label="Telegram" v-model="profile.telegram"></v-text-field>
                                        </v-col>
                                        <v-col v-else cols="6" md="6" sm="6" key=4 style="margin-top: -30px">
                                            <v-text-field v-model="profile.phone" :rules="phoneRules" :label="$ml.with('VueJS').get('phone')"
                                                          required></v-text-field>
                                        </v-col>
                                        <v-col cols="6" md="6" sm="6" key=5></v-col>
                                        <v-col cols="12" md="6" sm="6" key=6 v-if="profile.position">
                                            <v-card>
                                                <v-card-text>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col cols="6" md="5" sm="6" key=11>
                                                                <v-card-text class="null-pad">{{ $ml.with('VueJS').get('position') }}:</v-card-text>
                                                            </v-col>
                                                            <v-col cols="6" md="7" sm="6" key=12>
                                                                <v-card-text class="null-pad">{{position}}</v-card-text>
                                                            </v-col>
                                                            <v-col cols="6" md="5" sm="6" key=13 style="margin-top: -30px">
                                                                <v-card-text class="null-pad">{{ $ml.with('VueJS').get('active') }}:</v-card-text>
                                                            </v-col>
                                                            <v-col cols="6" md="3" sm="6" key=14 style="margin-top: -30px">
                                                                <v-card-text class="null-pad">{{is_active}}</v-card-text>
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
                        </v-form>
                        <v-dialog v-model="dialog" hide-overlay persistent>
                            <v-row>
                                <v-col cols="1" md="3"></v-col>
                                <v-col cols="10" md="6">
                                    <v-card>
                                        <v-card-title class="headline">{{ $ml.with('VueJS').get('profile_success') }}.
                                        </v-card-title>
                                        <v-card-actions>
                                            <div class="flex-grow-1"></div>
                                            <v-btn color="green darken-1" text @click="dialog = false">OK</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>
                                <v-col cols="1" md="3"></v-col>
                            </v-row>
                        </v-dialog>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat style="min-height: 50px">
                            <div style="text-align: center" v-if="profile_progress">
                                <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                            </div>
                            <v-card-text v-else>
                                <v-form ref="form" v-model="valid" lazy-validation style="padding: 20px;">
                                    <v-row>
                                        <v-col cols="12" md="6" sm="6" key=9>
                                            <v-text-field v-model="new_password"
                                                          :append-icon="show1 ? 'visibility' : 'visibility_off'"
                                                          :rules="[passwordRules.required, passwordRules.min]"
                                                          :type="show1 ? 'text' : 'password'" name="input-10-1"
                                                          :label="$ml.with('VueJS').get('new_pass')"
                                                          hint="At least 8 characters" counter
                                                          @click:append="show1 = !show1"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="6" key=10>
                                            <v-text-field v-model="confirm_new_password"
                                                          :append-icon="show3 ? 'visibility' : 'visibility_off'"
                                                          :rules="[passwordRules.required, passwordRules.min]"
                                                          :type="show3 ? 'text' : 'password'" name="input-10-1"
                                                          :label="$ml.with('VueJS').get('confirm_new_pass')"
                                                          hint="At least 8 characters" counter
                                                          @click:append="show3 = !show3"></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-btn @click="change_password()">{{ $ml.with('VueJS').get('change') }}</v-btn>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs-items>
                <v-dialog v-model="dialog_pas" hide-overlay persistent>
                    <v-row>
                        <v-col cols="1" md="3"></v-col>
                        <v-col cols="10" md="6">
                            <v-card>
                                <v-card-title class="headline">{{ $ml.with('VueJS').get('pass_success') }}.
                                </v-card-title>
                                <v-card-actions>
                                    <div class="flex-grow-1"></div>
                                    <v-btn color="green darken-1" text @click="dialog_pas = false">OK</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                        <v-col cols="1" md="3"></v-col>
                    </v-row>
                </v-dialog>
            </v-card>
        </v-col>
        <v-col md="1"></v-col>
    </v-row>
</template>

<script>
    export default {
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
                show1: false,
                show3: false,
                valid: false,
                profile_progress: true,
                new_password: null,
                confirm_new_password: null,
                tab: null,
                dialog: false,
                dialog_pas: false,
                nameRules: [
                    v => !!v || 'Name is required',
                    v => (v && v.length <= 255) || 'Name must be less than 255 characters',
                ],
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                phoneRules: [
                    v => !!v || 'Phone is required',
                    v => /^[\+]?\d{2,}?[(]?\d{2,}[)]?[-\s\.]?\d{2,}?[-\s\.]?\d{2,}[-\s\.]?\d{0,9}$/im.test(v) || 'Phone must be valid',
                ],
                passwordRules: {
                    required: value => !!value || 'Password is required.',
                    min: v => v && v.length >= 8 || 'Min 8 characters',
                },
            };
        },
        watch: {
            confirm_new_password: function () {
                if (this.new_password === this.confirm_new_password) {
                    this.profile.password = this.new_password;
                }
            },
            new_password: function () {
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
            change_profile: function () {
                let _this = this;
                if (this.$refs.obs.validate()) {
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
            change_password: function () {
                let _this = this;
                if (this.$refs.form.validate()) {
                    this.profile_progress = true;
                    if (this.profile.password === this.confirm_new_password && this.profile.password === this.new_password) {
                        let data = {
                            password: _this.profile.password,
                        };
                        axios.put('/profile/password/edit', data)
                            .then(function () {
                                _this.dialog_pas = true;
                                _this.profile_progress = false;
                            });
                    } else {
                        _this.$bus.$emit("alert", _this.$ml.with('VueJS').get('not_match'), "error");
                        _this.profile_progress = false;
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .v-tabs-slider-wrapper {
        left: 0 !important;
    }
    .v-application--is-ltr .v-tabs-bar.v-tabs-bar--is-mobile:not(.v-tabs-bar--show-arrows)>.v-slide-group__wrapper>
    .v-tabs-bar__content>.v-tabs-slider-wrapper+.v-tab {
        margin-left: 0 !important;
    }
    @media (min-width: 600px) and (max-width: 960px) {
        .null-pad {
            padding-right: 0 !important;
        }
    }
</style>
