require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'

Vue.use(VueRouter);


import { Form, HasError, AlertError} from 'vform'

import Snotify, { SnotifyPosition } from 'vue-snotify'

const options  = {
    toast: {
        position: SnotifyPosition.rightTop
    }
}

Vue.use(Snotify, options )

window.Form = Form

// Component Register
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination', require('./components/partial/PaginationComponent.vue').default);

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
const dashboard = Vue.component('dashboard-component', require('./components/DashboardComponent.vue').default);
const category = Vue.component('category-component', require('./components/CategoryComponent.vue').default);

const router = new VueRouter({
    routes: [
        {
            path: '/dashboard',
            name: 'dashboard',
            component: dashboard
        }, {
            path: '/category',
            name: 'category',
            component: category
        }
    ]
});

const app = new Vue({
    el: '#app',
    router
});
