import VueRouter from 'vue-router';

import Home from '../views/Home'

import Login from '../views/Login'
import Register from '../views/Register'

import About from '../views/About'
import Contact from '../views/Contact'
import PrivacyPolicy from '../views/PrivacyPolicy'

import Overview from '../views/Overview'
import EventDetail from '../views/EventDetail'

import Profile from '../views/Profile/Profile'
import ProfileEvents from '../views/Profile/ProfileEvents'
import ProfileEventDetail from '../views/Profile/ProfileEventDetail'

import CreateEvent from '../views/Profile/CreateEvent'
import UpdateEvent from '../views/Profile/UpdateEvent'

import CreatePost from '../views/Profile/CreatePost'
import UpdatePost from '../views/Profile/UpdatePost'



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
        path: '/profiel/jouw-events/:id',
        component: ProfileEventDetail
    },
    {
        path: '/profiel/jouw-events/:id/bewerken',
        component: UpdateEvent
    },
    {
        path: '/profiel/jouw-events/:id/nieuwe-blogpost',
        component: CreatePost
    },
    {
        path: '/profiel/jouw-events/:id/posts/:postid/bewerken',
        component: UpdatePost
    },
    {
        path: '/profiel/nieuw-event',
        component: CreateEvent
    },
]

export default new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior (to, from, savedPosition) { return { x: 0, y: 0 } }
});