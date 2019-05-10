<template>
	<div>
    <v-container grid-list-md text-xs-center>
      <v-layout v-resize="onResize" row wrap>
        <v-flex xs12>
          <v-toolbar flat color="white">
            <v-toolbar-title>User Employments</v-toolbar-title>
            <v-divider
              class="mx-2"
              inset
              vertical></v-divider
            >
            
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn flat small icon color="grey darken-2" v-on="on"><v-icon>print</v-icon></v-btn>
              </template>
              <span>Print</span>
            </v-tooltip>
            
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn flat small icon color="grey darken-2" v-on="on">
                  <json-excel :data="employments"><v-icon>import_export</v-icon></json-excel>
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
              :items="employments"
              :search="search"
              :loading="loadingStatus"
              disable-initial-sort
              :expand="expand"
              item-key="id"
              class="elevation-1">
              <template v-slot:expand="props">
                <v-card flat>
                  <v-card-text>
                    {{ props.item.payroll === 0 ? 'Cash' : 'ATM' }}
                    <v-spacer></v-spacer>
                    {{ props.item.department ? props.item.department.name : '[ department ]' }}
                    <v-spacer></v-spacer>
                    {{ props.item.branch.id == 1 ? (props.item.time ? props.item.time : '[ schedule ]') : '' }}
                  </v-card-text>
                </v-card>
              </template>
              <template v-slot:items="props">
                <td @click="props.expanded = !props.expanded" style="cursor:pointer;">
                  <v-icon>keyboard_arrow_{{ props.expanded ? 'up' : 'down' }}</v-icon>
                </td>
                <td class="text-md-left">{{ props.item.sss }}</td>
                <td class="text-md-left">{{ props.item.user.full_name }}</td>
                <td class="text-md-left">{{ props.item.branch.name }}</td>
                <td class="text-md-left">{{ props.item.position ? props.item.position.name : '' }}</td>
                <td class="justify-center">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-icon
                        small
                        class="mr-2"
                        v-on="on"
                        @click.stop="editEmployment(props.item)">
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
      persistent
      scrollable
      v-model="dialog"
      max-width="500px"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>
        <v-progress-linear v-if="loadingStatus" :indeterminate="loadingStatus" height="2"></v-progress-linear>
        <v-divider></v-divider>
        <v-card-text>
          <v-container>
            <v-layout wrap>
              <v-flex md12>
                <v-text-field
                  v-model="editedEmployment.user"
                  label="Employee"
                  disabled
                ></v-text-field>
                <v-text-field
                  v-model="editedEmployment.sss"
                  :error-messages="sssErrors"
                  label="SSS"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedEmployment.sss.$touch()"
                ></v-text-field>
              </v-flex>
              <v-flex md12>
                <v-autocomplete
                  v-model="editedEmployment.branch"
                  :items="branches"
                  item-text="name"
                  item-value="id"
                  label="Select Branch"
                ></v-autocomplete>
              </v-flex>
              <v-flex md12>
                <v-autocomplete
                  v-model="editedEmployment.position"
                  :error-messages="positionErrors"
                  :items="positions"
                  item-text="name"
                  item-value="id"
                  label="Select Position"
                ></v-autocomplete>
              </v-flex>
              <v-flex md12>
                <v-autocomplete
                  v-model="editedEmployment.department"
                  :error-messages="departmentErrors"
                  :items="departments"
                  item-text="name"
                  item-value="id"
                  label="Select Department"
                ></v-autocomplete>
              </v-flex>
              <v-flex md12>
                <v-autocomplete
                  v-model="editedEmployment.payroll"
                  :error-messages="payrollErrors"
                  :items="payrollOptions"
                  item-text="name"
                  item-value="id"
                  label="Payroll"
                ></v-autocomplete>
              </v-flex>
              <v-flex md12 v-if="editedEmployment.branch === 1">
                <v-dialog
                  ref="timeFromDialog"
                  v-model="timeFromModal"
                  :return-value.sync="editedEmployment.time_from"
                  persistent
                  lazy
                  full-width
                  width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      v-model="editedEmployment.time_from"
                      :error-messages="timeFromErrors"
                      label="Time From"
                      prepend-icon="access_time"
                      readonly
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                    v-if="timeFromModal"
                    v-model="editedEmployment.time_from"
                    full-width
                  >
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="timeFromModal = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.timeFromDialog.save(editedEmployment.time_from)">OK</v-btn>
                  </v-time-picker>
                </v-dialog>
              </v-flex>
              <v-flex md12 v-if="editedEmployment.branch === 1">
                <v-dialog
                  ref="timeToDialog"
                  v-model="timeToModal"
                  :return-value.sync="editedEmployment.time_to"
                  persistent
                  lazy
                  full-width
                  width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      :disabled="!editedEmployment.time_from"
                      v-model="editedEmployment.time_to"
                      :error-messages="timeToErrors"
                      label="Time To"
                      prepend-icon="access_time"
                      readonly
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                    v-if="timeToModal"
                    v-model="editedEmployment.time_to"
                    full-width
                    :min="editedEmployment.time_from"
                  >
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="timeToModal = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.timeToDialog.save(editedEmployment.time_to)">OK</v-btn>
                  </v-time-picker>
                </v-dialog>
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

  import JsonExcel from 'vue-json-excel'

	export default {
    components: {
      JsonExcel
    },

    mixins: [validationMixin],

		data () {
			return {
        timeFromModal: false,
        timeToModal: false,
        payrollOptions: [
          { name: 'Cash', id: 0 },
          { name: 'ATM', id: 1 }
        ],
        expand: false,
        search: '',
        selected: [],
	      headers: [
          { text: '', align: 'left', value: 'expand', sortable: false },
          { text: 'SSS', align: 'left', value: 'sss' },
          { text: 'Name', align: 'left', value: 'user.full_name' },
          { text: 'Branch', align: 'left', value: 'branch.name' },
	        { text: 'Position', align: 'left', value: 'position.name' },
	        { text: 'Actions', align: 'center', value: 'action', sortable: false }
	      ],
	      editedEmployment: {
          sss: '',
          payroll: '',
          branch: '',
          position: '',
          department: '',
          time_from: '',
          time_to: '',
	      }
			}
		},

    validations: {
      editedEmployment: {
        sss:
        {
          required,
          isUnique(value) { // custom validation
            if (value === '') return true

            const isSss = this.employments.filter(employmen => employmen.sss == value).length
            if (isSss > 0) {
              return false
            } else {
              return true
            }
          }
        },
        payroll: { required },
        branch: { required },
        position: { required },
        department: { required },
      },
    },

    computed: {
      ...mapGetters({
        employments: 'employment/getEmployments',
        employmentErrors: 'employment/getEmploymentErrors',
        branches: 'branch/getBranches',
        positions: 'position/getPositions',
        departments: 'department/getDepartments',
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
        return 'Edit User Employment'
      },

      sssErrors () {
        const errors = []
        if (!this.$v.editedEmployment.sss.$dirty) return errors

        var sssRequiredError = null
        if (this.employmentErrors['sss|required']) {
          sssRequiredError = this.employmentErrors['sss|required']
        } else {
          sssRequiredError = 'The first name field is required.'
        }

        var sssUniqueError = null
        if (this.employmentErrors['sss|unique']) {
          sssUniqueError = this.employmentErrors['sss|unique']
        } else {
          sssUniqueError = 'The sss has already been taken.'
        } 
        
        !this.$v.editedEmployment.sss.required && errors.push(sssRequiredError)
        !this.$v.editedEmployment.sss.isUnique && errors.push(sssUniqueError)
        return errors
      },
      payrollErrors () {
        const errors = []
        if (!this.$v.editedEmployment.payroll.$dirty) return errors

        var payrollRequiredError = null
        if (this.employmentErrors['payroll|required']) {
          payrollRequiredError = this.employmentErrors['payroll|required']
        } else {
          payrollRequiredError = 'Please select payroll.'
        }
        
        !this.$v.editedEmployment.payroll.required && errors.push(payrollRequiredError)
        return errors
      },
      positionErrors () {
        const errors = []
        if (!this.$v.editedEmployment.position.$dirty) return errors

        var positionRequiredError = null
        if (this.employmentErrors['position|required']) {
          positionRequiredError = this.employmentErrors['position|required']
        } else {
          positionRequiredError = 'Please select position.'
        }
        
        !this.$v.editedEmployment.position.required && errors.push(positionRequiredError)
        return errors
      },
      departmentErrors () {
        const errors = []
        if (!this.$v.editedEmployment.department.$dirty) return errors

        var departmentRequiredError = null
        if (this.employmentErrors['department|required']) {
          departmentRequiredError = this.employmentErrors['department|required']
        } else {
          departmentRequiredError = 'Please select department.'
        }
        
        !this.$v.editedEmployment.department.required && errors.push(departmentRequiredError)
        return errors
      }
    },

    watch: {
      employments () {
        this.$v.$reset() // reset validation
      }
    },

    created () {
      this.$store.dispatch('employment/fetchEmployments')
      this.$store.dispatch('branch/fetchBranches')
      this.$store.dispatch('position/fetchPositions')
      this.$store.dispatch('department/fetchDepartments')
    },

    methods: {
      onResize() {
        if (window.innerWidth < 769)
          this.$store.dispatch('isMobile', true)
        else
          this.$store.dispatch('isMobile', false)
      },

      editEmployment (employment) {
        this.editedEmployment = Object.assign({}, {
                            id: employment.id,
                            user: employment.user.full_name,
                            sss: employment.sss,
                            payroll: employment.payroll,
                            branch: employment.branch.id,
                            position: employment.position ? employment.position.id : null,
                            department: employment.department ? employment.department.id : null,
                            time_from: employment.time_from,
                            time_to: employment.time_to,
                          })
        this.$store.dispatch('triggerDialog', true)
      },

      close () {
        this.$v.$reset()
        this.$store.dispatch('triggerDialog', false)
      },

      save () {
        this.$v.$touch()
        this.$store.dispatch('employment/updateEmployment', this.editedEmployment)
      }
    }
	}
</script>