<template>
  <div id="contact" class="content">
    <div class="container">
      <h1>Contact</h1>
      <div class="row">
        <div class="column column-lg-6">
          <form>
            <label for="name">Naam</label>
            <input type="text" name="name" placeholder="Jouw naam...">
            <label for="email" class="required">Emailadres</label>
            <input type="email" name="email" placeholder="Jouw emailadres...">
            <label for="subject" class="required">Onderwerp</label>
            <input type="text" name="subject" placeholder="Onderwerp...">
            <label for="message" class="required">Jouw bericht</label>
            <textarea name="message" placeholder="Hey Everlini, ik zou het graag even hebben over..."></textarea>
            <button class="btn primary-btn">Verzenden</button>
          </form>
        </div>
        <div class="column column-lg-6">
          <section class="contact-details">
          <div class="row">
            <div class="column column-lg-12">
              <i class="fa fa-envelope"></i>
              <p>admin@everlini.be</p>
            </div>
            <div class="column column-lg-12">
              <i class="fa fa-phone"></i>
              <p>+324 98 05 50 32</p>
            </div>
            <div class="column column-lg-12">
              <i class="fa fa-map-marker"></i>
              <p>Industrieweg 232<br>9030 Mariakerke</p>
            </div>
          </div>
          </section>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
  export default {
    name: "contact",
    computed: {
    },
    data() {
      return {
      }
    },
    mounted() {
      console.log("Contact Vue Component mounted");
    },
    methods: {
    }
  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
.contact-details {
  .column{
    text-align: center;
    clear: both;
    margin: auto;
    i {
      font-size: 1.5rem;
      margin: 30px 0 20px;
      color:#FECA57;
      border: 2px solid rgba(#FECA57, 0.5);
      border-radius: 50%;
      width: 50px;
      height: 50px;
      padding: 10px;
    }
    p {
      margin-bottom: 20px;
    }
  }
}
</style>