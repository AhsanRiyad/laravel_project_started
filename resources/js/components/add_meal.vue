<template>

    <v-container>
        <v-layout row wrap>

        <v-flex xs12>
        <v-card>
            <v-card-title>
                <h2>Add Meal</h2>    
            </v-card-title>
            <v-card-text>

        
                <v-form ref="form">
                    
                    <v-layout row wrap justify-space-around>

                    <v-flex xs5>

                    <v-text-field value='0' label="Ataur" :rules="mealRule"></v-text-field>
                    
                    </v-flex>
                    
                    <v-flex xs5>

                    <v-text-field value="regular" label="Comment" :rules="otherRules"></v-text-field>
                
                    </v-flex>
                    </v-layout>
                

                    <v-layout row wrap justify-space-around>

                    <v-flex xs5>

                    <v-text-field value="0" label="Riyad" :rules="mealRule"></v-text-field>
                    
                    </v-flex>
                    
                    <v-flex xs5>

                    <v-text-field value="regular" label="Comment" :rules="otherRules"></v-text-field>
                
                    </v-flex>
                    </v-layout>
                    

                    <v-layout row wrap justify-space-around>

                    <v-flex xs5 >
                        <v-menu>
                            <v-text-field  :value="formatedDate" slot="activator" label="Date" prepend-icon="date_range" :rules="otherRules">

                            </v-text-field>

                            <v-date-picker v-model="due_date"></v-date-picker>
                        </v-menu>

                    </v-flex>

                    <v-flex xs2 >
                        <v-btn :disabled="disability" @click="next_date">
                            next day
                        </v-btn>

                    </v-flex>

                    <v-flex xs2 >
                        <v-btn :disabled="disability"  @click="prev_date">
                            prev day
                        </v-btn>

                    </v-flex>




                    </v-layout>

                    <v-layout justify-center>
                        
                    <v-flex xs2>
                        <v-btn @click="submit" color="green" class="white--text" :loading="loading_status">Add Meal</v-btn>
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
                due_date: null,
                disability : false,
                ff : 'disabled',
                riyadMeal: 0 , 
                ataurMeal : 0 , 
                riyadComment: 'regular',
                ataurComment: 'regular',
                mealRule: [
                 v => v.length > 0 || 'minimum length not full filled',
                 v => /^[\d]*(\.){0,1}(\d)*$/.test(v) || 'must be integer or decimal point value' , 

                ],
                otherRules: [
                 v => v.length > 3 || 'minimum length not full filled' ,
                ],
                loading_status : false,

            }
        },
        computed: {
            formatedDate () {
                if(this.due_date!=null){
                    //this.disability = true;
                }
                var d = new Date();
                return this.due_date ? format(this.due_date , 'Do-MMM-YY (dddd)') : format(d , 'Do-MMM-YY (dddd)')  ;
            },
            
        },
        methods: {
            next_date () {
                var d = new Date(this.due_date);
                this.due_date = d.setDate(d.getDate()+1);
            },
            prev_date () {
                var d = new Date(this.due_date);
                this.due_date = d.setDate(d.getDate()-1);
            },
            submit() {
                if(this.$refs.form.validate()){
                    console.log('form validated');
                    this.loading_status = true;

                    
                }
            },
        }
    }
</script>
