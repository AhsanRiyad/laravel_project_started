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

                    <v-text-field value='0' v-model="ataurMeal" label="Ataur" :rules="mealRule"></v-text-field>
                    
                    </v-flex>
                    
                    <v-flex xs5>

                    <v-text-field value="regular" v-model="ataurComment" label="Comment" :rules="otherRules"></v-text-field>
                
                    </v-flex>
                    </v-layout>
                

                    <v-layout row wrap justify-space-around>

                    <v-flex xs5>

                    <v-text-field value='0' v-model="riyadMeal" label="Riyad" :rules="mealRule"></v-text-field>
                    
                    </v-flex>
                    
                    <v-flex xs5>

                    <v-text-field value="regular" v-model="riyadComment" label="Comment" :rules="otherRules"></v-text-field>
                
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

                        <br>
                                         <h1 v-bind:class="{ text_color: status_color , text_color_red : status_color_red  }"  >  {{ bazar_adding_status }} </h1>
                    </v-flex>


                    </v-layout>

                </v-form>

            

            </v-card-text>


        </v-card>

        </v-flex>
    </v-layout>
    </v-container>



</template>


<style scoped>
    .text_color{
        color: green ;
    }

    .text_color_red{
        color: red ;
    }
</style>



<script>

import format from 'date-fns/format'
import VueResource from 'vue-resource'


    export default {
        mounted() {
            console.log('add meal Component mounted.')
        },
        
        data(){
            return {
                correctDate : new Date() ,
                due_date: null,
                disability : false,
                ff : 'disabled',
                riyadMeal: '0' , 
                ataurMeal : '0' , 
                riyadComment: 'regular',
                ataurComment: 'regular',
                mealRule: [
                 v => v && v.length >= 0 || 'minimum length not full filled',
                 v => /^[\d]*(\.){0,1}(\d)*$/.test(v) || 'must be integer or decimal point value' , 

                ],
                otherRules: [
                 v => v && v.length > 3 || 'minimum length not full filled' ,
                ],
                loading_status : false,
                bazar_adding_status : null ,
                status_color : true , 
                status_color_red : false , 
                months : ["January","February","March","April","May","June","July","August","September","October","November","December"],

            }
        },
        computed: {
            formatedDate () {
                if(this.due_date!=null){
                    //this.disability = true;
                }
                //var d = new Date();
                //var date =  this.due_date ? format(this.due_date , 'Do-MMM-YY (dddd)') : format(d , 'Do-MMM-YY (dddd)')  ;
                //alert(date);


                if(this.due_date == null)
                {
                    var d = new Date();
                    var date = format(d , 'Do-MMM-YY (dddd)');
                    //var date = moment().format(d , 'MMMM Do YYYY');

                    //var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];

                    var dd = this.months[d.getMonth()] + ' ' + + d.getDate()+ ' , ' + d.getFullYear() ;

                    //this.due_date = new Date(dd);

                    //alert(moment(d).format('YYYY'));
;                   return date;
                }else{
                    var date = format(this.due_date , 'Do-MMM-YY (dddd)');

                    //this.due_date = new Date( format(this.due_date , 'Do-MMM-YY') ); 

                    //var dd = this.months[date.getMonth()] + ' ' + + date.getDate()+ ' , ' + date.getFullYear()    ;

                    //this.due_date = new Date(dd);
                    //alert(dd);
                    return date;
                }

                //this.due_date = new Date();
                alert(date);

                return date.toString();
            },
            
        },
        methods: {
            next_date () {

                if(this.due_date != null){

                    var d = new Date(this.due_date);
                    this.due_date = d.setDate(d.getDate()+1);
                    this.due_date = new Date(this.due_date);
                    //alert(this.due_date);
                }else{
                    var d = new Date();
                    this.due_date = d.setDate(d.getDate()+1);
                    this.due_date = new Date(this.due_date);



                }
            },
            prev_date () {
                if(this.due_date != null){
                    var d = new Date(this.due_date);
                    this.due_date = d.setDate(d.getDate()-1);
                    this.correctDate = this.formatedDate;
                    this.due_date = new Date(this.due_date);
                    //this.due_date = this.formatedDate;
                    //alert(this.correctDate);
                }else{
                    var d = new Date();
                    this.due_date = d.setDate(d.getDate()+1);
                    this.due_date = new Date(this.due_date);

                }
            },
            submit() {
                if(this.$refs.form.validate()){
                    console.log('form validated');
                    this.loading_status = true;


                    if(this.due_date == null ){
                        var d = new Date();
                        var  m = d.getMonth() ;
                        ++m ;
                        this.n_date = d.getFullYear()  + '-' +  m + '-' +   d.getDate();
                    }else{
                        //this.n_date = this.due_date ; 

                        var d = new Date(this.due_date);
                        var  m = d.getMonth() ;
                        ++m ;
                        this.n_date = d.getFullYear()  + '-' +  m + '-' +   d.getDate();
                    }


                    this.$http.post('http://localhost:3000/addMeal' , 
                    {
                        date : this.n_date,
                        riyadMeal : this.riyadMeal ,
                        riyadComment : this.riyadComment , 
                        ataurMeal : this.ataurMeal ,
                        ataurComment : this.ataurComment , 
                                  
                    }

                        ).then(function(data){
                            //alert('inside');
                            console.log(data);
                            this.loading_status = false ;
                            this.bazar_adding_status = 'bazar added' ; 
                            this.status_color = true;
                            this.status_color_red = false ; 
                        }).catch(function(error){
                            this.loading_status = false ; 
                            //alert('an error occured');
                            this.bazar_adding_status = 'bazar not added' ;
                            this.status_color = false;
                            this.status_color_red = true ; 
                        });


                    
                }
            },
        }
    }
</script>
