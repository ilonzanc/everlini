<template>
  	<div id="home">
			<div class="container">
				<h1>Ontdek evenementen bij jou in de buurt die matchen met jouw interesses</h1>
				<form method="POST" action="" @submit.prevent="onSubmit">
					<label for="location">Locatie</label>
					<input type="text" id="location" name="location" placeholder="Waar zullen we zoeken?" v-model="params.location">
					<label>Binnen 13 km</label>
					<label>Datum</label>
					<div class="row">
						<div class="column column-sm-6"><label>Van</label>
					<input type="text" id="startdate" name="startdate" placeholder="Start datum..." v-model="params.startdate"></div>
						<div class="column column-sm-6"><label>Tot</label>
					<input type="text" id="enddate" name="enddate" placeholder="Eind datum..." v-model="params.enddate"></div>
					</div>


					<label>Interesses</label>
					<input type="text" id="interests" name="interests" placeholder="Eigen interesse..." v-model="params.interests">
					<button type="submit" class="btn primary-btn">Zoeken</button>
			</form>
			</div>
  </div>
</template>

<script>
	import axios from "axios";
	export default {
		computed: {
			searchparams() {
				return this.$store.getters.getSearch;
			}
		},
		name: "home",
		data() {
			return {
				params: {
					location: "",
					startdate: "",
					enddate: "",
					interests: [],
				}
			};
		},
		mounted() {
			this.params = this.searchparams;
		},
		methods: {
			onSubmit() {
				this.$store.commit('updateSearch', this.params);
				this.$router.push('/evenementen');
			}
		}
	};
</script>

<style lang="scss">

</style>