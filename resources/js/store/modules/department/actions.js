import router from '../../../router/index'

const actions = {
	fetchDepartments (context) {
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.get('api/departments')
			.then(response => {
				context.commit('SET_DEPARTMENT', response.data)
				context.commit('LOADING_STATUS', false, { root: true })
			})
	},
	storeDepartment (context, data) {
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.post('api/departments', data)
			.then(response => {
				context.commit('STORE_DEPARTMENT', response.data)
				context.commit('DIALOG_STATUS', false, { root: true }) // close dialog
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading

				let payload = [
					{ status: true,
						message: 'Department successfully added.',
						timeout: 3000 },
				]
				context.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
			})
			.catch(error => {
				context.commit('DEPARTMENT_ERROR', error.response.data) // get error from backend
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading
			})
	},
	updateDepartment (context, department) {
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.put('api/departments/update', department)
			.then(response => {
				context.commit('UPDATE_DEPARTMENT', response.data)
				context.commit('DIALOG_STATUS', false, { root: true }) // close dialog
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading

				let payload = [
					{ status: true, message: 'Department successfully updated.',
						timeout: 3000 },
				]
				context.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
			})
			.catch(error => {
				context.commit('DEPARTMENT_ERROR', error.response.data) // get error from backend
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading
			})
	},
	deleteDepartments (context, departmentIds) {
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.patch('api/departments/delete/multiple', departmentIds)
			.then(response => {
				context.commit('DELETE_DEPARTMENTS', response.data)
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading

				let payload = [
					{ status: true, message: 'Department(s) successfully deleted.',
						timeout: 3000 },
				]
				context.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
			})
			.catch(error => {
				console.log(error.response.data)
			})
	}
}

export default actions