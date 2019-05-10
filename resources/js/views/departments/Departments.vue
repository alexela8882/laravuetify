<template>
	<div>
    <v-container grid-list-md text-xs-center>
      <v-layout v-resize="onResize" row wrap>
        <v-flex xs12>
          <v-toolbar flat color="white">
            <v-toolbar-title>Departments</v-toolbar-title>
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
                  @click="deleteDepartments"
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
              :items="departments"
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
                <td class="justify-center">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-icon
                        small
                        class="mr-2"
                        v-on="on"
                        @click="editDepartment(props.item)">
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
                        @click="">
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
                  v-model="editedDepartment.name"
                  :error-messages="nameErrors"
                  label="Name"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedDepartment.name.$touch()"
                ></v-text-field>
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
  </div>
</template>

<script>

  import { mapState } from 'vuex'
  import { mapActions } from 'vuex'
  import { mapGetters } from 'vuex'

  import { validationMixin } from 'vuelidate'
  import { required, email } from 'vuelidate/lib/validators'

	export default {
    mixins: [validationMixin],

		data () {
			return {
        search: '',
        selected: [],
	      headers: [
          { text: 'Select', align: 'left', value: 'select-all', sortable: false },
          { text: 'Name', align: 'left', value: 'name' },
	        { text: 'Actions', align: 'center', value: 'action', sortable: false }
	      ],
	      editedIndex: -1,
	      editedDepartment: {
          name: '',
	      },
	      defaultDepartment: {
          name: '',
	      },
			}
		},

    validations: {
      editedDepartment: {
        name:
        {
          required,
          isUnique(value) { // custom validation
            if (value === '') return true

            const isName = this.departments.filter(department => department.name == value).length
            if (isName === 1) {
              return false
            } else {
              return true
            }
          }
        }
      },
    },

    computed: {
      ...mapGetters({
        departments: 'department/getDepartments',
        departmentErrors: 'department/getDepartmentErrors',
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
        return this.editedIndex === -1 ? 'New Department' : 'Edit Department'
      },

      nameErrors () {
        const errors = []
        if (!this.$v.editedDepartment.name.$dirty) return errors

        var nameRequiredError = null
        if (this.departmentErrors['name|required']) {
          nameRequiredError = this.departmentErrors['name|required']
        } else {
          nameRequiredError = 'The name field is required.'
        }

        var nameUniqueError = null
        if (this.departmentErrors['name|unique']) {
          nameUniqueError = this.departmentErrors['name|unique']
        } else {
          nameUniqueError = 'The name already taken.'
        }
        
        !this.$v.editedDepartment.name.required && errors.push(nameRequiredError)
        !this.$v.editedDepartment.name.isUnique && errors.push(nameUniqueError)
        return errors
      }
    },

    watch: {
      departments () {
        this.$v.$reset() // reset validation
      }
    },

    created () {
      this.$store.dispatch('department/fetchDepartments')
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
        this.editedDepartment = Object.assign({}, this.defaultDepartment) // reset to default
        this.$store.dispatch('triggerDialog', true)
      },

      editDepartment (department) {
        this.editedIndex = this.departments.indexOf(department) // get index: important
        this.editedDepartment = Object.assign({}, department)
        this.$store.dispatch('triggerDialog', true)
      },

      deleteDepartments () {
        const departments = this.$data.selected
        const departmentIds = departments.map(department => {
          return department.id
        })
        const departmentNames = departments.map(department => {
          return department.name
        })

        this.$vuetifyConfirmDialog
          .open("Confirm Delete", "Are you sure to delete department " + departmentNames + "?", "Cancel", "Confirm Delete")
          .then(state => {
            if (state) this.$store.dispatch('department/deleteDepartments', departmentIds)
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
          // update department
          this.$store.dispatch('department/updateDepartment', this.editedDepartment)
        } else {
          // store department
          this.$store.dispatch('department/storeDepartment', this.editedDepartment)
        }
      }
    }
	}
</script>