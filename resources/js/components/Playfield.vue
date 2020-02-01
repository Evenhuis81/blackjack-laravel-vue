<template>
  <div class="box has-text-centered">
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
      <div class="level-item">
        <div>
          <p class="title has-text-weight-bold">Computer</p>
        </div>
      </div>
    </nav>

    <nav class="level">
      <div class="level-item">
        <div style="height: 122px;"></div>
        <div
          disabled
          style="font-weight: bold; width: 20px; margin-right: 10px; opacity: 1; cursor: default;"
          class="button is-rounded is-primary is-small has-text-black"
          :style="{ visibility: showScore ? 'visible' : 'hidden' }"
        >{{ pc.score }}</div>
        <div v-for="(card, index) in pc.cards" :key="index">
          <img :src="pngCard(card)" alt="pngimage" style="height: 122px;" />
        </div>
      </div>
    </nav>

    <nav class="level">
      <div class="level-item">
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
      <div class="level-item">
        <div style="height: 122px;"></div>
        <div
          disabled
          style="font-weight: bold; width: 20px; margin-right: 10px; opacity: 1; cursor: default;"
          class="button is-rounded is-primary is-small has-text-black"
          :style="{ visibility: showScore ? 'visible' : 'hidden' }"
        >{{ user.score }}</div>
        <div v-for="(card, index) in user.cards" :key="index">
          <img :src="pngCard(card)" alt="pngimage" style="height: 122px;" />
        </div>
      </div>
    </nav>

    <nav class="level">
      <div class="level-item">
        <div>
          <p class="title has-text-weight-bold">You</p>
          <p class="title has-text-grey" style="border-top: 2px solid black">
            {{ user.chips }} chips
            <span
              v-show="updatingChipsValue"
              :style="{ color: updatingChipsValue < 0 ? 'red' : 'green' }"
            >( {{ updatingChipsValue > 0 ? "+" + updatingChipsValue : updatingChipsValue }} )</span>
          </p>
        </div>
      </div>
    </nav>

    <nav class="level">
      <div class="level-item">
        <div>
          <p class="title" :style="resultMsgStyle">{{ resultMsg }}</p>
        </div>
      </div>
    </nav>

    <nav class="level" v-show="showBidButtons">
      <div class="level-item">
        <div style="height: 60px;"></div>
        <p class="heading" style="margin-right: 20px;">{{ betMsg }}</p>
        <button
          v-for="bidButton in bidButtons"
          :key="bidButton.bid"
          @click="placeBet(bidButton.bid)"
          v-show="user.chips >= bidButton.bid && !user.bet || user.bet === bidButton.bid"
          :style="[betButtonStyle]"
          :disabled="betButtonDisabled"
          :class="['button', 'is-rounded', bidButton.size, bidButton.color]"
        >{{ bidButton.bid }}</button>
      </div>
    </nav>

    <!-- userOption, whatToDoNext, drawAndSuch, DrawStandSplitDouble, selectedUserOption, afterSetupOptionsUser, userIsActiveOption, DrawDecision ?? -->
    <nav class="level" v-show="chooseNext">
      <div class="level-item">
        <p class="heading" style="font-weight: bold; margin-right: 20px;">{{ chooseMsg }}</p>
        <button
          v-for="(chooseButton) in chooseButtons"
          :key="chooseButton.selected"
          @click="selectedButton(chooseButton.selected)"
          v-show="chooseButton.show"
          :style="[chooseButtonStyle]"
          :disabled="chooseButton.disabled"
          :class="['button', 'is-light', chooseButton.color]"
        >{{ chooseButton.selected }}</button>
      </div>
    </nav>
  </div>
  <!-- /box -->
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
      chooseButtons: [
        {
          selected: "Draw",
          show: true,
          color: "is-info",
          disabled: false
        },
        {
          selected: "Stand",
          show: true,
          color: "is-success",
          disabled: false
        },
        {
          selected: "Split",
          show: true,
          color: "is-warning",
          disabled: false
        },
        {
          selected: "Double",
          show: true,
          color: "is-danger",
          disabled: false
        }
      ],
      showScore: false,
      showBidButtons: true,
      resultMsg: ".",
      betMsg: "Place your Bid: ",
      chooseMsg: "Choose: ",
      // showChooseButtons
      chooseNext: false,
      chooseNextTimeout: null,
      drawPcCardsInterval: null,
      removePcCardsInterval: null,
      removeUserCardsInterval: null,
      chooseButtonDisabled: false,
      betButtonDisabled: false,
      betButtonStyle: {
        marginRight: "10px",
        opacity: "1"
      },
      chooseButtonStyle: {
        marginRight: "15px"
      },
      resultMsgStyle: {
        visibility: "hidden",
        color: "purple"
      },
      bidButtons: [
        {
          bid: 25,
          size: "is-small",
          color: "is-primary"
        },
        {
          bid: 50,
          size: "is-normal",
          color: "is-success"
        },
        {
          bid: 100,
          size: "is-medium",
          color: "is-warning"
        },
        {
          bid: 250,
          size: "is-large",
          color: "is-danger"
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
    // can use same parameter as other switches?
    selectedButton(chooseResult) {
      switch (chooseResult) {
        case "Draw":
          this.draw();
          break;
        case "Stand":
          this.stand();
          break;
        case "Split":
          this.split();
          break;
        case "Double":
          this.double();
          break;
        default:
          alert("SOMETHING WENT WRONG IN SELECTEDBUTTON SWITCH");
      }
    },
    split() {
      //
    },
    double() {
      //
    },
    placeBet(bid) {
      this.betButtonStyle.cursor = "default";
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

      setTimeout(() => {
        this.showScore = true;
      }, 1350);
      // choosenexttimeout variable must be set before setTimeout player draw 2nd card in case of blackjack so it can be removed
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
      //   this.activePlayer.player1
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
          if (this.chooseNextTimeout) {
            clearTimeout(this.chooseNextTimeout);
          }
          // can't break chips rule applies here (with 3:2 payout), not implemented (not defined what each chip is worth + color etc. in any case, it's hard to make clear without physical chips)
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
          }, 900);
          setTimeout(() => {
            this.resultMsgStyle.color = "red";
          }, 1050);
          setTimeout(() => {
            this.resultMsgStyle.color = "black";
          }, 1200);
          break;
        case 1:
          // chooseNext: What you really want is after click no rapid 2nd click until script is done (in case of "hanging"(?), thus on top of script)
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
          setTimeout(() => {
            this.resultMsgStyle.color = "blue";
          }, 400);
          setTimeout(() => {
            this.resultMsgStyle.color = "green";
          }, 800);
          setTimeout(() => {
            this.resultMsgStyle.color = "black";
          }, 1200);
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
          alert("SOMETHING WENT WRONG SETMSG SWITCH");
      }
    },
    nextRound() {
      // if less than 25 playerchips, give info about registration and playing online etc.
      this.resultMsgStyle.visibility = "hidden";
      this.resultMsg = ".";
      this.resultMsgStyle.color = "black";
      this.user.aces = 0;
      this.user.score = 0;
      this.user.stand = false;
      this.pc.stand = false;
      this.pc.aces = 0;
      this.pc.score = 0;
      this.activePlayer = undefined;
      let timer = 0;
      this.pc.cards.length >= this.user.cards.length
        ? (timer = this.pc.cards.length * 200)
        : (timer = this.user.cards.length * 200);
      setTimeout(() => {
        this.showScore = false;
      }, 100);

      setTimeout(() => {
        this.betButtonDisabled = false;
        delete this.betButtonStyle.cursor;
        this.betMsg = "Place your Bid: ";
        this.chooseMsg = "Choose: ";
        this.user.bet = 0;
      }, timer + 200);
      this.removePcCardsInterval = setInterval(
        this.removePcCardsIntervalHandler,
        200
      );
      setTimeout(() => {
        this.removeUserCardsInterval = setInterval(
          this.removeUserCardsIntervalHandler,
          200
        );
      }, 200);
      this.newDeck();
      this.shuffleDeck();
    },
    removePcCardsIntervalHandler() {
      if (this.pc.cards.length === 0) {
        clearInterval(this.removePcCardsInterval);
      } else {
        this.pc.cards.pop();
      }
    },
    removeUserCardsIntervalHandler() {
      if (this.user.cards.length === 0) {
        clearInterval(this.removeUserCardsInterval);
      } else {
        this.user.cards.pop();
      }
    },
    drawPcCardsIntervalHandler() {
      if (this.pc.stand) {
        clearInterval(this.drawPcCardsInterval);
      } else {
        this.draw();
      }
    },
    stand() {
      this.chooseNext = false;
      this.user.stand = true;
      this.activePlayer = this.pc;
      this.pc.cards.pop();
      this.draw();
      this.drawPcCardsInterval = setInterval(
        this.drawPcCardsIntervalHandler,
        600
      );
    },
    // betButtonStyling() {
    //   if () {
    //     return this.disabledBetButtonStyle;
    //   }
    // },
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
