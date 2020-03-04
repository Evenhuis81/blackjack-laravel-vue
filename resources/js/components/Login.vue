<!-- verwijder loze commentaren -->
<template>
  <div class="content">
    <div class="title m-b-md">Blackjack</div>

    <!-- dit menu kan er uit -->
    <div class="links">
      <a href="https://laravel.com/docs">What?</a>
      <a href="https://laracasts.com">Why?</a>
      <a href="https://laravel-news.com">How?</a>
      <a href="https://blog.laravel.com">As a guest</a>
      <a href="https://nova.laravel.com">As registered</a>
      <a href="https://forge.laravel.com">Github</a>
    </div>

    <div class="play">
        <!-- kies duidelijke naam, bijv. showLoginForm, ipv activateModal -->
      <button @click="activateModal" class="button is-dark is-large">Play Game</button>
    </div>
    <div class="modal" :class="{ 'is-active': modalIsActive }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Enter Username</p>
          <!-- kies duidelijke naam, bijv. hideLoginForm, ipv deactivateModal -->
          <button @click="deactivateModal" class="delete" aria-label="close"></button>
        </header>
        <!-- gebruik @submit.prevent i.p.v. preventDefault -->
        <form @submit="checkForm">
          <section class="modal-card-body">
            <div class="control has-text-centered">
              <input
                class="has-text-centered"
                id="username"
                type="text"
                v-model="userName"
                name="username"
                placeholder="Enter Username"
              />
              <!-- <input type="hidden" name="_token" :value="csrf" /> -->
            </div>
            <!-- gebruik geen hard-coded array indexen want foutgevoelig en niet dynamisch -->
            <p v-if="errors.length" class="help is-danger">{{ errors[0] }}</p>
            <p v-else class="help">at least 3 characters</p>
          </section>
          <footer class="modal-card-foot">
            <!-- encode speciale karakters zoals ampersand: &amp; -->
            <button type="submit" value="submit" class="button is-success">Save & Play</button>
            <!-- <button @click="deactivateModal" class="button">Cancel</button> -->
          </footer>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      //   csrf: document
      //     .querySelector('meta[name="csrf-token"]')
      //     .getAttribute("content"),
      errors: [],
      userName: null,           // maak string velden altijd default '' i.p.v. null, want null.length geeft error (zie browser console)
      modalIsActive: false
    };
  },
  methods: {
    activateModal() {
      this.modalIsActive = true;
    },
    deactivateModal() {
      this.modalIsActive = false;
    },
    checkForm: function(e) {
      e.preventDefault();
      this.errors = [];
      if (this.userName.length > 2) {
        if (localStorage) {
          localStorage.setItem("username", this.userName);
          localStorage.setItem("userchips", 1000);
          // na 1 keer spelen werkt het spel niet meer omdat ik dan 0 punten heb (in localStorage). Er is geen optie om een game (punten) te resetten,
          // ik moet nu handmatig mijn localStorage legen voordat ik een nieuw spel kan spelen!
          // tip: verplaats puntentoekenning en logincredentials van Login naar App component en voeg een reset / logout knop toe aan het App component om een nieuw spel te kunnen starten
          this.$emit("setLocalData", "");   // kies duidelijke naam voor custom event, bijv. inputUserCredentials. Geef de username mee zodat het centrale App component deze kan setten in de localStorage, evenals de userchips. Ditzelfde component kan dan bij een reset / logout deze data unsetten. Zo houd je de data centraal in App.vue en is het Login.vue component alleen voor de inlogactie zelf.
          return;
        } else {
          alert(
            "No support. Use a fallback such as browser cookies or store on the server."
          );
          return;
        }
      }
      this.errors.push("Username has to be at least 3 characters.");
    }
  }
};
</script>

<style scoped>
html,
body {
  background-color: #fff;
  color: #636b6f;
  font-family: "Nunito", sans-serif;
  font-weight: 200;
  height: 100vh;
  margin: 0;
}

.play {
  margin-top: 100px;
}

.full-height {
  height: 100vh;
}

.flex-center {
  align-items: center;
  display: flex;
  justify-content: center;
}

.position-ref {
  position: relative;
}

.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.content {
  text-align: center;
}

.title {
  font-size: 84px;
}

.links > a {
  color: #636b6f;
  padding: 0 25px;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.1rem;
  text-decoration: none;
  text-transform: uppercase;
}

.m-b-md {
  margin-bottom: 30px;
}
</style>
