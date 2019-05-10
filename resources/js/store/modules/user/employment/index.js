import actions from './actions'

const state = {
	employments: [],
	errors: [],
}

const getters = {
	getEmployments (state) {
		return state.employments
	},
	getEmploymentErrors (state) {
		return state.errors
	}
}

const mutations = {
	SET_EMPLOYMENT (state, employments) {
		state.employments = employments
	},

	UPDATE_EMPLOYMENT (state, payload) {
		state.employments = state.employments.map(employment => {
      if (employment.id === payload.id) {
        return Object.assign({}, employment, payload)
      }
      return employment
    })
	},

	EMPLOYMENT_ERROR (state, errors) {
		state.errors = errors
	}
}

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions
}