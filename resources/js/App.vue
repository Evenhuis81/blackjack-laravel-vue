<template>
  <div>
    <login v-show="!nickname" @loginguest="onLoginGuest"></login>
    <playfield v-show="nickname" :localData="[nickname, computerchips, playerchips]"></playfield>
  </div>
</template>

<script>
import Login from "./components/Login.vue";
import Playfield from "./components/Playfield.vue";

export default {
  data: function() {
    return {
      nickname: false,
      computerchips: false,
      playerchips: false
    };
  },
  components: {
    Login,
    Playfield
  },
  methods: {
    onLoginGuest() {
      this.nickname = localStorage.getItem("nickname");
      this.computerchips = localStorage.getItem("computerchips");
      this.playerchips = localStorage.getItem("playerchips");
    }
  },
  mounted: function() {
    if (localStorage.getItem("nickname")) {
      this.nickname = localStorage.getItem("nickname");
      this.computerchips = localStorage.getItem("computerchips");
      this.playerchips = localStorage.getItem("playerchips");
    }
  }
};
</script>

<style scoped>
</style>
