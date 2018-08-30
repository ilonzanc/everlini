<template>
  <div id="about" class="content">
    <div class="container">
      <h1>About</h1>
      <p>Everlini is een webapplicatie waarmee gebruikers op een snelle manier nieuwe
      evenementen kunnen ontdekken die beantwoorden aan hun interesses. Facebook is bij iedereen
      bekend en biedt een hele resem aan functionaliteiten aan om evenementen te zoeken en te
      verkennen. Helaas voldoen deze niet altijd aan de interesses van de gebruiker en worden enkel
      evenementen op de eerste pagina gepromoot die populair zijn bij de vrienden van de gebruiker.
      Met deze app willen we een op maat gemaakt aanbod aan evenementen voorschotelen zodat de
      gebruiker onmiddellijk iets vindt dat past bij zijn voorkeuren.</p>
      <p>Daarnaast hebben organisaties ook de mogelijkheid om hun evenementen aan de applicatie toe
      te voegen en op die manier te promoten om zo een breder publiek aan te spreken.</p>
    </div>
  </div>
</template>

<script>
  export default {
    name: "about",
    computed: {
    },
    data() {
      return {
      }
    },
    mounted() {
      console.log("About Vue Component mounted");
    },
    methods: {
    }
  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
#about {
  p {
    margin-bottom: 1rem;
  }
}
</style>