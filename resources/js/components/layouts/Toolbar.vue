<template>
	<nav>
	  <v-toolbar app>
	    <v-toolbar-side-icon @click.stop="drawer"></v-toolbar-side-icon>
	    <v-toolbar-title></v-toolbar-title>
	    <v-spacer></v-spacer>
	    <v-toolbar-items>
	      <v-menu
		      :close-on-content-click="false"
		      :nudge-width="200"
		      offset-x
		    >
		      <template v-slot:activator="{ on }">
		        <v-btn flat v-on="on">
		        	<v-icon dark v-if="$vuetify.breakpoint.smAndDown">expand_more</v-icon>
		          <div class="hidden-sm-and-down">
		          	{{ currentUser.first_name }} {{ currentUser.last_name }}&nbsp;<v-icon>arrow_drop_down</v-icon></div>
		        </v-btn>
		      </template>

		      <v-card>
		        <v-list>
		          <v-list-tile avatar>
		            <v-list-tile-avatar>
		              <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
		            </v-list-tile-avatar>

		            <v-list-tile-content>
		              <v-list-tile-title>{{ currentUser.full_name }}</v-list-tile-title>
		              <v-list-tile-sub-title>
		              	{{ currentUser.employment.position ? currentUser.employment.position.name : 'Not Assigned' }}
		              </v-list-tile-sub-title>
		            </v-list-tile-content>

		            <v-list-tile-action>
		              <v-btn icon>
		                <v-icon>favorite</v-icon>
		              </v-btn>
		            </v-list-tile-action>
		          </v-list-tile>
		        </v-list>

		        <v-divider></v-divider>

		        <v-list>
		          <v-list-tile>
		            <v-list-tile-action>
		              <v-switch color="purple"></v-switch>
		            </v-list-tile-action>
		            <v-list-tile-title>Enable messages</v-list-tile-title>
		          </v-list-tile>

		          <v-list-tile>
		            <v-list-tile-action>
		              <v-switch color="purple"></v-switch>
		            </v-list-tile-action>
		            <v-list-tile-title>Enable hints</v-list-tile-title>
		          </v-list-tile>
		        </v-list>

		        <v-card-actions>
		        	<v-btn flat color="red darken-3" @click="logout">Sign Out</v-btn>
		          <v-spacer></v-spacer>

		          <v-btn flat>Cancel</v-btn>
		          <v-btn color="primary" flat>Save</v-btn>
		        </v-card-actions>
		      </v-card>
		    </v-menu>

	    </v-toolbar-items>
	  </v-toolbar>
	</nav>
</template>

<script>
	export default {
		methods: {
			drawer () {
				this.$store.state.drawer = !this.$store.state.drawer
			},

			logout () {
				this.$store.commit('logout');
        this.$router.push('/login');
			}
		},

		created () {
			console.log(this.$store.getters.currentUser)
		},

		computed: {
			currentUser () {
				return this.$store.getters.currentUser
			},

			loadingStatus () {
        return this.$store.state.loading
      },
		}
	}
</script>