<template>
  <v-toolbar >
    <v-toolbar-side-icon></v-toolbar-side-icon>
    <v-toolbar-title>Meal Manager</v-toolbar-title>
    <v-spacer></v-spacer>
    <v-toolbar-items class="hidden-sm-and-down">
      <v-btn color="green" flat> <v-icon color="green"> perm_identity </v-icon> Profile</v-btn>
      <v-btn flat color="red"> <v-icon color="red"> arrow_back </v-icon> Sign Out</v-btn>
    </v-toolbar-items>
  </v-toolbar>
</template>



<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

