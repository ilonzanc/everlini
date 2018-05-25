import VueRouter from 'vue-router';

import Home from '../components/Home'
import Login from '../components/Login'
import Register from '../components/Register'
import Overview from '../components/Overview'
import Profile from '../components/Profile'
import ProfileEvents from '../components/ProfileEvents'
import CreateEvent from '../components/CreateEvent'
import UpdateEvent from '../components/UpdateEvent'

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
        path: '/registreren',
        component: Register
    },
    {
        path: '/evenementen',
        component: Overview
    },
    {
        path: '/profiel',
        component: Profile
    },
    {
        path: '/profiel/jouw-events',
        component: ProfileEvents
    },
    {
        path: '/profiel/nieuw-event',
        component: CreateEvent
    },
    {
        path: '/profiel/jouw-events/:id/bewerken',
        component: UpdateEvent
    }
]

export default new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior (to, from, savedPosition) { return { x: 0, y: 0 } }
});