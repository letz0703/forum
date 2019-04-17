import Vue from 'vue';
import App from './App.vue';
import InstantSearch from 'vue-instantsearch';
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

// window.Vue = require('vue');
// import algoliasearch from 'algoliasearch';
// import InstantSearch from 'vue-instantsearch';

Vue.use(InstantSearch);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Flash.vue'));
Vue.component('paginate', require('./components/Paginate.vue'));
Vue.component('user-notification', require('./components/UserNotification.vue'));
Vue.component('avatar-form', require('./components/AvatarForm.vue'));
Vue.component('wysiwyg', require('./components/Wysiwyg.vue'));
Vue.component('thread-view', require('./pages/Thread.vue'));

const app = new Vue({
    el: '#app',
    data: {
        searchClient: algoliasearch(
            'GP8RKZZYOR',
            'e6ef21c99284bfd36011317c215cecd2'),
    },
});
