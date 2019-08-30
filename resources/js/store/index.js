import Vue from 'vue'
import Vuex from 'vuex'

import Lang from './modules/Lang'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        Lang
    },
    strict: debug
})
