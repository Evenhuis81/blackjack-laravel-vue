<template>
  <div class="content">
    <div class="title m-b-md">Blackjack</div>

    <div class="links">
      <a href="https://laravel.com/docs">What</a>
      <a href="https://laracasts.com">Why</a>
      <a href="https://laravel-news.com">How</a>
      <a href="https://blog.laravel.com">As a guest</a>
      <a href="https://nova.laravel.com">As registered</a>
      <a href="https://forge.laravel.com">Github</a>
    </div>

    <div class="play">
      <button @click="activateModal" class="button is-dark is-large">Play Game</button>
    </div>
    <div class="modal" :class="{ 'is-active': modalIsActive }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Enter Nickname</p>
          <button @click="deactivateModal" class="delete" aria-label="close"></button>
        </header>
        <form @submit="checkForm">
          <section class="modal-card-body">
            <div class="control has-text-centered">
              <input
                class="has-text-centered"
                id="nickname"
                type="text"
                v-model="nickname"
                name="nickname"
                placeholder="Enter Nickname"
              />
              <!-- <input type="hidden" name="_token" :value="csrf" /> -->
            </div>
            <p v-if="errors.length" class="help is-danger">{{ errors[0] }}</p>
            <p v-else class="help">at least 3 characters</p>
          </section>
          <footer class="modal-card-foot">
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
      nickname: null,
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
      if (this.nickname.length > 2) {
        if (localStorage) {
          localStorage.setItem("nickname", this.nickname);
          localStorage.setItem("playerchips", 1000);
          localStorage.setItem("computerchips", 1000);
          this.$emit("loginguest", this.nickname);
          //   alert("set");
          return;
        } else {
          alert(
            "No support. Use a fallback such as browser cookies or store on the server."
          );
          return;
        }
      }
      this.errors.push("Nickname has to be at least 3 characters.");
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
