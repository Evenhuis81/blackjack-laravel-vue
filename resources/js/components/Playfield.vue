<template>
  <div class="box">
    <section class="hero" v-show="!playerName">
      <!-- <section class="hero"> -->
      <div class="hero-body">
        <div class="container">
          <button @click="reset" class="button is-danger is-light">Reset</button>
          <h1 class="title">Welcome to Blackjack, {{ playerName }} ...</h1>
          <h2 class="subtitle">Have fun!</h2>
          <!-- <button @click="startBlackjack" class="button is-info is-light" v-show="!startGame">Start game</button> -->
        </div>
      </div>
    </section>

    <!-- <div v-show="startGame"> -->
    <div>
      <nav class="level">
        <div class="level-item has-text-centered">
          <div>
            <p class="title has-text-weight-bold">Computer</p>
            <p
              class="title has-text-grey"
              style="border-top: 2px solid black"
            >{{ computerChips }} chips</p>
          </div>
        </div>
      </nav>

      <nav class="level">
        <div class="level-item has-text-centered">
          <div style="height: 122px;"></div>
          <div
            v-show="pcScore"
            style="margin-right: 10px; opacity: 1; cursor: default"
            class="button is-rounded is-primary is-small has-text-black"
          >{{ pcScore }}</div>
          <div v-for="(pccard, index) in pccards" :key="index">
            <img :src="pngcard(pccard)" alt="pngimage" style="height: 122px;" />
          </div>
        </div>
      </nav>

      <nav class="level">
        <div class="level-item has-text-centered">
          <div>
            <p class="subtitle has-text-grey-light">Dealer must draw to 16 and stand on 17</p>
            <p
              class="subtitle has-text-grey-light"
              style="border-top: 1px dotted black"
            >Blackjack pays 3:2</p>
          </div>
        </div>
      </nav>

      <nav class="level">
        <div class="level-item has-text-centered">
          <div style="height: 122px;"></div>
            <div
            disabled
            v-show="pcScore"
            style="margin-right: 10px; opacity: 1; cursor: default"
            class="button is-rounded is-primary is-small has-text-black"
          >{{ playerScore }}</div>
            <div v-for="(playercard, index) in playercards" :key="index">
            <img :src="pngcard(playercard)" alt="pngimage" style="height: 122px;" />
          </div>
        </div>
      </nav>

      <nav class="level">
        <div class="level-item has-text-centered">
          <div>
            <p class="title has-text-weight-bold">You</p>
            <p
              class="title has-text-grey"
              style="border-top: 2px solid black"
            >{{ playerChips }} chips</p>
          </div>
        </div>
      </nav>

      <nav class="level">
        <div class="level-item has-text-centered">
          <p class="heading" style="margin-right: 20px;">{{ betMsg }}</p>
          <button @click="placeBet(25)" v-show="!bet || bet === 25" :style="[baseStyle, betButtonStyling()]" :disabled="betButtonDisabled" class="button is-rounded is-primary is-small">25</button>
          <button @click="placeBet(50)" v-show="!bet || bet === 50" :style="[baseStyle, betButtonStyling()]" :disabled="betButtonDisabled" class="button is-rounded is-link">50</button>
          <button @click="placeBet(100)" v-show="!bet || bet === 100" :style="[baseStyle, betButtonStyling()]" :disabled="betButtonDisabled" class="button is-rounded is-success is-normal">100</button>
          <button @click="placeBet(250)" v-show="!bet || bet === 250" :style="[baseStyle, betButtonStyling()]" :disabled="betButtonDisabled" class="button is-rounded is-warning is-medium">250</button>
          <button @click="placeBet(500)" v-show="!bet || bet === 500" :style="[betButtonStyling()]" :disabled="betButtonDisabled" class="button is-rounded is-danger is-large">500</button>
        </div>
      </nav>
      <nav class="level" v-show="chooseNext">
        <div class="level-item has-text-centered">
          <p class="heading" style="margin-right: 20px;">{{ chooseMsg }}</p>
          <button :style="[baseStyle]" class="button is-black">Draw</button>
          <button :style="[baseStyle]" class="button is-light">Stand</button>
          <button :style="[baseStyle]" class="button is-warning">Split</button>
          <button class="button is-danger">Double</button>
        </div>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    localData: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      betMsg: "Place your Bid: ",
      chooseNext: false,
      chooseMsg: "Choose: ",
      betButtonDisabled: false,
      baseStyle: {
        marginRight: '10px',
      },
      disabledBetButtonStyle: {
        opacity: "1",
        cursor: "default"
      },
      bet: 0,
      deck: [],
      suits: ["spade", "diamond", "club", "heart"],
      values: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "jack", "queen", "king"],
      playerScore: "",
      pcScore: "",
      startGame: false,
      pccards: [],
      playercards: [],
      playerName: this.localData[0],
      computerChips: this.localData[1],
      playerChips: this.localData[2]
    };
  },
  methods: {
    betButtonStyling() {
      if (this.bet !== 0) {
        return this.disabledBetButtonStyle
      }
    },
    reset() {
      localStorage.clear();
      location.reload();
    },
    startBlackjack() {
      this.startGame = true;
    },
    pngcard(card) {
      if (card === "blank") {
        return "cards/back.png";
      }
      return "cards/" + card.Suit + "_" + card.Value + ".png";
    },
    getDeck() {
      for(let i = 0; i < this.suits.length; i++)
      {
        for(let x = 0; x < this.values.length; x++)
        {
          let card = {Value: this.values[x], Suit: this.suits[i]};
          this.deck.push(card);
        }
      }
    },
    shuffleDeck() {
      for (let i = 0; i < 1000; i++)
      {
        let location1 = Math.floor((Math.random() * this.deck.length));
        let location2 = Math.floor((Math.random() * this.deck.length));
        let tmp = this.deck[location1];

        this.deck[location1] = this.deck[location2];
        this.deck[location2] = tmp;
      }
    },
    placeBet(bidvalue) {
      this.betMsg = "Your Bet: "
      this.bet = bidvalue
      this.betButtonDisabled = true

      setTimeout(this.draw, 300, 'pc');
      setTimeout(this.pushBlank, 600);
      setTimeout(this.draw, 900, 'player');
      setTimeout(this.draw, 1200, 'player');
      setTimeout(this.setChoose, 1500);
      setTimeout(this.setScore, 1500);
    },
    setScore() {
      this.pcScore = 0
      this.playerScore = 0
      console.log('setting score')
      for (let index = 0; index < this.pccards.length; index++) {
        if (this.pccards[index] === "blank") {
          continue;
        }
        // if (this.pccards[index].Value === "jack" || this.pccards[index].Value === "queen" || this.pccards[index].Value === "king") {
        if (this.pccards[index].Value in ["jack", "queen", "king"]) {
          console.log( 'elsepcjqk')
          this.pcScore += 10
        } else {
          console.log( 'elsepc')
          this.pcScore += this.pccards[index].Value
          }
        }
      for (let index = 0; index < this.playercards.length; index++) {
        // if (playercards[index] === "blank") {
        //   continue;
        // }
        if (this.playercards[index].Value === "jack" || "queen" || "king") {
          console.log( 'elseplayerjqk')
          this.pcScore += 10
        } else {
          console.log( 'elseplayer')
          this.pcScore += this.playercards[index].Value
          }          
        }
      },
    setChoose() {
      this.chooseNext = true
    },
    pushBlank() {
      this.pccards.push("blank")
    },
    draw(val) {
      let cardindex = Math.floor(Math.random() * this.deck.length);
      let drawnCard = this.deck.splice(cardindex, 1);
      val === "pc" ? this.pccards.push(drawnCard[0]) : val === "player" ? this.playercards.push(drawnCard[0]) : "";
    }
  },
  watch: {
    localData: function(val) {
      this.playerName = this.localData[0];
      this.computerChips = this.localData[1];
      this.playerChips = this.localData[2];
    }
  },
  mounted() {
    this.getDeck();
    this.shuffleDeck();
  }
}
</script>
