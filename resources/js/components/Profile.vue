<template>
    <v-card width="1000" class="mx-auto mycontent-left" style="margin-top: 30px">
        <v-toolbar color="purple darken-3" dark>
            <template>
                <v-tabs v-model="tab" background-color="transparent">
                    <v-tabs-slider color="blue lighten-1"></v-tabs-slider>
                    <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
                </v-tabs>
            </template>
        </v-toolbar>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <v-form ref="form">
                <v-card flat style="min-height: 50px">
                    <div style="text-align: center"  v-if="profile_progress">
                        <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                    </div>
                    <v-card-text v-else>
                        <v-row>
                            <v-col cols="12" md="6" key=1>
                                <v-text-field style="margin-left: 15px" v-model="profile.name" :rules="nameRules" label="Name" required></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6" key=2>
                                <v-text-field v-if="profile.position === 'admin'" style="margin-left: 15px" label="Email" v-model="profile.email"
                                              :rules="emailRules" required></v-text-field>
                                <v-text-field v-else style="margin-left: 15px" label="Email" v-model="profile.email" readonly></v-text-field>
                            </v-col>
                            <v-col v-if="profile.position" cols="12" md="6" key=3 style="margin-top: -30px">
                                <v-text-field style="margin-left: 15px" label="Telegram" v-model="profile.telegram"></v-text-field>
                            </v-col>
                            <v-col v-else cols="12" md="6" key=4 style="margin-top: -30px">
                                <v-text-field style="margin-left: 15px" label="Phone" v-model="profile.phone"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6" key=5></v-col>
                            <v-col cols="12" md="6" key=6 v-if="profile.position">
                                <v-card>
                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12" md="5" key=11><v-card-text>Position:</v-card-text></v-col>
                                                <v-col cols="12" md="7" key=12><v-card-text>{{profile.position}}</v-card-text></v-col>
                                                <v-col cols="12" md="5" key=13 style="margin-top: -30px"><v-card-text>Active:</v-card-text></v-col>
                                                <v-col cols="12" md="3" key=14 style="margin-top: -30px"><v-card-text>{{profile.is_active}}</v-card-text></v-col>
                                            </v-row>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                        <v-btn v-if="!profile_progress" @click="change_profile()">Change</v-btn>
                    </v-card-text>
                </v-card>
                </v-form>
                <v-dialog v-model="dialog" hide-overlay persistent width="300">
                    <v-card>
                        <v-card-title class="headline">Profile was saved successfully.</v-card-title>
                        <v-card-actions>
                            <div class="flex-grow-1"></div>
                            <v-btn color="green darken-1" text @click="dialog = false">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-tab-item>
            <v-tab-item>
                <v-card flat style="min-height: 50px">
                    <div style="text-align: center"  v-if="profile_progress">
                        <v-progress-circular indeterminate color="purple" style="margin: 10px;"></v-progress-circular>
                    </div>
                    <v-card-text v-else>
                        <v-row>
                            <v-col cols="12" md="6" key=9>
                                <v-text-field v-model="new_password" :append-icon="show2 ? 'visibility' : 'visibility_off'" :rules="[passwordRules.required, passwordRules.min]"
                                              :type="show2 ? 'text' : 'password'" name="input-10-1" label="New password" hint="At least 8 characters"
                                              counter @click:append="show2 = !show1" ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6" key=10>
                                <v-text-field v-model="confirm_new_password" :append-icon="show3 ? 'visibility' : 'visibility_off'" :rules="[passwordRules.required, passwordRules.min]"
                                              :type="show3 ? 'text' : 'password'" name="input-10-1" label="Confirm new password" hint="At least 8 characters"
                                              counter @click:append="show3 = !show3" ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-btn v-if="!profile_progress" @click="change_password()">Change</v-btn>
                    </v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs-items>
        <v-dialog v-model="dialog_pas" hide-overlay persistent width="300">
            <v-card>
                <v-card-title class="headline">Password was saved successfully.</v-card-title>
                <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn color="green darken-1" text @click="dialog_pas = false">OK</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
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
                profile : {
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
                items: [
                    'change profile', 'change password',
                ],
                tab: null,
                dialog: false,
                dialog_pas: false,
                nameRules: [
                    v => !!v || 'Name is required',
                    v => (v && v.length <= 255) || 'Name must be less than 255 characters',
                ],
                passwordRules: {
                    required: value => !!value || 'Password is required.',
                    min: v => v.length >= 8 || 'Min 8 characters',
                    emailMatch: () => ('The email and password you entered don\'t match'),
                },
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                phoneRules: [
                    v => !!v || 'Phone is required',
                    v => /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/.test(v) || 'Phone must be valid',
                ],
            };
        },
        watch: {
            confirm_new_password: function () {
                if(this.new_password === this.confirm_new_password)
                {
                    this.profile.password = this.new_password;
                }
            },
        },
        methods: {
            change_profile: function () {
                this.profile_progress = true;
                let _this = this;
                if (this.$refs.form.validate()) {
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
                this.profile_progress = true;
                let _this = this;
                if (this.$refs.form.validate()) {
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
</script>

<style scoped>

</style>
