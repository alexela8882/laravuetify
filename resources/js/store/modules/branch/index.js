import actions from './actions'

const state = {
	branches: [],
	errors: [],
}

const getters = {
	getBranches (state) {
		return state.branches
	},
	getBranchErrors (state) {
		return state.errors
	},
}

const mutations = {
	SET_BRANCH (state, branches) {
		state.branches = branches
	},

	STORE_BRANCH (state, branch) {
		state.branches.unshift(branch)
	},

	UPDATE_BRANCH (state, payload) {
		state.branches = state.branches.map(branch => {
      if (branch.id === payload.id) {
        return Object.assign({}, branch, payload)
      }
      return branch
    })
	},

	DELETE_BRANCHES (state, payload) {
		for (var i = payload.length - 1; i >= 0; i--) {
			var index = state.branches.findIndex(branch => branch.id === payload[i].id)
			state.branches.splice(index, 1)
		}
	},

	BRANCH_ERROR (state, errors) {
		state.errors = errors
	},
}

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions
}