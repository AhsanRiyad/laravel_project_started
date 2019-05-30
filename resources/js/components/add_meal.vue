<template>
	
<h1>
	addmeal

	{{ id }}
</h1>

</template>


<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
        	return {
        		id: this.$route.params.id
        	} 
        }
    }
</script>
