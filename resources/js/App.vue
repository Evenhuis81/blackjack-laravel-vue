<template>
  <div>
    <login v-show="!nickname" @setLocalData="setLocalData"></login>
    <playfield v-show="nickname" @setLocalChipsData="setLocalChips" :localData="[nickname, playerchips]"></playfield>
  </div>
</template>

<script>
import Login from "./components/Login.vue";
import Playfield from "./components/Playfield.vue";

export default {
  data: function() {
    return {
      nickname: false,
      playerchips: false
    };
  },
  components: {
    Login,
    Playfield
  },
  methods: {
    setLocalData() {
      this.nickname = localStorage.getItem("nickname");
      this.playerchips = localStorage.getItem("playerchips");
    },
    setLocalChips() {
      this.playerchips = localStorage.getItem("playerchips");
    }
  },
  mounted: function() {
    if (localStorage.getItem("nickname")) {
      this.nickname = localStorage.getItem("nickname");
      this.playerchips = localStorage.getItem("playerchips");
    }
  }
};
</script>

<style scoped>
</style>
