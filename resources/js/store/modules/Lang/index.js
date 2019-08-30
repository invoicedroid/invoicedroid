import Cookies from 'js-cookie'
const { locale } = window.config

function initialState() {
    return {
        locale: Cookies.get('locale') || locale
    }
}

const getters = {
    locale: state => state.locale
}


// mutations
export const mutations = {
    setLocale(state, { locale }) {
        state.locale = locale
    }
}

// actions
export const actions = {
    setLocale ({ commit }, { locale }) {
        commit('setLocale', { locale })
        Cookies.set('locale', locale, { expires: 365 })
    }
}


export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
