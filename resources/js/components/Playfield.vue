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
            <!-- <p
              class="title has-text-grey"
              style="border-top: 2px solid black"
            >{{ computerChips }} chips</p> -->
          </div>
        </div>
      </nav>

      <nav class="level">
        <div class="level-item has-text-centered">
          <div style="height: 122px;"></div>
          <div
            disabled
            style="font-weight: bold; width: 20px; margin-right: 10px; opacity: 1; cursor: default;"
            class="button is-rounded is-primary is-small has-text-black"
            :style="{ visibility: pcScore ? 'visible' : 'hidden' }"
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
            style="font-weight: bold; width: 20px; margin-right: 10px; opacity: 1; cursor: default;"
            class="button is-rounded is-primary is-small has-text-black"
            :style="{ visibility: playerScore ? 'visible' : 'hidden' }"
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
          <div>
            <p class="title" :style="resultMsgStyle">{{ resultMessage ? resultMessage : 'placeholder' }}</p>
          </div>
        </div>
      </nav>

      <nav class="level">
        <div class="level-item has-text-centered">
          <div style="height: 60px;"></div>
          <p class="heading" style="margin-right: 20px;">{{ betMsg }}</p>
          <button
            @click="placeBet(25)"
            v-show="playerChips >= 25 && !bet || bet === 25"
            :style="[baseStyle, betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-primary is-small"
          >25</button>
          <button
            @click="placeBet(50)"
            v-show="playerChips >= 50 && !bet || bet === 50"
            :style="[baseStyle, betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-link"
          >50</button>
          <button
            @click="placeBet(100)"
            v-show="playerChips >= 100 && !bet || bet === 100"
            :style="[baseStyle, betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-success is-normal"
          >100</button>
          <button
            @click="placeBet(250)"
            v-show="playerChips >= 250 && !bet || bet === 250"
            :style="[baseStyle, betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-warning is-medium"
          >250</button>
          <button
            @click="placeBet(500)"
            v-show="playerChips >= 500 && !bet || bet === 500"
            :style="[betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-danger is-large"
          >500</button>
        </div>
      </nav>
      <nav class="level" v-show="chooseNext">
        <div class="level-item has-text-centered">
          <p class="heading" style="margin-right: 20px;">{{ chooseMsg }}</p>
          <button @click="draw('player')" :style="[baseStyle]" class="button is-black">Draw</button>
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
      resultMessage: "",
      betMsg: "Place your Bid: ",
      chooseNext: false,
      chooseMsg: "Choose: ",
      betButtonDisabled: false,
      baseStyle: {
        marginRight: "10px"
      },
      disabledBetButtonStyle: {
        opacity: "1",
        cursor: "default"
      },
      resultMsgStyle: {
        visibility: 'hidden',
        border: '1px solid black',
        color: 'purple'
      },
      bet: 0,
      deck: [],
      suits: ["spade", "diamond", "club", "heart"],
      values: [
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        "10",
        "jack",
        "queen",
        "king"
      ],
      pcAces: 0,
      playerAces: 0,
      pcScore: 0,
      playerScore: 0,
      pccards: [],
      playercards: [],
      // startGame: false,
      playerName: this.localData[0],
      playerChips: this.localData[1],
    };
  },
  methods: {
    resultMsgStyling() {
      if (this.resultMessage === "BLACKJACK") {
        this.resultMsgStyle.border = 'red solid 2px';
        this.resultMsgStyle.color = 'red'
      }
      if (this.resultMessage) {
        // this.resultMsgStyle.visibility = 'visible' : this.resultMsgStyle.visibility = 'hidden'
      }
      
    },
    resultVisible() {
      return { visibility: this.resultMessage ? 'visible' : 'hidden' };
    },
    betButtonStyling() {
      if (this.bet !== 0) {
        return this.disabledBetButtonStyle;
      }
    },
    reset() {
      localStorage.clear();
      location.reload();
    },
    startBlackjack() {
      this.resultMsgAdditionStyle
      this.startGame = true;
    },
    pngcard(card) {
      if (card === "blank") {
        return "cards/back.png";
      }
      return "cards/" + card.Suit + "_" + card.Value + ".png";
    },
    newDeck() {
      if (this.deck.length) {
        this.deck = [];
      }
      for (let i = 0; i < this.suits.length; i++) {
        for (let x = 0; x < this.values.length; x++) {
          let card = { Value: this.values[x], Suit: this.suits[i] };
          this.deck.push(card);
        }
      }
    },
    shuffleDeck() {
      for (let i = 0; i < 1000; i++) {
        let location1 = Math.floor(Math.random() * this.deck.length);
        let location2 = Math.floor(Math.random() * this.deck.length);
        let tmp = this.deck[location1];
        this.deck[location1] = this.deck[location2];
        this.deck[location2] = tmp;
      }
    },
    placeBet(bidvalue) {
      // this.playerScore = 21;
      // this.playercards = [1, 1];
      // this.getResult("player");
      // return;
      this.betMsg = "Your Bet: ";
      this.bet = bidvalue;
      this.betButtonDisabled = true;
      this.setplayerChips(bidvalue)
      setTimeout(this.draw, 300, "pc");
      setTimeout(this.pushBlank, 600);
      setTimeout(this.draw, 900, "player");
      setTimeout(this.draw, 1200, "player");
      setTimeout(this.setChoose, 1500);
    },
    setplayerChips(bid) {
      let newVal = this.playerChips - bid
      localStorage.setItem("playerchips", newVal)
      this.$emit("setLocalChipsData", "")
    },
    setScore(val) {
      if (val === "pc") {
        let card = this.pccards[this.pccards.length - 1]
        if (card.Value === "1") {
          this.pcAces += 1;
          this.pcScore += 11;
        } else if (card.Value === "jack" || card.Value === "queen" || card.Value === "king") {
          this.pcScore += 10;
        } else {
            this.pcScore += parseInt(card.Value);
        }
        this.getResult("pc");
      }
      if (val === "player") {
        let card = this.playercards[this.playercards.length - 1]
        if (card.Value === "1") {
          this.playerAces += 1;
          this.playerScore += 11;
          // if (this.pccards[i].Value in ["jack", "queen", "king"]) {
        } else if (card.Value === "jack" || card.Value === "queen" || card.Value === "king") {
          this.playerScore += 10;
        } else {
            this.playerScore += parseInt(card.Value);
        }
        this.getResult("player");
      }
    },
    getResult(val) {
      if (val === "player") {
        if (this.playercards.length === 2) {
          this.resultMessage = "BLACKJACK";
          this.resultMsgStyling();
          setTimeout(function () {
            this.resultMessage = ""
            this.resultMsgStyling()
            }.bind(this), 3000)
        }
      }
    },
    setChoose() {
      this.chooseNext = true;
    },
    pushBlank() {
      this.pccards.push("blank");
    },
    draw(val) {
      let cardindex = Math.floor(Math.random() * this.deck.length);
      let drawnCard = this.deck.splice(cardindex, 1);
      if (val === "pc") {
        this.pccards.push(drawnCard[0])
        this.setScore("pc")
      }
      if (val === "player") { 
        this.playercards.push(drawnCard[0])
        this.setScore("player")
      }
    }
  },
  watch: {
    localData: function(val) {
      this.playerName = this.localData[0];
      this.playerChips = this.localData[1];
    }
  },
  mounted() {
    this.newDeck();
    this.shuffleDeck();
  }
};
</script>
