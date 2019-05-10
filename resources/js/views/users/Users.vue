<template>
	<div>
    <v-container grid-list-md text-xs-center>
      <v-layout v-resize="onResize" row wrap>
        <v-flex xs12>
          <v-toolbar flat color="white">
            <v-toolbar-title>Users</v-toolbar-title>
            <v-divider
              class="mx-2"
              inset
              vertical></v-divider>

            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn flat small icon color="grey darken-2" v-on="on" @click="openDialog"><v-icon>add</v-icon></v-btn>
              </template>
              <span>Add</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn :disabled="!selected.length > 0" flat small icon color="grey darken-2" v-on="on" @click="deleteUsers"><v-icon>delete</v-icon></v-btn>
              </template>
              <span>Delete</span>
            </v-tooltip>
            
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn flat small icon color="grey darken-2" v-on="on"><v-icon>print</v-icon></v-btn>
              </template>
              <span>Print</span>
            </v-tooltip>
            
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn flat small icon color="grey darken-2" v-on="on">
                  <json-excel :data="users"><v-icon>import_export</v-icon></json-excel>
                </v-btn>
              </template>
              <span>Export</span>
            </v-tooltip>

            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Search"
              single-line
            ></v-text-field>
          </v-toolbar>

          <v-card flat>
            <v-data-table
              v-model="selected"
              :hide-headers="isMobile"
              :class="{mobile: isMobile}"
              :headers="headers"
              :items="users"
              :search="search"
              :loading="loadingStatus"
              disable-initial-sort
              :expand="expand"
              item-key="id"
              class="elevation-1">
              <template v-slot:expand="props">
                <v-card flat>
                  <v-card-text>{{ props.item.branch.name }}</v-card-text>
                </v-card>
              </template>
              <template v-slot:items="props">
                <td @click="props.expanded = !props.expanded" style="cursor:pointer;">
                  <v-icon>keyboard_arrow_{{ props.expanded ? 'up' : 'down' }}</v-icon>
                </td>
                <td @click.stop="">
                  <v-checkbox
                    :disabled="props.item.id === loggedUser.id"
                    v-model="props.selected"
                    color="primary"
                    hide-details
                  ></v-checkbox>
                </td>
                <td class="text-md-left">{{ props.item.full_name }}</td>
                <td class="text-md-left">{{ props.item.email }}</td>
                <td class="text-md-left">
                  <span v-for="(role, index) in props.item.roles" @click.stop="popRole(props.item)">
                    <v-chip small v-if="index === 0">{{ role.name }}</v-chip>
                    <span
                      style="cursor:pointer;"
                      v-if="index === 0"
                      class="grey--text caption"
                    >
                      <span v-if="props.item.roles.length - 1 > 0">
                        (+{{ props.item.roles.length - 1 }} others)
                      </span>
                    </span>
                  </span>
                </td>
                <td class="justify-center">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-icon
                        small
                        class="mr-2"
                        v-on="on"
                        @click.stop="editUser(props.item)">
                        edit
                      </v-icon>
                    </template>
                    <span>Edit</span>
                  </v-tooltip>

                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-icon
                        small
                        class="mr-2"
                        v-on="on"
                        @click.stop="">
                        file_copy
                      </v-icon>
                    </template>
                    <span>Duplicate</span>
                  </v-tooltip>
                </td>
              </template>
            </v-data-table>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>

    <!-- START OF DIALOG -->
    <v-dialog persistent v-model="dialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-container>
            <v-layout wrap>
              <v-flex md12>
                <v-text-field
                  v-model="editedUser.first_name"
                  :error-messages="firstNameErrors"
                  label="First Name"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedUser.first_name.$touch()"
                ></v-text-field>
              </v-flex>
              <v-flex md12>
                <v-text-field
                  v-model="editedUser.last_name"
                  :error-messages="lastNameErrors"
                  label="Last Name"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedUser.last_name.$touch()"
                ></v-text-field>
              </v-flex>
              <v-flex md12>
                <v-text-field
                  v-model="editedUser.email"
                  :error-messages="emailErrors"
                  label="Email"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedUser.email.$touch()"
                ></v-text-field>
              </v-flex>
              <v-flex md12>
                <v-autocomplete
                  v-model="editedUser.branch"
                  :error-messages="branchErrors"
                  :items="branches"
                  item-text="name"
                  item-value="id"
                  label="Select Branch"
                ></v-autocomplete>
              </v-flex>
              <v-flex md12>
                <v-autocomplete
                  v-model="editedUser.roles"
                  required
                  :items="roles"
                  item-text="name"
                  item-value="id"
                  multiple
                  chips
                  small-chips
                  label="Select Roles"
                >
                  <template v-slot:selection="{ item, index }">
                    <v-chip small v-if="index === 0">
                      <span>{{ item.name }}</span>
                    </v-chip>
                    <span
                      v-if="index === 1"
                      class="grey--text caption"
                    >(+{{ editedUser.roles.length - 1 }} others)</span>
                  </template>
                </v-autocomplete>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="close">Cancel</v-btn>
          <v-btn color="blue darken-1" flat @click="save" :loading="loadingStatus">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- END OF DIALOG -->
    <RolesDialog />
  </div>
</template>

<script>

  import { mapState } from 'vuex'
  import { mapActions } from 'vuex'
  import { mapGetters } from 'vuex'

  import { validationMixin } from 'vuelidate'
  import { required, email } from 'vuelidate/lib/validators'

  import RolesDialog from './templates/RolesDialog'

  import JsonExcel from 'vue-json-excel'

	export default {
    components: {
      JsonExcel,
      RolesDialog
    },

    mixins: [validationMixin],

		data () {
			return {
        expand: false,
        search: '',
        selected: [],
	      headers: [
          { text: '', align: 'left', value: 'expand', sortable: false },
          { text: 'Select', align: 'left', value: 'select-all', sortable: false },
          { text: 'Name', align: 'left', value: 'full_name' },
          { text: 'Email', align: 'left', value: 'email' },
	        { text: 'Roles', align: 'left', value: 'roles', sortable: false },
	        { text: 'Actions', align: 'center', value: 'name', sortable: false }
	      ],
	      editedIndex: -1,
	      editedUser: {
          first_name: '',
	        last_name: '',
          email: '',
          branch: '',
          roles: [ { id: '', name: '' } ],
	      },
	      defaultUser: {
          first_name: '',
	        last_name: '',
          email: '',
          branch: '',
	        roles: [],
	      },
			}
		},

    validations: {
      editedUser: {
        first_name: { required },
        last_name: { required },
        branch: { required },
        email: {
          required,
          email,
          isUnique(value) { // custom validation
            if (value === '') return true

            const isEmail = this.users.filter(user => user.email == value).length
            if (isEmail > 0) {
              return false
            } else {
              return true
            }
          }
        },
      },
    },

    computed: {
      ...mapGetters({
        users: 'user/getUsers',
        branches: 'branch/getBranches',
        roles: 'role/getRoles',
        userErrors: 'user/getUserErrors',
      }),

      dialog () {
        return this.$store.state.dialog
      },

      loadingStatus () {
        return this.$store.state.loading
      },

      isMobile () {
        return this.$store.state.isMobile
      },

      loggedUser () {
        return this.$store.state.currentUser
      },

      formTitle () {
        return this.editedIndex === -1 ? 'New User' : 'Edit User'
      },

      firstNameErrors () {
        const errors = []
        if (!this.$v.editedUser.first_name.$dirty) return errors

        var firstNameRequiredError = null
        if (this.userErrors['first_name|required']) {
          firstNameRequiredError = this.userErrors['first_name|required']
        } else {
          firstNameRequiredError = 'The first name field is required.'
        }
        
        !this.$v.editedUser.first_name.required && errors.push(firstNameRequiredError)
        return errors
      },
      lastNameErrors () {
        const errors = []
        if (!this.$v.editedUser.last_name.$dirty) return errors

        var firstNameRequiredError = null
        if (this.userErrors['last_name|required']) {
          firstNameRequiredError = this.userErrors['last_name|required']
        } else {
          firstNameRequiredError = 'The last name field is required.'
        }
        
        !this.$v.editedUser.last_name.required && errors.push(firstNameRequiredError)
        return errors
      },
      emailErrors () {
        const errors = []
        if (!this.$v.editedUser.email.$dirty) return errors

        var emailRequiredError = null
        if (this.userErrors['email|required']) {
          emailRequiredError = this.userErrors['email|required']
        } else {
          emailRequiredError = 'The email field is required.'
        }

        var emailUniqueError = null
        if (this.userErrors['email|unique']) {
          emailUniqueError = this.userErrors['email|unique']
        } else {
          emailUniqueError = 'The email has already been taken.'
        } 

        !this.$v.editedUser.email.email && errors.push('Must be valid e-mail')
        !this.$v.editedUser.email.required && errors.push(emailRequiredError)
        !this.$v.editedUser.email.isUnique && errors.push(emailUniqueError)
        return errors
      },
      branchErrors () {
        const errors = []
        if (!this.$v.editedUser.branch.$dirty) return errors

        var branchRequiredError = null
        if (this.userErrors['branch|required']) {
          branchRequiredError = this.userErrors['branch|required']
        } else {
          branchRequiredError = 'Please select branch.'
        }
        
        !this.$v.editedUser.branch.required && errors.push(branchRequiredError)
        return errors
      }
    },

    watch: {
      users () {
        this.$v.$reset() // reset validation
      }
    },

    created () {
      this.$store.dispatch('user/fetchUsers')
      this.$store.dispatch('branch/fetchBranches')
      this.$store.dispatch('role/fetchRoles')
    },

    methods: {
      onResize() {
        if (window.innerWidth < 769)
          this.$store.dispatch('isMobile', true)
        else
          this.$store.dispatch('isMobile', false)
      },

      openDialog () {
        this.editedIndex = -1 // reset default: important
        this.editedUser = Object.assign({}, this.defaultUser) // reset to default
        this.$store.dispatch('triggerDialog', true)
      },

      editUser (user) {
        this.editedIndex = this.users.indexOf(user) // get index: important
        
        const roleIds = user.roles.map(role => role.id) // get role ids
        this.editedUser = Object.assign({}, {
                            id: user.id,
                            first_name: user.first_name,
                            last_name: user.last_name,
                            email: user.email,
                            branch: user.branch.id,
                            roles: roleIds,
                          })
        this.$store.dispatch('triggerDialog', true)
      },

      deleteUsers () {
        const users = this.$data.selected
        const userIds = users.map(user => {
          return user.id
        })
        const userNames = users.map(user => {
          const fullName = user.first_name +  ' ' + user.last_name
          return fullName
        })

        this.$vuetifyConfirmDialog
          .open("Confirm Delete", "Are you sure to delete user " + userNames + "?", "Cancel", "Confirm Delete")
          .then(state => {
            if (state) this.$store.dispatch('user/deleteUsers', userIds)
          })
      },

      close () {
        this.editedIndex = -1
        this.$v.$reset()
        this.$store.dispatch('triggerDialog', false)
      },

      save () {
        this.$v.$touch()
        if (this.editedIndex > -1) {
          // update user
          this.$store.dispatch('user/updateUser', this.editedUser)
        } else {
          // store user
          this.$store.dispatch('user/storeUser', this.editedUser)
        }
      },

      popRole (item) {
        let payload = {
          dialog: true,
          item: item
        }
        this.$store.commit('user/SET_USER_ITEM', payload) // open dialog
      }
    }
	}
</script>