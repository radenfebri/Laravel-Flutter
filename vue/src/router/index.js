//import vue
import Vue from 'vue'

//import vue router
import VueRouter from 'vue-router'

//use vue router on vue
Vue.use(VueRouter)

//create object instance vue router
const router = new VueRouter({
    routes: [{
            path: '/',
            name: 'home',
            component: () =>
                import ( /* webpackChunkName: "Auth" */ "@/views/home/Index"),
        },
        {
            path: '/login',
            name: 'login',
            component: () =>
                import ( /* webpackChunkName: "Auth" */ "@/views/auth/Index"),
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: () =>
                import ( /* webpackChunkName: "Auth" */ "@/views/dashboard/Index"),
        }
    ],
    mode: 'history'
})

export default router