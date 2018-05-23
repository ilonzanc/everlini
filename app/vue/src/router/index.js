import VueRouter from 'vue-router';

import Home from '../components/Home'
import Login from '../components/Login'
import Overview from '../components/Overview'
import Profile from '../components/Profile'

let routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/aanmelden',
        component: Login
    },
    {
        path: '/evenementen',
        component: Overview
    },
    {
        path: '/profiel',
        component: Profile
    }
]

export default new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior (to, from, savedPosition) { return { x: 0, y: 0 } }
});