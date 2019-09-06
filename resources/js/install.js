/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */


window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue = require('vue')

import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'

Vue.use(ElementUI, { locale })

import LanguageSelection from "./install/LanguageSelection"
import DatabaseSetup from "./install/DatabaseSetup"
import FinalSettings from "./install/FinalSettings"

Vue.component('language-selection', LanguageSelection).default
Vue.component('database-setup', DatabaseSetup).default
Vue.component('final-settings', FinalSettings).default

import store from './store'
import i18n from './plugins/i18n'

const app = new Vue({
    i18n,
    store
}).$mount('#app')

