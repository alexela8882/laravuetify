export function initialize(store, router) {
  router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const currentUser = store.state.currentUser;

    if(requiresAuth && !currentUser) {
      next('/login');
    } else if(to.path == '/login' && currentUser) {
      next('/');
    } else {
      next();
    }
  });
  
  axios.interceptors.response.use(null, (error) => {
    if (error.message == "Network Error") {
      const errorMessage = "Network Error"
      let payload = [
        { status: true, message: errorMessage,
          timeout: 3000 },
      ]
      store.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
      store.commit('LOADING_STATUS', false, { root: true })
    } else if (error.response.data.message == "Model have child records") {
      const errorMessage = "Model have child records"
      let payload = [
        { status: true, message: errorMessage,
          timeout: 3000 },
      ]
      store.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
      store.commit('LOADING_STATUS', false, { root: true })
    }

    // ROUTES ERROR HANDLING
    if (error.response.status == 401) {
      const errorMessage = "Session expired. Please re-login."
      let payload = [
        { status: true, message: errorMessage,
          timeout: 12000 },
      ]
      store.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
      store.commit('LOADING_STATUS', false, { root: true })

      store.commit('logout')
      router.push('/login')
    }

    if (error.response.status == 403) {
      router.push('/403');
    }

    if (error.response.status == 418) {
      const errorMessage = "Forbidden"
      let payload = [
        { status: true, message: errorMessage,
          timeout: 3000 },
      ]
      store.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
      store.commit('LOADING_STATUS', false, { root: true })
    }

    return Promise.reject(error);
  });

  if (store.getters.currentUser) {
    setAuthorization(store.getters.currentUser.token);
  }
}

export function setAuthorization(token) {
  axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}