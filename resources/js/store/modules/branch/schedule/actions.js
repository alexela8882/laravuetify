const actions = {
	fetchSchedules (context) {
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.get('api/bscheds')
			.then(response => {
				context.commit('SET_SCHEDULE', response.data)
				context.commit('LOADING_STATUS', false, { root: true })
			})
	},
	storeSchedule (context, data) {
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.post('api/bscheds', data)
			.then(response => {
				context.commit('STORE_SCHEDULE', response.data)
				context.commit('DIALOG_STATUS', false, { root: true }) // close dialog
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading

				let payload = [
					{ status: true,
						message: 'Schedule successfully added.',
						timeout: 3000 },
				]
				context.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
			})
			.catch(error => {
				context.commit('SCHEDULE_ERROR', error.response.data) // get error from backend
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading
			})
	},
	updateSchedule (context, schedule) {
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.put('api/bscheds/update', schedule)
			.then(response => {
				context.commit('UPDATE_SCHEDULE', response.data)
				context.commit('DIALOG_STATUS', false, { root: true }) // close dialog
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading

				let payload = [
					{ status: true, message: 'Schedule successfully updated.',
						timeout: 3000 },
				]
				context.commit('SNACKBAR_STATUS', payload, { root: true }) // show snackbar
			})
			.catch(error => {
				context.commit('SCHEDULE_ERROR', error.response.data) // get error from backend
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading
			})
	},
	deleteSchedules (context, scheduleIds) {
		console.log(scheduleIds)
		context.commit('LOADING_STATUS', true, { root: true }) // start loading
		axios.patch('api/bscheds/delete/multiple', scheduleIds)
			.then(response => {
				context.commit('DELETE_SCHEDULES', response.data)
				context.commit('LOADING_STATUS', false, { root: true }) // stop loading

				let payload = [
					{ status: true, message: 'Schedule(s) successfully deleted.',
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