import VueRouter from 'vue-router';

import Home from '../views/Home'

import Login from '../views/Login'
import Register from '../views/Register'

import About from '../views/About'
import Contact from '../views/Contact'
import PrivacyPolicy from '../views/PrivacyPolicy'

import Overview from '../views/Overview'
import EventDetail from '../views/EventDetail'

import Profile from '../views/Profile'
import ProfileEvents from '../views/ProfileEvents'

import CreateEvent from '../views/CreateEvent'
import UpdateEvent from '../views/UpdateEvent'



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
        path: '/about',
        component: About
    },
    {
        path: '/contact',
        component: Contact
    },
    {
        path: '/privacy-policy',
        component: PrivacyPolicy
    },
    {
        path: '/evenementen',
        component: Overview
    },
    {
        path: '/evenementen/:id',
        component: EventDetail
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