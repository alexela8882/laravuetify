<template>
  <v-app>
    <!-- Navbar -->
    <Navbar v-if="isLoggedIn" />

    <!-- Toolbar -->
    <Toolbar v-if="isLoggedIn" />

    <!-- Content -->
    <v-content>
      <router-view></router-view>
    </v-content>

    <!-- Footer -->
    <Footer v-if="isLoggedIn" />

    <!-- Snackbar -->
    <Snackbar />
  </v-app>
</template>

<script>

  import Navbar from './Navbar';
  import Toolbar from './Toolbar';
  import Footer from './Footer';
  import Snackbar from './Snackbar';

  export default {
    components: {
      Navbar,
      Toolbar,
      Footer,
      Snackbar
    },

    created () {
      window.addEventListener('resize', this.handleResize)
      this.handleResize()

      const windowWidth = this.$store.state.windowSize.width
      if (windowWidth < 769) {
        this.$store.commit('DRAWER_STATUS', false)
      }
    },

    destroyed () {
      window.removeEventListener('resize', this.handleResize)
    },

    methods: {
      handleResize() {
        this.$store.commit('WINDOW_SIZE')
      }
    },

    computed: {
      isLoggedIn () {
        return this.$store.getters.isLoggedIn
      }
    }
  }
</script>