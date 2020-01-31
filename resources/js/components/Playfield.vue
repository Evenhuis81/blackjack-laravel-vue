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
          :style="{ visibility: pc.cards.length >= 2 ? 'visible' : 'hidden' }"
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
          :style="{ visibility: user.cards.length >= 2 ? 'visible' : 'hidden' }"
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
          <p class="title has-text-grey" style="border-top: 2px solid black">
            {{ user.chips }} chips
            <span
              v-show="updatingChipsValue"
              :style="{ color: updatingChipsValue < 0 ? 'red' : 'green' }"
            >( {{ updatingChipsValue }} )</span>
          </p>
        </div>
      </div>
    </nav>

    <nav class="level">
      <div class="level-item has-text-centered">
        <div>
          <p class="title" :style="resultMsgStyle">{{ resultMsg }}</p>
        </div>
      </div>
    </nav>

    <nav class="level">
      <div class="level-item has-text-centered">
        <div style="height: 60px;"></div>
        <p class="heading" style="margin-right: 20px;">{{ betMsg }}</p>
        <button
          v-for="bidButton in bidButtons"
          :key="bidButton.bid"
          @click="placeBet(bidButton.bid)"
          v-show="user.chips >= bidButton.bid && !user.bet || user.bet === bidButton.bid"
          :style="[betButtonStyle, betButtonStyling()]"
          :disabled="betButtonDisabled"
          class="button is-rounded"
          :class="['button', 'is-rounded', bidButton.size, bidButton.type]"
        >{{ bidButton.bid }}</button>
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
      resultMsg: ".",
      betMsg: "Place your Bid: ",
      chooseMsg: "Choose: ",
      chooseNext: false,
      chooseNextTimeout: null,
      removePcCardsInterval: null,
      removeUserCardsInterval: null,
      betButtonDisabled: false,
      betButtonStyle: {
        marginRight: "10px"
      },
      disabledBetButtonStyle: {
        opacity: "1",
        cursor: "default"
      },
      resultMsgStyle: {
        visibility: "hidden",
        color: "purple"
      },
      bidButtons: [
        {
          bid: 25,
          size: "is-small",
          type: "is-primary"
        },
        {
          bid: 50,
          size: "is-normal",
          type: "is-success"
        },
        {
          bid: 100,
          size: "is-medium",
          type: "is-warning"
        },
        {
          bid: 250,
          size: "is-large",
          type: "is-danger"
        }
      ],
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
        proap: "bla",
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
        chips: this.localData[0]
      },
      activePlayer: undefined,
      updatingChipsValue: 0
    };
  },
  methods: {
    placeBet(bid) {
      this.activePlayer = this.pc;
      this.betMsg = "Your Bet: ";
      this.user.bet = bid;
      this.betButtonDisabled = true;
      this.setUserChips(bid, "subtract");
      setTimeout(() => {
        this.draw();
      }, 300);
      setTimeout(() => {
        this.activePlayer = this.user;
        this.draw();
      }, 600);
      setTimeout(() => {
        this.pc.cards.push("blank");
      }, 900);
      this.chooseNextTimeout = setTimeout(() => {
        this.chooseNext = true;
      }, 1500);
      setTimeout(() => {
        this.draw();
      }, 1200);
    },
    setUserChips(value, plusOrMin) {
      let newValue = undefined;
      if (plusOrMin === "subtract") {
        newValue = this.user.chips - value;
        this.updatingChipsValue -= value;
        setTimeout(() => {
          this.updatingChipsValue = 0;
        }, 1000);
      } else {
        newValue = parseInt(this.user.chips) + value;
        this.updatingChipsValue += value;
        setTimeout(() => {
          this.updatingChipsValue = 0;
        }, 1000);
      }
      localStorage.setItem("userchips", newValue);
      setTimeout(() => {
        this.$emit("setLocalChipsData", "");
      }, 1000);
    },
    draw() {
      let cardindex = Math.floor(Math.random() * this.deck.length);
      let drawnCard = this.deck.splice(cardindex, 1);
      this.activePlayer.cards.push(drawnCard[0]);
      this.setScore();
    },
    setScore() {
      let card = this.activePlayer.cards[this.activePlayer.cards.length - 1];
      if (card.Value === "1") {
        this.activePlayer.aces += 1;
        this.activePlayer.score += 11;
      } else if (
        card.Value === "jack" ||
        card.Value === "queen" ||
        card.Value === "king"
      ) {
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
      // 0 = User Blackjack
      // 1 = Pc Win
      // 2 = User Lose
      // 3 = User Win
      // 4 = Tie
      if (
        this.activePlayer.cards.length === 2 &&
        this.activePlayer.score === 21
      ) {
        if (this.activePlayer.hasOwnProperty("bet")) {
          this.setMsg(0);
          return;
        } else {
          this.setMsg(1);
          return;
        }
      }
      if (this.activePlayer.score > 21) {
        if (this.activePlayer.hasOwnProperty("bet")) {
          this.setMsg(2);
          return;
        } else {
          this.setMsg(3);
          return;
        }
      }
      if (this.pc.score > 16 && this.activePlayer.hasOwnProperty("proap")) {
        this.pc.stand = true;
      }
      if (this.user.stand && this.pc.stand) {
        if (this.user.score === this.pc.score) {
          this.setMsg(4);
          return;
        } else if (this.user.score > this.pc.score) {
          this.setMsg(3);
          return;
        } else {
          this.setMsg(1);
          return;
        }
      }
    },
    setMsg(result) {
      switch (result) {
        case 0:
          clearTimeout(this.chooseNextTimeout);
          this.setUserChips(this.user.bet * 2.5);
          this.resultMsg = "You got Blackjack!!";
          this.resultMsgStyle.visibility = "visible";
          setTimeout(() => {
            this.resultMsgStyle.visibility = "hidden";
            this.resultMsg = ".";
            this.nextRound();
          }, 2000);
          // Write for loop with modulo and timeout increase variable
          setTimeout(() => {
            this.resultMsgStyle.color = "red";
          }, 150);
          setTimeout(() => {
            this.resultMsgStyle.color = "black";
          }, 300);
          setTimeout(() => {
            this.resultMsgStyle.color = "red";
          }, 450);
          setTimeout(() => {
            this.resultMsgStyle.color = "black";
          }, 600);
          setTimeout(() => {
            this.resultMsgStyle.color = "red";
          }, 750);
          setTimeout(() => {
            this.resultMsgStyle.color = "black";
          }, 1200);
          break;
        case 1:
          // chooseNext: What you really want is after click no 2nd click until script is done (in case of "hanging"(?), thus on top of script)
          this.chooseNext = false;
          this.pc.stand = true;
          this.resultMsg = "The Computer Wins";
          this.resultMsgStyle.color = "red";
          this.resultMsgStyle.visibility = "visible";
          setTimeout(() => {
            this.nextRound();
          }, 2000);
          break;
        case 2:
          this.chooseNext = false;
          this.resultMsg = "Busted!";
          this.resultMsgStyle.color = "red";
          this.resultMsgStyle.visibility = "visible";
          setTimeout(() => {
            this.nextRound();
          }, 2000);
          break;
        case 3:
          this.chooseNext = false;
          this.pc.stand = true;
          this.setUserChips(this.user.bet * 2);
          this.resultMsg = "Victory";
          this.resultMsgStyle.visibility = "visible";
          setTimeout(() => {
            this.nextRound();
          }, 2000);
          break;
        case 4:
          this.chooseNext = false;
          this.setUserChips(this.user.bet);
          this.resultMsg = "Push";
          this.resultMsgStyle.color = "purple";
          this.resultMsgStyle.visibility = "visible";
          setTimeout(() => {
            this.nextRound();
          }, 2000);
          break;
        default:
          alert("SOMETHING WENT WRONG");
      }
    },
    nextRound() {
      this.resultMsgStyle.visibility = "hidden";
      this.resultMsg = ".";
      this.resultMsgStyle.color = "black";
      this.user.bet = 0;
      this.user.aces = 0;
      this.user.score = 0;
      this.user.stand = false;
      this.pc.aces = 0;
      this.pc.score = 0;
      this.pc.stand = false;
      this.betMsg = "Place your Bid: ";
      this.chooseMsg = "Choose: ";
      this.activePlayer = undefined;
      this.removePcCardsInterval = setInterval(this.removePcCards, 200);
      setTimeout(() => {
        this.removeUserCardsInterval = setInterval(this.removeUserCards, 200);
      }, 200);
      setTimeout(() => {
        this.betButtonDisabled = false;
      }, 1000);
      this.newDeck();
      this.shuffleDeck();
    },
    removePcCards() {
      if (this.pc.cards.length === 0) {
        clearInterval(this.removePcCardsInterval);
      } else {
        this.pc.cards.pop();
      }
    },
    removeUserCards() {
      if (this.user.cards.length === 0) {
        clearInterval(this.removeUserCardsInterval);
      } else {
        this.user.cards.pop();
      }
    },
    playerStand() {
      this.chooseNext = false;
      this.user.stand = true;
      this.activePlayer = this.pc;
      this.pc.cards.pop();
      while (!this.pc.stand) {
        this.draw();
      }
    },
    betButtonStyling() {
      if (this.user.bet !== 0) {
        return this.disabledBetButtonStyle;
      }
    },
    reset() {
      localStorage.clear();
      location.reload();
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
