require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'

Vue.use(VueRouter);


import {Form, HasError, AlertError} from 'vform'
import objectToFormData from 'object-to-formdata'

import Snotify, { SnotifyPosition } from 'vue-snotify'

const options = {
    toast: {
        position: SnotifyPosition.rightTop
    }
}

Vue.use(Snotify, options)

window.Form = Form

window.objectToFormData = objectToFormData;

// Component Register
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination', require('./components/partial/PaginationComponent.vue').default);

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
const dashboard = Vue.component('dashboard-component', require('./components/DashboardComponent.vue').default);
const category = Vue.component('category-component', require('./components/CategoryComponent.vue').default);
const customcategory = Vue.component('custom-category-component', require('./components/CustomCategoryComponent.vue').default);
const post = Vue.component('post-component', require('./components/PostComponent').default);
const festival = Vue.component('festival-component', require('./components/FestivalComponent').default);

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
        },{
            path: '/custom-category',
            name: 'custom-category',
            component: customcategory
        }, {
            path: '/post',
            name: 'post',
            component: post
        }, {
            path: '/festival',
            name: 'festival',
            component: festival
        }
    ]
});

const app = new Vue({
    el: '#app',
    router
});
