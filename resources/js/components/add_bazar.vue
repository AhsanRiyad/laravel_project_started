<template>


	<div>
		<v-container>
			<v-layout row wrap>

				<v-flex xs12>
					<v-card>
						<v-card-title>
							<h2>Add Bazar</h2>    
						</v-card-title>
						<v-card-text>


							<v-form ref="form">




								<v-layout row wrap  >

									<v-flex xs4 class="ml-5" >
										<v-menu>
											<v-text-field  :value="formatedDate" slot="activator" label="Date" prepend-icon="date_range" :rules="otherRules">

											</v-text-field>

											<v-date-picker v-model="due_date"></v-date-picker>
										</v-menu>

									</v-flex>

									<v-flex xs2 offset-xs2>
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



								<v-layout row wrap v-for="b in bazar_details" :key="b.id" >

									<v-flex xs3 class="ml-5 mt-4" >
										<v-combobox
										:items="bazar_name_list"
										label="Bazar name"
										editable
										v-model="b.bazar_name_value"
										
										:input_value="b.bazar_name_value"
										:rules="otherRules" ></v-combobox>

									</v-flex>

									<v-flex xs2 class="ml-4 mt-4">

										<v-combobox
										:items="bazar_taka_list"
										label="Bazar price"
										editable
										:item-value="b.bazar_price"

										:rules="mealRule"

										></v-combobox>

									</v-flex>

									<v-flex xs2 class="ml-4">

										<v-radio-group v-model="b.row" row >
											<v-radio label="Bazar"  value="Bazar"></v-radio>
											<v-radio label="Constant" value="Constant"></v-radio>
											<v-radio label="Loan" value="Loan"></v-radio>
										</v-radio-group>



									</v-flex>



									<v-flex xs2 class="mt-4" >
										<v-btn  :disabled="disability" @click="delete_item(b.id)">
											Delete
										</v-btn>

									</v-flex>

									<v-flex xs2 class="mt-4">
										<v-btn  :disabled="disability" @click="add_item">
											Add
										</v-btn>

									</v-flex>



								</v-layout>










								<v-layout justify-center>

									<v-flex xs2>
										<v-btn  @click.prevent="submit" color="green" class="white--text" :loading="loading_status">Add Meal</v-btn>
									</v-flex>


								</v-layout>

							</v-form>



						</v-card-text>


					</v-card>

				</v-flex>
			</v-layout>
		</v-container>






		






	</div>


</template>


<script>

	import format from 'date-fns/format'
	import VueResource from 'vue-resource'
	import moment from 'moment'

	export default {
		mounted() {
			console.log('add meal Component mounted.')
		},
		data(){
			return {
				column: null,
				row: 'Bazar',
				bazar_name_value : 'null',
				id : 0 ,
				due_date: null,
				disability : false,
				ff : 'disabled',
				riyadMeal: 0 , 
				ataurMeal : 0 , 
				riyadComment: 'regular',
				ataurComment: 'regular',
				mealRule: [
				v => v && v.length > 0 || 'minimum 199 length not full filled',
				v => /^[\d]*(\.){0,1}(\d)*$/.test(v) || 'must be integer or decimal point value' , 

				],
				otherRules: [
				v => v && v.length >= 0 || 'minimum length not full filled' ,
				],
				loading_status : false,

				bazar_details : [ 
				{ id: 0 , bazar_name: 'chal'  , bazar_price : null , row : 'Bazar' , } ,

				], 
				bazar_name_list : [
				'oil' , 'chal' 
				],
				bazar_taka_list : [
				'10' , '20' 
				],
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
;                	return moment(d).format('MMMM Do YYYY');
                }else{
                	//var date = format(this.due_date , 'Do-MMM-YY (dddd)');
                	var date = moment(this.due_date).format('MMMM Do YYYY');;

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
        			//this.due_date = d.setDate(d.getDate()+1);
        			this.due_date = moment(d).add(1, 'days');
        		}else{
        			var d = new Date();
        			this.due_date = moment(d).add(1, 'days');
        		}
        	},
        	prev_date () {
        		if(this.due_date != null){
        			var d = new Date(this.due_date);
        			this.due_date = moment(d).subtract(1 , 'days');
        		}else{
        			var d = new Date();
        			this.due_date = moment(d).subtract(1 , 'days');
        		}
        	},
        	delete_item (id){
            	//alert(this.bazar_details.length+ ' ' + id);

            	var index = this.bazar_details.map(function(e) { return e.id; }).indexOf(id);
            	if(this.bazar_details.length > 1){
            		this.bazar_details.splice( index , 1) ;
            	}
            	
            },
            add_item (){
            	this.bazar_details.push({id: ++this.id , bazar_name: null  , bazar_price : null , row : 'Bazar'});
            },
            submit() {

            	if(this.$refs.form.validate()){
            		console.log('form validated');
            		this.loading_status = true;


            		alert('clicked');



            		this.$http.post('http://localhost:3000/ff' , 
            		{
            			date : this.due_date,
            			ff: 'ghhjgghgj',
            			bazar_details : this.bazar_details
            			
            		}
            			).then(function(data){
            				alert('inside');
            				console.log(data);
            			});

            		}
            	},
            }
        }
    </script>
