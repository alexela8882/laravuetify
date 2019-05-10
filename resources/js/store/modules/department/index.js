import actions from './actions'

const state = {
	departments: [],
	errors: [],
}

const getters = {
	getDepartments (state) {
		return state.departments
	},
	getDepartmentErrors (state) {
		return state.errors
	}
}

const mutations = {
	SET_DEPARTMENT (state, departments) {
		state.departments = departments
	},

	STORE_DEPARTMENT (state, department) {
		state.departments.unshift(department)
	},

	UPDATE_DEPARTMENT (state, payload) {
		state.departments = state.departments.map(department => {
      if (department.id === payload.id) {
        return Object.assign({}, department, payload)
      }
      return department
    })
	},

	DELETE_DEPARTMENTS (state, payload) {
		for (var i = payload.length - 1; i >= 0; i--) {
			var index = state.departments.findIndex(department => department.id === payload[i].id)
			state.departments.splice(index, 1)
		}
	},

	DEPARTMENT_ERROR (state, errors) {
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