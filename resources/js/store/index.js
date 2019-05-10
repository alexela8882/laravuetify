import Vue from 'vue'
import Vuex from 'vuex'

import user from './modules/user/index'
import employment from './modules/user/employment/index'
import position from './modules/position/index'
import department from './modules/department/index'
import branch from './modules/branch/index'
import bsched from './modules/branch/schedule/index'
import region from './modules/region/index'
import role from './modules/role/index'
import permission from './modules/permission/index'

import { getLocalUser } from "../helpers/auth";

const localUser = getLocalUser();

Vue.use(Vuex)

const store = new Vuex.Store({
	modules: {
		user: user,
		employment: employment,
		position: position,
		department: department,
		branch: branch,
		bsched: bsched,
		region: region,
		role: role,
		perm: permission,
	},

	state: {
		windowSize: {
			width: 0,
			height: 0,
		},
		drawer: true,
		loading: true,
		dialog: false,
    snackbar: false,
    snackbarText: '',
    snackbarTimeout: 0,

    // auth
    currentUser: localUser,
    isLoggedIn: !!localUser,
    auth_error: null,
	},

	getters: {
		// auth
    isLoggedIn(state) {
      return state.isLoggedIn;
    },
    currentUser(state) {
      return state.currentUser;
    },
    authError(state) {
      return state.auth_error;
    }
	},

	mutations: {
		WINDOW_SIZE (state) {
			state.windowSize.width = window.innerWidth
			state.windowSize.height = window.innerHeight
		},

		DRAWER_STATUS (state, status) {
			state.drawer = status
		},
		LOADING_STATUS (state, loading) {
			state.loading = loading
		},
		DIALOG_STATUS (state, dialog) {
			state.dialog = dialog
		},
		SNACKBAR_STATUS (state, payload) {
			state.loading = false; // set loading to false

			state.snackbar = payload[0].status
			state.snackbarText = payload[0].message
			state.snackbarTimeout = payload[0].timeout

			// change snackbar state afer timeout
			setTimeout(function(){
        state.snackbar = false
      }, payload[0].timeout);
		},

		// auth
		login(state) {
      state.loading = true;
      state.auth_error = null;
    },
    loginSuccess(state, payload) {
      state.auth_error = null;
      state.isLoggedIn = true;
      state.loading = false;
      state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

      localStorage.setItem("user", JSON.stringify(state.currentUser));
    },
    logout(state) {
      localStorage.removeItem("user");
      state.isLoggedIn = false;
      state.currentUser = null;
      state.loading = false;
    },
	},

	actions: {
		triggerDialog (context, dialog) {
			context.commit('DIALOG_STATUS', dialog)
		},

		// auth
		login(context) {
      context.commit("login");
    },
	}

})

export default store