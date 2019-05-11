<template>
	<footer>
		<v-footer app inset absolute>
	    <v-card class="flex" flat>
	      <v-card-actions class="justify-center">
	        &copy;&nbsp;{{ new Date().getFullYear() }} — <strong>&nbsp;Laravuetify</strong>
	      </v-card-actions>
	    </v-card>
	  </v-footer>
	</footer>
</template>

<script>
	export default {
		data () {
			return {
				icons: [
	        'fab fa-facebook',
	        'fab fa-twitter',
	        'fab fa-google-plus',
	        'fab fa-linkedin',
	        'fab fa-instagram'
	      ]
			}
		}
	}
</script>