/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@fortawesome/fontawesome-free/css/all.css'
import axios from 'axios';
import VueAxios from 'vue-axios';
import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import VeeValidate, {
    Validator
} from "vee-validate";
import ru from "vee-validate/dist/locale/ru";
import en from "vee-validate/dist/locale/en";
import {setupBus} from "./other/eventBus";
import './other/multLang';
import Notifications from 'vue-notification';

Validator.localize("ru", ru);

const opts = {
    theme: {
        dark: true
    },
    icons: {
        iconfont: 'mdiSvg' || 'mdi' || 'md' || 'fa' || 'fa4'
    },
}

Vue.use(VueRouter)
Vue.use(Vuetify,opts)
Vue.use(VueAxios, axios)
Vue.use(VeeValidate)
Vue.use(Notifications);
setupBus();

import App from './components/App';
import Home from './components/Home';
import AllTasks from './components/AllTasks';
import Profile from './components/Profile';
import AddTask from './components/AddTask';
import Settings from './components/Settings';
import Register from './components/auth/Register';
import Login from './components/auth/Login';
import Verify from './components/auth/Verify';
import RestorePassword from './components/auth/RestorePassword';
import ResetPassword from './components/auth/ResetPassword';

axios.defaults.baseURL = '/api';

const token = localStorage.getItem("default_auth_token");
if (token) {
    axios.defaults.headers.common["Authorization"] = 'Bearer ' + token;
}

const router = new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            meta: {
                title: 'GO-MISSIONS',
            },
            children: [
                {
                    path: '/tasks',
                    name: 'tasks',
                    component: AllTasks,
                    meta: {
                        auth: true
                    }
                },
                {
                    path: '/add',
                    name: 'add_task',
                    component: AddTask,
                    meta: {
                        auth: true
                    }
                },
                {
                    path: '/settings',
                    name: 'settings',
                    component: Settings,
                    meta: {
                        auth: true
                    }
                },
                {
                    path: '/profile',
                    name: 'profile',
                    component: Profile,
                    meta: {
                        auth: true
                    }
                },
                {
                    path: '/register',
                    name: 'register',
                    component: Register,
                    meta: {
                        auth: false
                    }
                },
                {
                    path: '/verify',
                    name: 'verify',
                    component: Verify,
                    meta: {
                        auth: false
                    }
                },
                {
                    path: '/login',
                    name: 'login',
                    component: Login,
                    meta: {
                        auth: false
                    }
                },
                {
                    path: '/restore',
                    name: 'restore_password',
                    component: RestorePassword,
                    meta: {
                        auth: false
                    }
                },
                {
                    path: '/reset/:token',
                    props: true,
                    name: 'reset_password',
                    component: ResetPassword,
                    meta: {
                        auth: false
                    }
                },
            ]
        },
    ],
});

Vue.router = router
Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router;
Vue.directive('title', {
    inserted: (el, binding) => document.title = binding.value,
    update: (el, binding) => document.title = binding.value
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('v-image', require('./components/useful/ImageUploader.vue').default);
Vue.component('v-prew-image', require('./components/useful/EnlargeImage.vue').default);
Vue.component('v-task', require('./components/useful/TaskModal.vue').default);
Vue.component('v-alert', require('./components/useful/Alert.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

new Vue({
    vuetify: new Vuetify(opts),
    router,
    render: h => h(App)
}).$mount("#app");
