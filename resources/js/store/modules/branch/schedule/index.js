import actions from './actions'

const state = {
	schedules: [],
	errors: [],
}

const getters = {
	getSchedules (state) {
		return state.schedules
	},
	getScheduleErrors (state) {
		return state.errors
	},
}

const mutations = {
	SET_SCHEDULE (state, schedules) {
		state.schedules = schedules
	},

	STORE_SCHEDULE (state, schedule) {
		state.schedules.unshift(schedule)
	},

	UPDATE_SCHEDULE (state, payload) {
		state.schedules = state.schedules.map(schedule => {
      if (schedule.id === payload.id) {
        return Object.assign({}, schedule, payload)
      }
      return schedule
    })
	},

	DELETE_SCHEDULES (state, payload) {
		for (var i = payload.length - 1; i >= 0; i--) {
			var index = state.schedules.findIndex(schedule => schedule.id === payload[i].id)
			state.schedules.splice(index, 1)
		}
	},

	SCHEDULE_ERROR (state, errors) {
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