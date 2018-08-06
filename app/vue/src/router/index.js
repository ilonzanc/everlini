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
import ProfileEvents from '../views/Dashboard/ProfileEvents'
import ProfileEventDetail from '../views/Dashboard/ProfileEventDetail'

import CreateEvent from '../views/Dashboard/CreateEvent'
import UpdateEvent from '../views/Dashboard/UpdateEvent'

import CreatePost from '../views/Dashboard/CreatePost'
import UpdatePost from '../views/Dashboard/UpdatePost'

import CreateOrganisation from '../views/Dashboard/CreateOrganisation'

import Dashboard from '../views/Dashboard/Dashboard'



let routes = [
    {
        path: '/',
        component: Home,
        name: 'home'
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
    {
        path: '/organisatie-aanmaken',
        component: CreateOrganisation
    },
    {
        path: '/organisatie-dashboard',
        component: Dashboard
    },
]

export default new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior (to, from, savedPosition) { return { x: 0, y: 0 } }
});