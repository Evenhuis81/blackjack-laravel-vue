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
    // je kunt wel een user login hervatten als er een username in localStorage staat, maar roep dan een initialize functie aan die de chipcount reset.
    // dezelfde initialize functie kun je ook voor je login event gebruiken
    if (localStorage.getItem("username")) {
      this.userName = localStorage.getItem("username");
      this.userChips = localStorage.getItem("userchips");
    }
  }
};
</script>

<!-- lege styling weglaten -->
<style scoped>
</style>
