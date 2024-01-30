import Vue from 'vue';
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css';
import 'element-ui/lib/theme-chalk/icon.css';

import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'
// configure language
locale.use(lang);

import VueSweetalert2 from 'vue-sweetalert2';
import { Notification } from 'element-ui';

Vue.use(Notification);
Vue.use(VueSweetalert2);
Vue.use(ElementUI);

import builder from './components/builder/index.vue';
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

        get_random_id: function() {
            var min = 999999,
                max = 9999999999;

            return Math.floor(Math.random() * (max - min + 1)) + min;
        },
    }
})

/* eslint-disable no-new */
new Vue({
	el: '#prafe-admin-app',
	store,
	render: (h) => h(builder)
});


menuFix('vue-app');
