<template>
  <div>
    <login v-show="!userName" @setLocalData="setLocalData"></login>
    <playfield
      v-show="userName"
      @setLocalChipsData="setLocalChips"
      :localData="[userName, userChips]"
    ></playfield>
  </div>
</template>

<script>
import Login from "./components/Login.vue";
import Playfield from "./components/Playfield.vue";

export default {
  data: function() {
    return {
      userName: false,
      userChips: false
    };
  },
  components: {
    Login,
    Playfield
  },
  methods: {
    setLocalData() {
      this.userName = localStorage.getItem("username");
      this.userChips = localStorage.getItem("userchips");
    },
    setLocalChips() {
      this.userChips = localStorage.getItem("userchips");
    }
  },
  mounted: function() {
    if (localStorage.getItem("username")) {
      this.userName = localStorage.getItem("username");
      this.userChips = localStorage.getItem("userchips");
    }
  }
};
</script>

<style scoped>
</style>
