<template>
  <div>
    <v-container grid-list-md text-xs-center>
      <v-layout v-resize="onResize" row wrap>
        <v-flex xs12>
          <v-toolbar flat color="white">
            <v-toolbar-title>Roles</v-toolbar-title>
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
                <v-btn
                  :disabled="!selected.length > 0"
                  flat
                  small
                  icon
                  color="grey darken-2"
                  v-on="on"
                  @click="deleteRoles"
                ><v-icon>delete</v-icon></v-btn>
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
                <v-btn flat small icon color="grey darken-2" v-on="on"><v-icon>import_export</v-icon></v-btn>
              </template>
              <span>Export</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn flat small icon color="grey darken-2" v-on="on" @click="refreshData"><v-icon>autorenew</v-icon></v-btn>
              </template>
              <span>Refresh</span>
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
              :items="roles"
              :search="search"
              :loading="loadingStatus"
              disable-initial-sort
              class="elevation-1">
              <template v-slot:items="props">
                <td>
                  <v-checkbox
                    v-model="props.selected"
                    color="primary"
                    hide-details
                  ></v-checkbox>
                </td>
                <td class="text-md-left">{{ props.item.name }}</td>
                <td class="text-md-left">
                  <span
                    v-for="(perm, index) in props.item.permissions"
                    :key="index"
                     @click="popPerm(props.item)"
                  >
                    <v-chip small v-if="index === 0">{{ perm.name }}</v-chip>
                    <span
                      style="cursor:pointer;"
                      v-if="index === 1"
                      class="grey--text caption"
                    >(+{{ props.item.permissions.length - 1 }} others)</span>
                  </span>
                </td>
                <td class="justify-center">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-icon
                        small
                        class="mr-2"
                        v-on="on"
                        @click="editRole(props.item)">
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
                        @click="editRole(props.item)">
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
        <v-progress-linear v-if="loadingStatus" :indeterminate="loadingStatus" height="2"></v-progress-linear>

        <v-card-text>
          <v-container>
            <v-layout wrap>
              <v-flex md12>
                <v-text-field
                  v-model="editedRole.name"
                  :error-messages="nameErrors"
                  label="Name"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedRole.name.$touch()"
                ></v-text-field>
              </v-flex>

              <v-flex md12>
                <v-autocomplete
                  v-model="editedRole.permissions"
                  :error-messages="permErrors"
                  @input="$v.editedRole.permissions.$touch()"
                  required
                  :items="perms"
                  item-text="name"
                  item-value="id"
                  multiple
                  chips
                  small-chips
                  label="Select Permissions"
                >
                  <template v-slot:selection="{ item, index }">
                    <v-chip small v-if="index === 0">
                      <span>{{ item.name }}</span>
                    </v-chip>
                    <span
                      v-if="index === 1"
                      class="grey--text caption"
                    >(+{{ editedRole.permissions.length - 1 }} others)</span>
                  </template>
                </v-autocomplete>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="close">Cancel</v-btn>
          <v-btn color="blue darken-1" flat @click="save" :loading="loadingStatus">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- END OF DIALOG -->
    <PermissionsDialog />
  </div>
</template>

<script>

  import { mapState } from 'vuex'
  import { mapActions } from 'vuex'
  import { mapGetters } from 'vuex'

  import { validationMixin } from 'vuelidate'
  import { required } from 'vuelidate/lib/validators'

  import PermissionsDialog from './templates/PermissionsDialog'

  export default {
    components: {
      PermissionsDialog
    },

    mixins: [validationMixin],

    data () {
      return {
        search: '',
        selected: [],
        headers: [
          { text: 'Select', align: 'left', value: 'select-all', sortable: false },
          { text: 'Name', align: 'left', value: 'name' },
          { text: 'Permissions', align: 'left', value: 'permissions' },
          { text: 'Actions', align: 'center', value: 'name', sortable: false }
        ],
        editedIndex: -1,
        editedRole: {
          name: '',
          permissions: [ { id: '', name: ''} ],
        },
        defaultRole: {
          name: '',
          permissions: [],
        },
      }
    },

    validations: {
      editedRole: {
        name:
        {
          required,
          isUnique(value) { // custom validation
            if (value === '') return true

            const isName = this.roles.filter(role => {return role.name == value}).length
            if (isName === 1) {
              return false
            } else {
              return true
            }
          }
        },
        permissions:
        { required },
      },
    },

    computed: {
      ...mapGetters({
        roles: 'role/getRoles',
        perms: 'perm/getPerms',
        roleErrors: 'role/getRoleErrors',
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

      formTitle () {
        return this.editedIndex === -1 ? 'New Role' : 'Edit Role'
      },

      nameErrors () {
        const errors = []
        if (!this.$v.editedRole.name.$dirty) return errors

        var nameRequiredError = null
        if (this.roleErrors['name|required']) {
          nameRequiredError = this.roleErrors['name|required']
        } else {
          nameRequiredError = 'The name field is required.'
        }

        var nameUniqueError = null
        if (this.roleErrors['name|unique']) {
          nameUniqueError = this.roleErrors['name|unique']
        } else {
          nameUniqueError = 'The name already taken.'
        }
        
        !this.$v.editedRole.name.required && errors.push(nameRequiredError)
        !this.$v.editedRole.name.isUnique && errors.push(nameUniqueError)
        return errors
      },

      permErrors () {
        const errors = []
        if (!this.$v.editedRole.permissions.$dirty) return errors

        var permRequiredError = null
        if (this.roleErrors['permissions|required']) {
          permRequiredError = this.roleErrors['permissions|required']
        } else {
          permRequiredError = 'The permission field is required.'
        }
        
        !this.$v.editedRole.permissions.required && errors.push(permRequiredError)
        return errors
      },
    },

    watch: {
      roles () {
        this.$v.$reset() // reset validation
      }
    },

    created () {
      this.$store.dispatch('role/fetchRoles')
      this.$store.dispatch('perm/fetchPerms')
    },

    methods: {
      refreshData () {
        this.$store.dispatch('role/fetchRoles')
        this.$store.dispatch('perm/fetchPerms')
      },

      onResize() {
        if (window.innerWidth < 769)
          this.$store.dispatch('isMobile', true)
        else
          this.$store.dispatch('isMobile', false)
      },

      openDialog () {
        this.editedIndex = -1 // reset default: important
        this.editedRole = Object.assign({}, this.defaultRole) // reset to default
        this.$store.dispatch('triggerDialog', true)
      },

      editRole (role) {
        this.editedIndex = this.roles.indexOf(role) // get index: important
        this.editedRole = Object.assign({}, role)
        this.$store.dispatch('triggerDialog', true)
      },

      deleteRoles () {
        const roles = this.$data.selected
        const roleIds = roles.map(role => {
          return role.id
        })
        const roleNames = roles.map(role => {
          return role.name
        })

        this.$vuetifyConfirmDialog
          .open("Confirm Delete", "Are you sure to delete role " + roleNames + "?", "Cancel", "Confirm Delete")
          .then(state => {
            if (state) this.$store.dispatch('role/deleteRoles', roleIds)
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
          // update role
          this.$store.dispatch('role/updateRole', this.editedRole)
        } else {
          // store role
          this.$store.dispatch('role/storeRole', this.editedRole)
        }
      },

      popPerm (item) {
        let payload = {
          dialog: true,
          item: item
        }
        this.$store.commit('role/SET_ROLE_ITEM', payload) // open dialog
      }
    }
  }
</script>