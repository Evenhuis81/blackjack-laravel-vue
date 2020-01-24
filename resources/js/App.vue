<template>
    <div>
    <login v-show="!nickname"></login>
    <playfield v-show="nickname"></playfield>
    </div>
</template>

<script>
import Login from "./components/Login.vue"
import Playfield from "./components/Playfield.vue"

export default {
    data() {
        return {
            nickname: !!localStorage.getItem('nickname'),
        }
    },
    components: {
        Login,
        Playfield
    },
    // mounted() {
    //     if (localStorage.getItem('nickname')) {
    //         this.nickname = true;
    //     } else {
    //         this.nickname = false;
    //     }
    // }
}
</script>

<style scoped>
</style>