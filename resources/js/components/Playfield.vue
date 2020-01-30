<template>
  <div class="box">
    <!-- <section class="hero" v-show="user.name"> -->
    <section class="hero" v-show="!user.name">
      <div class="hero-body">
        <div class="container">
          <button @click="reset" class="button is-danger is-light">Reset</button>
          <h1 class="title">Welcome to Blackjack, {{ user.name }} ...</h1>
          <h2 class="subtitle">Have fun!</h2>
        </div>
      </div>
    </section>

      <nav class="level">
        <div class="level-item has-text-centered">
          <div>
            <p class="title has-text-weight-bold">Computer</p>

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
            :style="{ visibility: pc.score ? 'visible' : 'hidden' }"
          >{{ pc.score }}</div>
          <div v-for="(card, index) in pc.cards" :key="index">
            <img :src="pngCard(card)" alt="pngimage" style="height: 122px;" />
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
            :style="{ visibility: user.score ? 'visible' : 'hidden' }"
          >{{ user.score }}</div>
          <div v-for="(card, index) in user.cards" :key="index">
            <img :src="pngCard(card)" alt="pngimage" style="height: 122px;" />
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
            >{{ user.chips }} chips</p>
          </div>
        </div>
      </nav>

      <nav class="level">
        <div class="level-item has-text-centered">
          <div>
            <p class="title" :style="resultMsgStyle">{{ resultMsg ? resultMsg : 'placeholder' }}</p>
          </div>
        </div>
      </nav>

      <nav class="level">
        <div class="level-item has-text-centered">
          <div style="height: 60px;"></div>
          <p class="heading" style="margin-right: 20px;">{{ betMsg }}</p>
          <button
            @click="placeBet(25)"
            v-show="user.chips >= 25 && !user.bet || user.bet === 25"
            :style="[betButtonStyle, betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-primary is-small"
          >25</button>
          <button
            @click="placeBet(50)"
            v-show="user.chips >= 50 && !user.bet || user.bet === 50"
            :style="[betButtonStyle, betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-link"
          >50</button>
          <button
            @click="placeBet(100)"
            v-show="user.chips >= 100 && !user.bet || user.bet === 100"
            :style="[betButtonStyle, betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-success is-normal"
          >100</button>
          <button
            @click="placeBet(250)"
            v-show="user.chips >= 250 && !user.bet || user.bet === 250"
            :style="[betButtonStyle, betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-warning is-medium"
          >250</button>
          <button
            @click="placeBet(500)"
            v-show="user.chips >= 500 && !user.bet || user.bet === 500"
            :style="[betButtonStyling()]"
            :disabled="betButtonDisabled"
            class="button is-rounded is-danger is-large"
          >500</button>
        </div>
      </nav>
      <nav class="level" v-show="chooseNext">
        <div class="level-item has-text-centered">
          <p class="heading" style="margin-right: 20px;">{{ chooseMsg }}</p>
          <button @click="draw()" :style="[betButtonStyle]" class="button is-black">Draw</button>
          <button @click="playerStand()" :style="[betButtonStyle]" class="button is-light">Stand</button>
          <button :style="[betButtonStyle]" class="button is-warning">Split</button>
          <button class="button is-danger">Double</button>
        </div>
      </nav>
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
      resultMsg: "",
      betMsg: "Place your Bid: ",
      chooseNext: false,
      chooseMsg: "Choose: ",
      betButtonDisabled: false,
      betButtonStyle: {
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
      pc: {
        stand: false,
        aces: 0,
        score: 0,
        cards: []
      },
      user: {
        stand: false,
        bet: 0,
        aces: 0,
        score: 0,
        cards: [],
        name: this.localData[0],
        chips: this.localData[0],
      },
      activePlayer: undefined,
      // startGame: false,
    };
  },
  methods: {
    betButtonStyling() {
      if (this.user.bet !== 0) {
        return this.disabledBetButtonStyle;
      }
    },
    reset() {
      localStorage.clear();
      location.reload();
    },
    startBlackjack() {
      // this.resultMsgAdditionStyle
      // this.startGame = true;
    },
    pngCard(card) {
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
    placeBet(bid) {
      this.activePlayer = this.pc;
      // this.userScore = 21;
      // this.userCards = [1, 1];
      // this.getResult("user");
      // return;
      this.betMsg = "Your Bet: ";
      this.user.bet = bid;
      this.betButtonDisabled = true;
      this.setUserChips(bid)
      setTimeout(() => {
        this.draw();
      }, 300);
      setTimeout(() => {
        this.pc.cards.push("blank")
      }, 600);
      setTimeout(() => {
        this.activePlayer = this.user
        this.draw();
      }, 900);
      setTimeout(() => {
        this.draw();
      }, 1200);
      setTimeout(() => {
        this.chooseNext = true;
      }, 1500);
    },
    setUserChips(bid) {
      let newVal = this.user.chips - bid
      localStorage.setItem("userchips", newVal)
      this.$emit("setLocalChipsData", "")
    },
    draw() {
      let cardindex = Math.floor(Math.random() * this.deck.length);
      let drawnCard = this.deck.splice(cardindex, 1);
      this.activePlayer.cards.push(drawnCard[0]);
      this.setScore()
    },
    setScore() {
      let card = this.activePlayer.cards[this.activePlayer.cards.length -1];
        if (card.Value === "1") {
          this.activePlayer.aces += 1;
          this.activePlayer.score += 11;
        } else if (card.Value === "jack" || card.Value === "queen" || card.Value === "king") {
          this.activePlayer.score += 10;
        } else {
            this.activePlayer.score += parseInt(card.Value);
        }
        while (this.activePlayer.score > 21 && this.activePlayer.aces !== 0) {
          this.activePlayer.aces--;
          this.activePlayer.score -= 10;
        }
        this.getResult();
    },
    getResult() {
      if (this.activePlayer.cards.length === 2 && this.activePlayer.score === 21) {
        if (this.activePlayer.hasOwnProperty('bet')) {
          alert("You got Blackjack!!");
          return;
        } else {
          alert("The Computer Wins");
        }
      }
      if (this.activePlayer.score > 21 && this.activePlayer.hasOwnProperty('bet')) {
        alert("Busted!");
      } else if (this.activePlayer.score > 21) {
        alert("Victory!");
      }
      if (this.user.stand && this.pc.stand) {
        if (this.user.score === this.pc.score) {
          alert("Push");
        } else if (this.user.score > this.pc.score) {
          alert("Victory");
        } else {
          alert("The Computer Wins");
        }
      }
      if (this.pc.score > 16) {this.pc.stand = true}
    },
    playerStand() {
      this.user.stand = true;
      this.activePlayer = this.pc;
      this.pc.cards.pop();
      while(!this.pc.stand) {this.draw()}
      this.getResult();
    }
  },
  watch: {
    localData: function(newVal) {
      this.user.name = this.localData[0];
      this.user.chips = this.localData[1];
    }
  },
  mounted() {
    this.newDeck();
    this.shuffleDeck();
  }
};
</script>
