<template>

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

								<v-flex xs5 offset-xs1 >
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

								<v-flex xs3 offset-xs1>
								<p>
									Name
								</p>
									<v-overflow-btn
									:items="bazar_name_list"
									label="Bazar name"
									editable
									v-model="bazar_name_value"
									:item-value="bazar_name_value"
									:input_value="bazar_name_value"
									></v-overflow-btn>

								</v-flex>

								<v-flex xs3  offset-xs1>
									<p>
										Taka
									</p>
									<v-overflow-btn
									:items="bazar_taka_list"
									label="Bazar price"
									editable
									:item-value="bazar_taka_value"
									></v-overflow-btn>

								</v-flex>

								<v-flex xs2 >
									<v-btn class="mt-5" :disabled="disability" @click="delete_item(b.id)">
										Delete
									</v-btn>

								</v-flex>

								<v-flex xs2 >
									<v-btn class="mt-5" :disabled="disability" @click="add_item">
										Add
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
				bazar_name_value : 'null',
				id : 0 ,
				due_date: new Date(),
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

				bazar_details : [ 
				{ id: 0 , bazar_name: null  , bazar_price : null } ,

				], 
				bazar_name_list : [
				'oil' , 'chal' 
				]

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
        	delete_item (id){
            	//alert(this.bazar_details.length+ ' ' + id);

            	var index = this.bazar_details.map(function(e) { return e.id; }).indexOf(id);
            	if(this.bazar_details.length > 1){
            		this.bazar_details.splice( index , 1) ;
            	}
            	
            },
            add_item (){
            	this.bazar_details.push({id: ++this.id , bazar_name: null  , bazar_price : null});
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
