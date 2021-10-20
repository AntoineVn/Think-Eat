import Vue from 'vue'
import Router from 'vue-router'
import Scan from '../components/Scan'
import Dashboard from '@/components/dashboard/Dashboard'
import Aliments from '@/components/aliments/listAliments'
import Feed from '@/components/feed/Feed.vue'
import Profile from '@/components/profile/editProfile.vue'
import Recap from '@/components/recap/recapAll.vue'
import Waste from '@/components/recap/trackWaste.vue'
import Eaten from '@/components/recap/trackEaten.vue'
import Processing from '@/components/recap/trackProcessing.vue'
import About from '@/components/About.vue'

Vue.use(Router)
import Home from '@/views/Home.vue'
import Login from '@/components/authentication/Login.vue'
import Register from '@/components/authentication/Register.vue'
import ForgotPassword from '@/components/authentication/ForgotPassword.vue'

export default new Router({
  mode : 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
    },
    {
      path: "/login",
      name: "Login",
      component: Login,
      /*       beforeEnter: ifNotAuthenticated */
    },
    {
      path: '/about',
      name: 'About',
      component: About,
    },
    {
      path: "/register",
      name: "Register",
      component: Register,
/*       beforeEnter: ifNotAuthenticated */
    },
    {
      path: '/forgot-password',
      name: 'Forgot Password',
      component: ForgotPassword,
    },
    {
      path: '/scan',
      name: 'Scan',
      component: Scan,
    },
    {
      path: '/feed',
      name: 'Feed',
      component: Feed,
      props : true,
    },
    {
      path: '/profile',
      name: 'Profile',
      component: Profile,
      props: true, 
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard,
      props: true, 
    },
    {
      path: '/recap',
      name: 'Recap',
      component: Recap,
      props: true, 
    },
    {
      path: "/eaten",
      name: 'Eaten',
      component: Eaten,
      props: true,
    },
    {
      path: "/waste",
      name: 'Waste',
      component: Waste,
      props: true,
    },
    {
      path: "/processing",
      name: 'Processing',
      component: Processing,
      props: true,
    },
    {
      path: '/:id',
      name: 'Aliments',
      component: Aliments,
      props : true,
    },
  ]
})

