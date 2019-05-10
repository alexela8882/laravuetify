<template>
  <div>
    <v-container grid-list-md>
      <v-layout row wrap>
        <v-flex xs12>
          <h3>Home</h3>
          <h2>This is the best home ever!</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae ducimus totam atque nemo repellat, necessitatibus ab placeat veritatis iste inventore consequuntur ratione quo aliquid fuga aperiam odit possimus temporibus sed.</p>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
  export default {
    mounted() {
      console.log('Home mounted.')
    }
  }
</script>