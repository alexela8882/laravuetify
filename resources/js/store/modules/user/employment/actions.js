const actions = {
	fetchEmployments (context) {
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.get('api/user-employments')
			.then(response => {
				context.commit('SET_EMPLOYMENT', response.data)
				context.commit('LOADING_STATUS', false, { root: true })
			})
	},
	updateEmployment (context, employment) {
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.put('api/user-employments/update', employment)
			.then(response => {
				context.commit('UPDATE_EMPLOYMENT', response.data)
				context.commit('DIALOG_STATUS', false, { root: true }) // close dialog
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading

				let payload = [
					{ status: true, message: 'User Employment successfully updated.',
						timeout: 3000 },
				]
				context.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
			})
			.catch(error => {
				context.commit('EMPLOYMENT_ERROR', error.response.data) // get error from backend
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading
			})
	}
}

export default actions