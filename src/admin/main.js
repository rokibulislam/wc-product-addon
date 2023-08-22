import { createHooks } from '@wordpress/hooks';
import Vue from 'vue';
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css';
import 'element-ui/lib/theme-chalk/icon.css';

import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'
// configure language
locale.use(lang);

import VueSweetalert2 from 'vue-sweetalert2';
// import Notifications from 'vue-notification';
import { Notification } from 'element-ui';

// Vue.use(Notifications);
Vue.use(Notification);
Vue.use(VueSweetalert2);
Vue.use(ElementUI);


import App from './App.vue';
import router from './router';
import store from './store';
import menuFix from './utils/admin-menu-fix';

Vue.config.productionTip = false;

Vue.prototype.$eventHub = new Vue();
window.Vue = Vue;

if (!Array.prototype.hasOwnProperty('swap')) {
    Array.prototype.swap = function (from, to) {
        this.splice(to, 0, this.splice(from, 1)[0]);
    };
}


Vue.mixin({
    methods: {
        is_failed_to_validate: function(template) {
            let validator = this.field_settings[template] ? this.field_settings[template].validator : false;

            if (validator && validator.callback && !this[validator.callback]()) {
                return true;
            }

            return false;
        },

         is_recaptcha_v2: function() {
            return contactum.recaptcha_type === 'v2';
        },

        has_recaptcha_api_keys: function() {
            return (contactum.recaptcha_public && contactum.recaptcha_secret ) ? true : false;;
        },
        settings_taxonomy: function (form_field) {
            return this.$store.state.field_settings[form_field.name].settings;
        },
        has_gmap_api_key: function() {
            return ( contactum.gmap_key != '' ) ? true : false;
        },

        isSingleInstance: function(field_name) {
            var singleInstance = ['post_title', 'post_content', 'post_excerpt', 'featured_image',
                'user_login', 'first_name', 'last_name', 'nickname', 'user_email', 'user_url',
                'user_bio', 'password', 'user_avatar', 'taxonomy', 'humanpresence'];

            if ( jQuery.inArray(field_name, singleInstance) >= 0 ) {
                return true;
            }

            return false;
        },

        get_random_id: function() {
            var min = 999999,
                max = 9999999999;

            return Math.floor(Math.random() * (max - min + 1)) + min;
        },
    }
})

window.contactum.hooks = (wp && wp.hooks) ? wp.hooks : createHooks();

if (contactum.hooks) {
    contactum.addFilter = (hookName, namespace, component, priority = 10) => {
        contactum.hooks.addFilter(
            hookName,
            namespace,
            (components) => {
                components.push(component);
                return components;
            },
            priority
        );
    };
}

/* eslint-disable no-new */
new Vue({
	el: '#contactum-admin-app',
	router,
	store,
	render: (h) => h(App)
});


menuFix('vue-app');
