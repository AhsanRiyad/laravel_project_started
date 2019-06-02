<template>

    <v-container>
        <v-layout row wrap>

        <v-flex xs12>
        <v-card>
            <v-card-title>
                <h2>Add Meal</h2>    
            </v-card-title>
            <v-card-text>

        
                <v-form>
                    
                    <v-layout row wrap justify-space-around>

                    <v-flex xs5>

                    <v-text-field label="Ataur"></v-text-field>
                    
                    </v-flex>
                    
                    <v-flex xs5>

                    <v-text-field label="Comment"></v-text-field>
                
                    </v-flex>
                    </v-layout>
                

                    <v-layout row wrap justify-space-around>

                    <v-flex xs5>

                    <v-text-field label="Riyad"></v-text-field>
                    
                    </v-flex>
                    
                    <v-flex xs5>

                    <v-text-field label="Comment"></v-text-field>
                
                    </v-flex>
                    </v-layout>
                    

                    <v-layout row wrap justify-space-around>

                    <v-flex xs5 >
                        <v-menu>
                            <v-text-field :value="formatedDate" slot="activator" label="date" prepend-icon="date_range">

                            </v-text-field>

                            <v-date-picker v-model="due_date"></v-date-picker>
                        </v-menu>

                    </v-flex>

                    <v-flex xs2 >
                        <v-btn>
                            next day
                        </v-btn>

                    </v-flex>

                    <v-flex xs2 >
                        <v-btn>
                            prev day
                        </v-btn>

                    </v-flex>


                    </v-layout>

                </v-form>

            

            </v-card-text>


        </v-card>

        </v-flex>
    </v-layout>
    </v-container>



</template>


<script>

import format from 'date-fns/format'

    export default {
        mounted() {
            console.log('add meal Component mounted.')
        },
        data(){
            return {
                due_date: null
            }
        },
        computed: {
            formatedDate () {
                return this.due_date ? format(this.due_date , 'Do-MMM-YYYY (dddd)') : '' ;
            }
        }
    }
</script>
