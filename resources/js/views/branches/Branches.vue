<template>
	<div>
    <v-container grid-list-md text-xs-center>
      <v-layout v-resize="onResize" row wrap>
        <v-flex xs12>
          <v-toolbar flat color="white">
            <v-toolbar-title>Branches</v-toolbar-title>
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
                  @click="deleteBranches"
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
              :items="branches"
              :search="search"
              :expand="expand"
              :loading="loadingStatus"
              disable-initial-sort
              class="elevation-1">
              <template v-slot:expand="props">
                <v-card flat>
                  <v-card-text>
                    {{ props.item.schedule ? props.item.schedule.time : '[ schedule ]' }}
                    <v-spacer></v-spacer>
                    {{ props.item.whscode ? props.item.whscode : '[ whscode ]' }}
                    <v-spacer></v-spacer>
                    {{ props.item.bm_oic ? props.item.bm_oic : '[ bm/oic ]' }}
                  </v-card-text>
                </v-card>
              </template>
              <template v-slot:items="props">
                <td @click="props.expanded = !props.expanded" style="cursor:pointer;">
                  <v-icon>keyboard_arrow_{{ props.expanded ? 'up' : 'down' }}</v-icon>
                </td>
                <td @click.stop="">
                  <v-checkbox
                    :disabled="props.item.users.length > 0"
                    v-model="props.selected"
                    color="primary"
                    hide-details
                  ></v-checkbox>
                </td>
                <td class="text-md-left">{{ props.item.machine_number }}</td>
                <td class="text-md-left">{{ props.item.name }}</td>
                <td class="text-md-left">{{ props.item.region.name }}</td>
                <td class="justify-center">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-icon
                        small
                        class="mr-2"
                        v-on="on"
                        @click.stop="editBranch(props.item)">
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
    <v-dialog
      scrollable
      persistent
      v-model="dialog"
      max-width="500px"
    >
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
                  v-model="editedBranch.machine_number"
                  :error-messages="machineNumErrors"
                  label="Machine Number"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedBranch.machine_number.$touch()"
                ></v-text-field>
              </v-flex>
              <v-flex md12>
                <v-text-field
                  v-model="editedBranch.name"
                  :error-messages="nameErrors"
                  label="Name"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedBranch.name.$touch()"
                ></v-text-field>
              </v-flex>
              <v-flex md12>
                <v-select
                  v-model="editedBranch.schedule"
                  required
                  :items="schedules"
                  item-text="time"
                  item-value="id"
                  label="Select Schedule"
                ></v-select>
              </v-flex>
              <v-flex md12>
                <v-autocomplete
                  v-model="editedBranch.region"
                  :error-messages="regionErrors"
                  @input="$v.editedBranch.region.$touch()"
                  required
                  :items="regions"
                  item-text="name"
                  item-value="id"
                  label="Select Region"
                ></v-autocomplete>
              </v-flex>
              <v-flex md12>
                <v-text-field
                  v-model="editedBranch.whscode"
                  :error-messages="whsCodeErrors"
                  label="Whs Code"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedBranch.whscode.$touch()"
                ></v-text-field>
              </v-flex>
              <v-flex md12>
                <v-text-field
                  v-model="editedBranch.bm_oic"
                  label="BM/OIC"
                  required
                  :disabled="loadingStatus"
                ></v-text-field>
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
        expand: false,
        search: '',
        selected: [],
	      headers: [
          { text: '', align: 'left', value: 'expand', sortable: false },
          { text: 'Select', align: 'left', value: 'select-all', sortable: false },
          { text: 'Machine Number', align: 'left', value: 'machine_number' },
          { text: 'Name', align: 'left', value: 'name' },
          { text: 'Region', align: 'left', value: 'region.name' },
          { text: 'Whscode', align: 'left', value: 'whscode', class: 'd-none' },
          { text: 'BM/OIC', align: 'left', value: 'bm_oic', class: 'd-none' },
	        { text: 'Actions', align: 'center', value: 'name', sortable: false }
	      ],
	      editedIndex: -1,
	      editedBranch: {
          name: '',
	        machine_number: '',
          whscode: '',
          bm_oic: '',

          schedule: {},
          region: {},
	      },
	      defaultBranch: {
          name: '',
	        machine_number: '',
          whscode: '',
          bm_oic: '',

          schedule: {},
          region: {},
	      },
			}
		},

    validations: {
      editedBranch: {
        name:
        {
          required,
          isUnique(value) { // custom validation
            if (value === '') return true

            const isName = this.branches.filter(branch => branch.name == value).length
            if (isName === 1) {
              return false
            } else {
              return true
            }
          }
        },
        region: { required },
        machine_number:
        {
          required,
          isUnique(value) { // custom validation
            if (value === '') return true

            const isMachineNum = this.branches.filter(branch => branch.machine_number == value).length
            if (isMachineNum === 1) {
              return false
            } else {
              return true
            }
          }
        },
        whscode:
        {
          required,
          isUnique(value) { // custom validation
            if (value === '') return true

            const isWhsCode = this.branches.filter(branch => {return branch.whscode == value}).length
            if (isWhsCode === 1) {
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
        branches: 'branch/getBranches',
        branchErrors: 'branch/getBranchErrors',
        regions: 'region/getRegions',
        schedules: 'bsched/getSchedules',
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
        return this.editedIndex === -1 ? 'New Branch' : 'Edit Branch'
      },

      nameErrors () {
        const errors = []
        if (!this.$v.editedBranch.name.$dirty) return errors

        var nameRequiredError = null
        if (this.branchErrors['name|required']) {
          nameRequiredError = this.branchErrors['name|required']
        } else {
          nameRequiredError = 'The name field is required.'
        }

        var nameUniqueError = null
        if (this.branchErrors['name|unique']) {
          nameUniqueError = this.branchErrors['name|unique']
        } else {
          nameUniqueError = 'The name already taken.'
        }
        
        !this.$v.editedBranch.name.required && errors.push(nameRequiredError)
        !this.$v.editedBranch.name.isUnique && errors.push(nameUniqueError)
        return errors
      },

      regionErrors () {
        const errors = []
        if (!this.$v.editedBranch.region.$dirty) return errors

        var regionRequiredError = null
        if (this.branchErrors['region|required']) {
          regionRequiredError = this.branchErrors['region|required']
        } else {
          regionRequiredError = 'The region field is required.'
        }
        
        !this.$v.editedBranch.region.required && errors.push(regionRequiredError)
        return errors
      },

      machineNumErrors () {
        const errors = []
        if (!this.$v.editedBranch.machine_number.$dirty) return errors

        var machineNumRequiredError = null
        if (this.branchErrors['machine_number|required']) {
          machineNumRequiredError = this.branchErrors['machine_number|required']
        } else {
          machineNumRequiredError = 'The machine number field is required.'
        }

        var machineNumUniqueError = null
        if (this.branchErrors['machine_number|unique']) {
          machineNumUniqueError = this.branchErrors['machine_number|unique']
        } else {
          machineNumUniqueError = 'The machine number has already been taken.'
        }
        
        !this.$v.editedBranch.machine_number.required && errors.push(machineNumRequiredError)
        !this.$v.editedBranch.machine_number.isUnique && errors.push(machineNumUniqueError)
        return errors
      },

      whsCodeErrors () {
        const errors = []
        if (!this.$v.editedBranch.whscode.$dirty) return errors

        var whsCodeRequiredError = null
        if (this.branchErrors['whscode|required']) {
          whsCodeRequiredError = this.branchErrors['whscode|required']
        } else {
          whsCodeRequiredError = 'The whscode field is required.'
        }

        var whsCodeUniqueError = null
        if (this.branchErrors['whscode|unique']) {
          whsCodeUniqueError = this.branchErrors['whscode|unique']
        } else {
          whsCodeUniqueError = 'The whscode has already been taken.'
        }
        
        !this.$v.editedBranch.whscode.required && errors.push(whsCodeRequiredError)
        !this.$v.editedBranch.whscode.isUnique && errors.push(whsCodeUniqueError)
        return errors
      },
    },

    watch: {
      branches () {
        this.$v.$reset() // reset validation
      }
    },

    created () {
      this.$store.dispatch('branch/fetchBranches')
      this.$store.dispatch('region/fetchRegions')
      this.$store.dispatch('bsched/fetchSchedules')
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
        this.editedBranch = Object.assign({}, this.defaultBranch) // reset to default
        this.$store.dispatch('triggerDialog', true)
      },

      editBranch (branch) {
        this.editedIndex = this.branches.indexOf(branch) // get index: important
        this.editedBranch = Object.assign({}, {
                              id: branch.id,
                              name: branch.name,
                              schedule: branch.schedule ? branch.schedule.id : null,
                              region: branch.region.id,
                              machine_number: branch.machine_number,
                              whscode: branch.whscode,
                              bm_oic: branch.bm_oic
                            })
        this.$store.dispatch('triggerDialog', true)
      },

      deleteBranches () {
        const branches = this.$data.selected
        const branchIds = branches.map(branch => {
          return branch.id
        })
        const branchNames = branches.map(branch => {
          return branch.name
        })

        this.$vuetifyConfirmDialog
          .open("Confirm Delete", "Are you sure to delete branch " + branchNames + "?", "Cancel", "Confirm Delete")
          .then(state => {
            if (state) this.$store.dispatch('branch/deleteBranches', branchIds)
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
          // update branch
          this.$store.dispatch('branch/updateBranch', this.editedBranch)
        } else {
          // store branch
          this.$store.dispatch('branch/storeBranch', this.editedBranch)
        }
      }
    }
	}
</script>