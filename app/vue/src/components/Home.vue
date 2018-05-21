<template>
  	<div id="home">
			<div class="container">
				<h1>Ontdek evenementen bij jou in de buurt die matchen met jouw interesses</h1>
				<form method="POST" action="" @submit.prevent="onSubmit">
					<label for="location">Locatie</label>
					<input type="text" id="location" name="location" placeholder="Waar zullen we zoeken?" v-model="location">
					<label>Binnen 13 km</label>
					<label>Datum</label>
					<label>Interesses</label>
					<button type="submit" class="btn primary-btn">Zoeken</button>
			</form>
			</div>
  </div>
</template>

<script>
	import axios from "axios";
	export default {
		name: "home",
		data() {
			return {
				location: ""
			};
		},
		mounted() {
		},
		methods: {
			onSubmit() {
				this.$router.push('/evenementen');
			}
		}
	};
</script>

<style lang="scss">

</style>