<template>
    <v-card width="600" :height="height" class="mx-auto mycontent-left" style="margin-top: 30px">
        <v-form ref="form" v-model="valid" lazy-validation>
            <v-col cols="12" md="12">
                <v-text-field v-model="name" :counter="255" :rules="nameRules" label="Full name" required ></v-text-field>
            </v-col>
            <v-col cols="12" md="12" style="margin-top: -20px">
                <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
            </v-col>
            <v-col cols="12" md="12" style="margin-top: -20px">
                <v-text-field v-model="telegram" label="Telegram"></v-text-field>
            </v-col>
            <v-col cols="12" md="12" style="margin-top: -20px">
                <v-switch v-model="master" label="Master"></v-switch>
            </v-col>
            <v-col cols="12" md="12" style="margin-top: -20px" v-if="!master">
                <v-text-field v-model="phone" :rules="phoneRules" label="Phone" required></v-text-field>
            </v-col>
            <v-col cols="12" md="12" style="margin-top: -20px">
                <v-text-field v-model="password" :append-icon="show1 ? 'visibility' : 'visibility_off'" :rules="[passwordRules.required, passwordRules.min]"
                              :type="show1 ? 'text' : 'password'" name="input-10-1" label="Password" hint="At least 8 characters"
                              counter @click:append="show1 = !show1" ></v-text-field>
            </v-col>
            <v-col cols="12" md="12">
                <v-btn :disabled="!valid" color="success" class="mr-4" @click="register">Sign up</v-btn>
            </v-col>
            <v-card-text>To enter your account you should verify email.</v-card-text>
        </v-form>
    </v-card>
</template>

<script>
    export default {
        data(){
            return {
                name: '',
                email: '',
                password: '',
                phone: '',
                telegram: '',
                valid: true,
                master: false,
                show1: false,
                show2: true,
                height: 570,
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
            master(){
                if(this.master === false)
                    this.height = 570;
                else
                    this.height = 500;
            },
        },
        methods: {
            register(){
                var app = this
                if (this.$refs.form.validate()) {
                    this.$auth.register({
                        data: {
                            name: app.name,
                            email: app.email,
                            password: app.password,
                            phone: app.phone,
                            telegram: app.telegram,
                            is_master: app.master,
                        },
                        redirect: '/login'
                    });
                }
            }
        }
    }
</script>
