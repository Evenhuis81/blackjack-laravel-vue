<template>
    <div class="box">
        <section class="hero">
            <div class="hero-body">
                <div class="container">
                    <button @click="reset" class="button is-danger is-light">Reset</button>
                    <h1 class="title">
                        Welcome to Blackjack, {{ playerName }} ...
                    </h1>
                    <h2 class="subtitle">
                        Have fun!
                    </h2>
                    <!-- <button @click="startBlackjack" class="button is-info is-light" v-show="!startGame">Start game</button> -->
                </div>
            </div>
        </section>

        <!-- <div v-show="startGame"> -->
            <div>
            <nav class="level">
                <div class="level-item has-text-centered">
                    <div>
                    <p class="title has-text-weight-bold">Dealer</p>
                    <p class="title has-text-grey" style="border-top: 2px solid black">{{ computerChips }} chips</p>
                    </div>
                </div>
            </nav>

            <nav class="level">
                <div class="level-item has-text-centered">
                    <div style="height: 122px;"></div>
                    <div disabled v-show="pcScore" style="margin-right: 10px; opacity: 1; cursor: default" class="button is-rounded is-primary is-small has-text-black">{{ pcScore }}</div>
                    <div v-for="(pccard, index) in pccards" :key="index">
                    <img :src="pngcard(pccard)" alt="pngimage" style="height: 122px;">
                    <!-- <p class="heading" v-if="pccard === 0">blank</p> -->
                    <!-- <img src="cards/back.png" alt="card" style="height: 122px; width: auto;"> -->
                    
                    <!-- <p class="heading" v-else>{{ pccard }}</p> -->
                    </div>
                </div>
            </nav>

            <nav class="level">
                <div class="level-item has-text-centered">
                    <div>
                    <p class="subtitle has-text-grey-light">Dealer must draw to 16 and stand on 17</p>
                    <p class="subtitle has-text-grey-light" style="border-top: 1px dotted black">Blackjack pays 3:2</p>
                    </div>
                </div>
            </nav>

            <nav class="level">
                <div class="level-item has-text-centered">
                    <!-- <div v-for="(playercard, index) in playercards" :key="index"> -->
                    <div style="height: 122px;"></div>
                    <!-- <p class="heading" v-if="playercard === 0">blank</p>
                    <p class="heading" v-else>{{ playercard }}</p> -->
                    <!-- </div> -->
                </div>
            </nav>

            <nav class="level">
                <div class="level-item has-text-centered">
                    <div>
                    <p class="title has-text-weight-bold">You</p>
                    <p class="title has-text-grey" style="border-top: 2px solid black">{{ playerChips }} chips</p>
                    </div>
                </div>
            </nav>

            <nav class="level">
                <div class="level-item has-text-centered">
                    <p class="heading" style="margin-right: 20px;">Bet: </p>
                    <button style="margin-right: 10px;" class="button is-rounded is-primary is-small">25</button>
                    <button style="margin-right: 10px;" class="button is-rounded is-link">50</button>
                    <button style="margin-right: 10px;" class="button is-rounded is-success is-normal">100</button>
                    <button style="margin-right: 10px;" class="button is-rounded is-warning is-medium">250</button>
                    <button class="button is-rounded is-danger is-large">500</button>
                </div>
            </nav>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            playerScore: 0,
            pcScore: 1,
            startGame: false,
            pccards: [0, 0, 0],
            playercards: [0, 0],
            playerName: localStorage.getItem('nickname'),
            playerChips: localStorage.getItem('playerchips'),
            computerChips: localStorage.getItem('computerchips'),
        }
    },
    methods: {
        reset() {
            localStorage.clear();
            location.reload();
        },
        startBlackjack() {
            this.startGame = true
        },
        pngcard(card) {
            if (card === 0) {
                return "cards/back.png";
            }
        }
    }
}
</script>