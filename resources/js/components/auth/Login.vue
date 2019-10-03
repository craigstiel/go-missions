<template>
    <v-card width="600" :height="height" class="mx-auto mycontent-left" style="margin-top: 30px">
        <v-form ref="form" v-model="valid" lazy-validation>
            <v-col cols="12" md="12" style="margin-top: -20px">
                <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
            </v-col>
            <v-col cols="12" md="12" style="margin-top: -20px">
                <v-text-field v-model="password" :append-icon="show1 ? 'visibility' : 'visibility_off'" :rules="[passwordRules.required, passwordRules.min]"
                              :type="show1 ? 'text' : 'password'" name="input-10-1" label="Password" hint="At least 8 characters"
                              counter @click:append="show1 = !show1" ></v-text-field>
            </v-col>
            <v-col cols="12" md="12">
                <v-btn :disabled="!valid" color="success" class="mr-4" @click="login">Sing in</v-btn>
            </v-col>
        </v-form>
    </v-card>
</template>

<script>
    export default {
        data(){
            return {
                email: '',
                password: '',
                valid: true,
                show1: false,
                show2: true,
                height: 210,
                passwordRules: {
                    required: value => !!value || 'Password is required.',
                    min: v => v.length >= 8 || 'Min 8 characters',
                    emailMatch: () => ('The email and password you entered don\'t match'),
                },
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
            }
        },
        methods: {
            login(){
                var app = this;
                if (this.$refs.form.validate()) {
                    this.$auth.login({
                        params: {
                            email: app.email,
                            password: app.password
                        },
                        success: function () {
                        },
                        error: function () {
                        },
                        rememberMe: true,
                        redirect: '/',
                        fetchUser: true,
                    });
                }
            },
        }
    }
</script>
