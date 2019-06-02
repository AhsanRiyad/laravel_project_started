<template>
  <v-app>
    
    <v-app>
      <toolbar/>  
        <v-content>
          <router-view></router-view>
        </v-content>

    </v-app>


  </v-app>
</template>

<script>

//import add_bazar from './components/add_bazar.vue'

export default {
  name: 'App',
  components : { 
        //'add_bazar': add_bazar

     },
  data () {
    return {
      //
    }
  }
}
</script>
