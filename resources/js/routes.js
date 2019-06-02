import add_meal from './components/add_meal.vue'
import add_bazar from './components/add_bazar.vue'
import calculation from './components/calculation.vue'

export default[

{ path:'/add_meal/:id' , component: add_meal }  ,
{ path:'/add_meal' , component: add_meal }  ,
{ path:'/add_bazar' , component: add_bazar }  ,
{ path:'/calculation' , component: calculation }  ,


]