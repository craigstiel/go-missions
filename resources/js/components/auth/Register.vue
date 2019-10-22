<template>
    <v-row style="margin-top: -20px">
        <v-col md="2"></v-col>
        <v-col cols="12" md="8">
            <v-card :height="height" class="mx-auto mycontent-left">
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-col cols="12" md="12">
                        <v-text-field v-model="name" :counter="255" :rules="nameRules"
                                      :label="$ml.with('VueJS').get('full_name')" required></v-text-field>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -20px">
                        <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -20px">
                        <v-text-field v-model="telegram" label="Telegram"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -20px">
                        <v-switch v-model="master" :label="$ml.with('VueJS').get('master')"></v-switch>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -30px" v-if="!master">
                        <v-text-field v-model="phone" :rules="phoneRules" :label="$ml.with('VueJS').get('phone')"
                                      required></v-text-field>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -20px">
                        <v-text-field v-model="new_password" :append-icon="show1 ? 'visibility' : 'visibility_off'"
                                      :rules="[passwordRules.required, passwordRules.min]"
                                      :type="show1 ? 'text' : 'password'" name="input-10-1"
                                      :label="$ml.with('VueJS').get('pass')"
                                      counter @click:append="show1 = !show1"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -20px">
                        <v-text-field v-model="confirm_new_password" :append-icon="show2 ? 'visibility' : 'visibility_off'"
                                      :rules="[passwordRules.required, passwordRules.min]"
                                      :type="show2 ? 'text' : 'password'" name="input-10-1"
                                      :label="$ml.with('VueJS').get('confirm_pass')"
                                      counter @click:append="show2 = !show2"></v-text-field>
                    </v-col>
                    <v-col style="margin-top: -15px" cols="12" md="12">
                        <v-btn :disabled="!valid" color="success" class="mr-4" @click="register">
                            {{$ml.with('VueJS').get('sign_up')}}
                        </v-btn>
                    </v-col>
                    <v-card-text style="margin-top: -10px">{{$ml.with('VueJS').get('reg_rule')}}.</v-card-text>
                </v-form>
            </v-card>
        </v-col>
        <v-col md="2"></v-col>
    </v-row>
</template>

<script>
    export default {
        data() {
            return {
                name: '',
                email: '',
                password: '',
                confirm_new_password: '',
                new_password: '',
                phone: '',
                telegram: '',
                valid: true,
                master: false,
                show1: false,
                show2: false,
                lang: '',
                height: 630,
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
                    v => /^[\+]?\d{2,}?[(]?\d{2,}[)]?[-\s\.]?\d{2,}?[-\s\.]?\d{2,}[-\s\.]?\d{0,9}$/im.test(v) || 'Phone must be valid',
                ],
            };
        },
        watch: {
            master() {
                if (this.master === false)
                    this.height = 630;
                else
                    this.height = 580;
            },
            confirm_new_password: function () {
                if (this.new_password === this.confirm_new_password) {
                    this.password = this.new_password;
                }
            },
            new_password: function () {
                if (this.new_password === this.confirm_new_password) {
                    this.password = this.new_password;
                }
            },
        },
        methods: {
            register() {
                var app = this;
                if (this.$refs.form.validate()) {
                    if (app.password === app.confirm_new_password && app.password === app.new_password) {
                        this.$auth.register({
                            data: {
                                name: app.name,
                                email: app.email,
                                password: app.password,
                                phone: app.phone,
                                telegram: app.telegram,
                                is_master: app.master
                            },
                            error: function (error) {
                                if (error.response.status === 500) {
                                    app.$bus.$emit("alert", app.$ml.with('VueJS').get('already_used'), "error");
                                }
                            },
                            redirect: '/verify'
                        });
                    } else {
                        app.$bus.$emit("alert", app.$ml.with('VueJS').get('not_match'), "error");
                    }
                }
            }
        }
    }
</script>
