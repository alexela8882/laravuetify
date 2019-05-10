<template>
  <div>
    <v-container grid-list-md text-xs-center>
      <v-layout v-resize="onResize" row wrap>
        <v-flex xs12>
          <v-toolbar flat color="white">
            <v-toolbar-title>Permissions</v-toolbar-title>
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
                  @click="deletePerms"
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
              :items="perms"
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
                        @click="editPerm(props.item)">
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
                  v-model="editedPerm.name"
                  :error-messages="nameErrors"
                  label="Name"
                  required
                  :disabled="loadingStatus"
                  @input="$v.editedPerm.name.$touch()"
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
  import { required } from 'vuelidate/lib/validators'

  export default {
    mixins: [validationMixin],

    data () {
      return {
        search: '',
        selected: [],
        headers: [
          { text: 'Select', align: 'left', value: 'select-all', sortable: false },
          { text: 'Name', align: 'left', value: 'name' },
          { text: 'Actions', align: 'center', value: 'name', sortable: false }
        ],
        editedIndex: -1,
        editedPerm: {
          name: '',
        },
        defaultPerm: {
          name: '',
        },
      }
    },

    validations: {
      editedPerm: {
        name:
        {
          required,
          isUnique(value) { // custom validation
            if (value === '') return true

            const isName = this.perms.filter(perm => {return perm.name == value}).length
            if (isName === 1) {
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
        perms: 'perm/getPerms',
        permErrors: 'perm/getPermErrors',
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
        return this.editedIndex === -1 ? 'New Permission' : 'Edit Permission'
      },

      nameErrors () {
        const errors = []
        if (!this.$v.editedPerm.name.$dirty) return errors

        var nameRequiredError = null
        if (this.permErrors['name|required']) {
          nameRequiredError = this.permErrors['name|required']
        } else {
          nameRequiredError = 'The name field is required.'
        }

        var nameUniqueError = null
        if (this.permErrors['name|unique']) {
          nameUniqueError = this.permErrors['name|unique']
        } else {
          nameUniqueError = 'The name already taken.'
        }
        
        !this.$v.editedPerm.name.required && errors.push(nameRequiredError)
        !this.$v.editedPerm.name.isUnique && errors.push(nameUniqueError)
        return errors
      },
    },

    watch: {
      perms () {
        this.$v.$reset() // reset validation
      }
    },

    created () {
      this.$store.dispatch('perm/fetchPerms')
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
        this.editedPerm = Object.assign({}, this.defaultPerm) // reset to default
        this.$store.dispatch('triggerDialog', true)
      },

      editPerm (perm) {
        this.editedIndex = this.perms.indexOf(perm) // get index: important
        this.editedPerm = Object.assign({}, perm)
        this.$store.dispatch('triggerDialog', true)
      },

      deletePerms () {
        const perms = this.$data.selected
        const permIds = perms.map(perm => {
          return perm.id
        })
        const permNames = perms.map(perm => {
          return perm.name
        })

        this.$vuetifyConfirmDialog
          .open("Confirm Delete", "Are you sure to delete permission " + permNames + "?", "Cancel", "Confirm Delete")
          .then(state => {
            if (state) this.$store.dispatch('perm/deletePerms', permIds)
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
          // update perm
          this.$store.dispatch('perm/updatePerm', this.editedPerm)
        } else {
          // store perm
          this.$store.dispatch('perm/storePerm', this.editedPerm)
        }
      }
    }
  }
</script>