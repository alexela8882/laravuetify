<template>
	<nav>
	  <v-navigation-drawer
	  	app
	  	floating
	  	:value="drawer"
	  	:mini-variant.sync="mini">
	    <v-toolbar flat class="transparent">
	      <v-list class="pa-0">
	        <v-list-tile avatar>
	          <v-list-tile-avatar>
	            <img :src="'images/logo.png'">
	          </v-list-tile-avatar>

	          <v-list-tile-content>
	            <v-list-tile-title>LARAVUETIFY</v-list-tile-title>
	          </v-list-tile-content>

	          <v-list-tile-action>
	            <v-btn
	              icon
	              @click.stop="mini = !mini"
	            >
	              <v-icon>chevron_left</v-icon>
	            </v-btn>
	          </v-list-tile-action>
	        </v-list-tile>
	      </v-list>
	    </v-toolbar>

	    <v-list class="pt-0" dense>
	      <v-divider></v-divider>

	      <template v-for="link in links">
		      <v-list-group
		      	:append-icon="link.subLinks ? 'keyboard_arrow_down' : ''"
		        :prepend-icon="link.icon"
		      >
		        <template v-slot:activator>
		          <v-list-tile router :to="!link.subLinks ? link.route : ''">
		            <v-list-tile-title>{{ link.text }}</v-list-tile-title>
		          </v-list-tile>
		        </template>

		        <v-list-tile
		        	v-for="(subLink, i2) in link.subLinks"
		        	:key="i2"
		        	v-if="!subLink.links"
		        	router
		        	:to="subLink.route"
		        >
			        <v-list-tile-action></v-list-tile-action>
			        <v-list-tile-title v-text="subLink.text"></v-list-tile-title>
			        <v-list-tile-action>
			        	<v-icon v-text="subLink.icon"></v-icon>
			        </v-list-tile-action>
			      </v-list-tile>

		        <v-list-group
		        	v-for="(subLink, i3) in link.subLinks"
		        	v-if="subLink.links"
		        	:key="i3"
		          no-action
		          sub-group
		        >
		          <template v-slot:activator>
		            <v-list-tile>
		              <v-list-tile-title>{{ subLink.text }}</v-list-tile-title>
		            </v-list-tile>
		          </template>

		          <v-list-tile
		          	v-for="(_subLink, i) in subLink.links"
		          	:key="i"
		          	router
		          	:to="_subLink.route"
		          >
		            <v-list-tile-title v-text="_subLink.text"></v-list-tile-title>
		            <v-list-tile-action>
		              <v-icon v-text="_subLink.icon"></v-icon>
		            </v-list-tile-action>
		          </v-list-tile>
		        </v-list-group>
		      </v-list-group>
	    	</template>
	    </v-list>
	  </v-navigation-drawer>
	</nav>
</template>

<script>
	export default {
		data () {
      return {
      	mini: false,
        links: [
        	{ text: 'Home', icon: 'home', route: '/' },
        	{
        		text: 'Administrative',
        		icon: 'business_center',
        		subLinks: [
        			{
        				text: 'Users',
        				links: [
				        	{
				        		text: 'Management',
				        		icon: 'account_circle',
				        		route: '/users'
				        	},
				        	{
				        		text: 'Employments',
				        		icon: 'assignment',
				        		route: '/user-employments'
				        	},
				        	{
				        		text: 'Positions',
				        		icon: 'assignment_ind',
				        		route: '/positions'
				        	},
				        	{
				        		text: 'Departments',
				        		icon: 'portrait',
				        		route: '/departments'
				        	}
			        	]
			        },
					    {
	        			text: 'Authorizations',
			        	links: [
				        	{
				        		text: 'Roles',
				        		icon: 'verified_user',
				        		route: '/roles'
				        	},
				        	{
				        		text: 'Permissions',
				        		icon: 'lock',
				        		route: '/permissions'
				        	}
			        	]
					    },
					    {
	        			text: 'Branches',
			        	links: [
				        	{
				        		text: 'Management',
				        		icon: 'store_mall_directory',
				        		route: '/branches'
				        	},
				        	{
				        		text: 'Schedules',
				        		icon: 'access_time',
				        		route: '/branch-schedules'
				        	},
				        	{
				        		text: 'Regions',
				        		icon: 'terrain',
				        		route: '/regions'
				        	},
			        	]
					    },
				    ]
			    }
        ],
      }
    },

    methods: {
			toggleDrawer () {
				this.$store.state.drawer = !this.$store.state.drawer
			},
		},

    computed: {
    	drawer () {
    		return this.$store.state.drawer
    	},

    	currentUser () {
    		return this.$store.getters.currentUser
    	}
    }
	}
</script>