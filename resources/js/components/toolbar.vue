<template>
    <nav>

  <v-toolbar app>
    <v-toolbar-side-icon @click="drawer = !drawer"></v-toolbar-side-icon>
    <v-toolbar-title>Meal Manager</v-toolbar-title>
    <v-spacer></v-spacer>
    <v-toolbar-items class="hidden-sm-and-down">
      <v-btn color="green" flat> <v-icon color="green"> perm_identity </v-icon> Profile</v-btn>
      <v-btn flat color="red"> <v-icon color="red"> exit_to_app </v-icon> Sign Out</v-btn>
    </v-toolbar-items>
  </v-toolbar>
    
    
    <v-navigation-drawer  v-model="drawer" app  class="grey darken-1">
      
      
    <v-container>
      <v-layout row wrap justify-center>
        <v-flex xs10 >


          <v-list class="mt-3">
          <v-list-tile  v-for="link in links" :key ="link.text" router :to ="link.route">
            <v-list-title-action>
              <v-icon  class="white--text pr-4"> {{ link.icon }} </v-icon>
            </v-list-title-action>
            <v-list-tile-content>
              <v-list-tile-title class="white--text">{{ link.text }}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
        

        </v-flex>
      </v-layout>
    </v-container>

      

  



    </v-navigation-drawer>

</nav>
</template>



<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
          return {
            drawer: false,
            links: 
            [
              { text: 'Add Meal'  , icon: 'dashboard' , route: '/add_meal' } ,
              { text: 'Add_bazar'  , icon: 'dashboard' , route: '/add_bazar' } ,
              { text: 'Calculation'  , icon: 'dashboard' , route: '/calculation' } ,
            ]
            
          }
        }, 
        methods: {
          drawer_switch: function(){
            this.drawer = false;
          }
        }
    }
</script>



<style >
  
  .v-navigation-drawer>.v-list:not(.v-list--dense) .v-list__tile {
    text-decoration: none;
  }

</style>
