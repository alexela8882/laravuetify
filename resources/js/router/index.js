import Vue from 'vue'
import VueRouter from 'vue-router'
import NProgress from 'nprogress'

Vue.use(VueRouter)

import authRoutes from './authRoutes'
import errorRoutes from './errorRoutes'
import viewRoutes from './viewRoutes'

var allRoutes = []
allRoutes = allRoutes.concat(authRoutes, errorRoutes, viewRoutes)

const routes = allRoutes

const router = new VueRouter({
	mode: 'history',
	base: '/laravuetify/public/',
	routes: routes
})

router.beforeResolve((to, from, next) => {
  // If this isn't an initial page load.
  if (to.name) {
    // Start the route progress bar.
    NProgress.start()
  }
  next()
})

router.afterEach((to, from) => {
  // Complete the animation of the route progress bar.
  NProgress.done()
})

export default router