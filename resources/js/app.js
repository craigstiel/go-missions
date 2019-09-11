/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@fortawesome/fontawesome-free/css/all.css'
import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

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

import App from './components/App';
import Home from './components/Home';
import AllTasks from './components/AllTasks';
import Task from './components/Task';
import AddTask from './components/AddTask';

const router = new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            children: [
                {
                    path: '/tasks',
                    name: 'tasks',
                    component: AllTasks,
                },
                {
                    path: '/show',
                    name: 'show_task',
                    component: Task,
                },
                {
                    path: '/add',
                    name: 'add_task',
                    component: AddTask,
                },
            ]
        },
    ],
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

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('v-image', require('./components/useful/ImageUploader.vue').default);

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
