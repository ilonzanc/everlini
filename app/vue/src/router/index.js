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
import ProfileAdminInvite from '../views/ProfileAdminInvite'
import ProfileEvents from '../views/Dashboard/ProfileEvents'
import ProfileEventDetail from '../views/Dashboard/ProfileEventDetail'

import CreateEvent from '../views/Dashboard/CreateEvent'
import UpdateEvent from '../views/Dashboard/UpdateEvent'

import CreatePost from '../views/Dashboard/CreatePost'
import UpdatePost from '../views/Dashboard/UpdatePost'

import CreateOrganisation from '../views/Dashboard/CreateOrganisation'

import Dashboard from '../views/Dashboard/Dashboard'

import OrganisationDashboard from '../views/Dashboard/OrganisationDashboard'



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
        path: '/profiel/:username',
        component: Profile
    },
    {
        path: '/profiel/:username/uitnodigen-als-admin',
        component: ProfileAdminInvite
    },
    {
        path: '/profiel/jouw-events',
        component: ProfileEvents
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
        path: 'dashboard/organisatie-aanmaken',
        component: CreateOrganisation
    },
    {
        path: '/dashboard',
        component: Dashboard
    },
    {
        path: '/dashboard/organisatie-aanmaken',
        component: CreateOrganisation
    },
    {
        path: '/dashboard/:name/:id',
        component: OrganisationDashboard
    },
    {
        path: '/dashboard/:name/:id/nieuw-event',
        component: CreateEvent
    },
    {
        path: '/dashboard/:name/:id/events/:eventid',
        component: ProfileEventDetail
    },
]

export default new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior (to, from, savedPosition) { return { x: 0, y: 0 } }
});