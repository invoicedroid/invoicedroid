import axios from 'axios'
import i18n from './i18n'

// set defaults
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Request interceptor
// axios.interceptors.request.use(request => {
//     const token = store.getters['auth/token']
//
//     if (token) {
//         request.headers.common['Authorization'] = `Bearer ${token}`
//     }
//
//     const locale = store.getters['Lang/locale']
//
//     if (locale) {
//         request.headers.common['Accept-Language'] = locale
//     }
//
//     return request
// })
//
// // Response interceptor
// axios.interceptors.response.use(response => response, error => {
//     const { status } = error.response
//
//     if (status >= 500) {
//         alert('Server error!')
//     }
//
//     if (status === 401 && store.getters['auth/check']) {
//         alert('Authorization error');
//     }
//
//     return Promise.reject(error)
// })
