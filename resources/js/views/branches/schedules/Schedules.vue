<template>
  <div>
    <v-container grid-list-md text-xs-center>
      <v-layout v-resize="onResize" row wrap>
        <v-flex xs12>
          <v-toolbar flat color="white">
            <v-toolbar-title>Branch Schedules</v-toolbar-title>
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
                  @click="deleteSchedules"
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
              :items="schedules"
              :search="search"
              :loading="loadingStatus"
              disable-initial-sort
              class="elevation-1">
              <template v-slot:items="props">
                <td>
                  <v-checkbox
                    :disabled="props.item.branches.length > 0"
                    v-model="props.selected"
                    color="primary"
                    hide-details
                  ></v-checkbox>
                </td>
                <td class="text-md-left">{{ props.item.time }}</td>
                <td class="justify-center">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-icon
                        small
                        class="mr-2"
                        v-on="on"
                        @click="editSchedule(props.item)">
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
                <v-dialog
                  ref="timeFromDialog"
                  v-model="timeFromModal"
                  :return-value.sync="editedSchedule.time_from"
                  persistent
                  lazy
                  full-width
                  width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      v-model="editedSchedule.time_from"
                      :error-messages="timeFromErrors"
                      label="Time From"
                      prepend-icon="access_time"
                      readonly
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                    v-if="timeFromModal"
                    v-model="editedSchedule.time_from"
                    full-width
                  >
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="timeFromModal = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.timeFromDialog.save(editedSchedule.time_from)">OK</v-btn>
                  </v-time-picker>
                </v-dialog>
              </v-flex>
              <v-flex md12>
                <v-dialog
                  ref="timeToDialog"
                  v-model="timeToModal"
                  :return-value.sync="editedSchedule.time_to"
                  persistent
                  lazy
                  full-width
                  width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      :disabled="!editedSchedule.time_from"
                      v-model="editedSchedule.time_to"
                      :error-messages="timeToErrors"
                      label="Time To"
                      prepend-icon="access_time"
                      readonly
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                    v-if="timeToModal"
                    v-model="editedSchedule.time_to"
                    full-width
                    :min="editedSchedule.time_from"
                  >
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="timeToModal = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.timeToDialog.save(editedSchedule.time_to)">OK</v-btn>
                  </v-time-picker>
                </v-dialog>
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
        timeFromModal: false,
        timeToModal: false,
        search: '',
        selected: [],
        headers: [
          { text: 'Select', align: 'left', value: 'select-all', sortable: false },
          { text: 'Time', align: 'left', value: 'time' },
          { text: 'Actions', align: 'center', value: 'action', sortable: false }
        ],
        editedIndex: -1,
        editedSchedule: {
          time_from: '',
          time_to: '',
        },
        defaultSchedule: {
          time_from: '',
          time_to: '',
        },
      }
    },

    validations: {
      editedSchedule: {
        time_from: { required },
        time_to: { required },
      },
    },

    computed: {
      ...mapGetters({
        schedules: 'bsched/getSchedules',
        scheduleErrors: 'bsched/getScheduleErrors',
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
        return this.editedIndex === -1 ? 'New Schedule' : 'Edit Schedule'
      },

      timeFromErrors () {
        const errors = []
        if (!this.$v.editedSchedule.time_from.$dirty) return errors

        var timeFromRequiredError = null
        if (this.scheduleErrors['time_from|required']) {
          timeFromRequiredError = this.scheduleErrors['time_from|required']
        } else {
          timeFromRequiredError = 'The time from field is required.'
        }
        
        !this.$v.editedSchedule.time_from.required && errors.push(timeFromRequiredError)
        return errors
      },

      timeToErrors () {
        const errors = []
        if (!this.$v.editedSchedule.time_to.$dirty) return errors

        var timeToRequiredError = null
        if (this.scheduleErrors['time_to|required']) {
          timeToRequiredError = this.scheduleErrors['time_to|required']
        } else {
          timeToRequiredError = 'The time to field is required.'
        }
        
        !this.$v.editedSchedule.time_to.required && errors.push(timeToRequiredError)
        return errors
      },
    },

    watch: {
      schedules () {
        this.$v.$reset() // reset validation
      }
    },

    created () {
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
        this.editedSchedule = Object.assign({}, this.defaultSchedule) // reset to default
        this.$store.dispatch('triggerDialog', true)
      },

      editSchedule (schedule) {
        this.editedIndex = this.schedules.indexOf(schedule) // get index: important
        this.editedSchedule = Object.assign({}, schedule)
        this.$store.dispatch('triggerDialog', true)
      },

      deleteSchedules () {
        const schedules = this.$data.selected
        const scheduleIds = schedules.map(schedule => {
          return schedule.id
        })
        const scheduleTimes = schedules.map(schedule => {
          const time = schedule.time_from + ' ' + schedule.time_to
          return time
        })

        this.$vuetifyConfirmDialog
          .open("Confirm Delete", "Are you sure to delete schedule " + scheduleTimes + "?", "Cancel", "Confirm Delete")
          .then(state => {
            if (state) this.$store.dispatch('bsched/deleteSchedules', scheduleIds)
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
          // update schedule
          this.$store.dispatch('bsched/updateSchedule', this.editedSchedule)
        } else {
          // store schedule
          this.$store.dispatch('bsched/storeSchedule', this.editedSchedule)
        }
      }
    }
  }
</script>