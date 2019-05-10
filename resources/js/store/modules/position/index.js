import actions from './actions'

const state = {
	positions: [],
	errors: [],
}

const getters = {
	getPositions (state) {
		return state.positions
	},
	getPositionErrors (state) {
		return state.errors
	}
}

const mutations = {
	SET_POSITION (state, positions) {
		state.positions = positions
	},

	STORE_POSITION (state, position) {
		state.positions.unshift(position)
	},

	UPDATE_POSITION (state, payload) {
		state.positions = state.positions.map(position => {
      if (position.id === payload.id) {
        return Object.assign({}, position, payload)
      }
      return position
    })
	},

	DELETE_POSITIONS (state, payload) {
		for (var i = payload.length - 1; i >= 0; i--) {
			var index = state.positions.findIndex(position => position.id === payload[i].id)
			state.positions.splice(index, 1)
		}
	},

	POSITION_ERROR (state, errors) {
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