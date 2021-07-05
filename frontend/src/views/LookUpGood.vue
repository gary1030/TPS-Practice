<template>
  <div>
    <good-form-by-id @look-up-product="searchProduct"></good-form-by-id>
    <good-form-by-list
      @look-up-product="searchProduct"
      v-bind:products="products"
    ></good-form-by-list>

    <h3>{{ cur_product.name }} 的所有交易</h3>
    <ul>
      <li v-for="tran in trans" :key="tran.id">
        <p>
          Transaction Date: {{ tran.transaction_date }}, Buyer:
          {{ tran.member_id }}, Amount: {{ tran.transaction_price }}
        </p>
      </li>
    </ul>

    <h3>依會員分類</h3>
    <ul>
      <li v-for="member in recordByMember" :key="member.id">
        <p>
          Member: {{ member.name }}, Transaction Times: {{ member.trans_cnt }},
          Transaction Amount: {{ member.trans_amount }}
        </p>
      </li>
    </ul>

    <h3>依性別分類</h3>
    <ul>
      <li v-for="gender in recordByGender" :key="gender.gender">
        <p>{{ gender.gender }} 的總消費金額: {{ gender.amount }}</p>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from "axios";
import GoodFormById from "../components/GoodFormById.vue";
import GoodFormByList from "../components/GoodFormByList.vue";

export default {
  components: {
    GoodFormById,
    GoodFormByList,
  },
  data() {
    return {
      trans: [],
      cur_product: { id: 2, name: "B" },
      members: [],
      products: [],
      recordByMember: {},
      recordByGender: {},
      recordByAge: {},
    };
  },
  methods: {
    async searchProduct(productId) {
      console.log("searching: ", productId);
      this.cur_product.id = productId;
      this.cur_product.name = this.products.find(
        (ele) => ele.id === productId
      ).name;

      //call api to get transaction data
      try {
        const res = await axios.get(`http://localhost:3000/transactions`);
        this.trans = res.data;
        console.log(this.trans);
      } catch (e) {
        console.error(e);
      }

      //category by member
      var cntByMember = this.members;
      //console.log(cntByMember)
      cntByMember.forEach((e) => {
        e.trans_cnt = 0;
        e.trans_amount = 0;
      });
      this.trans.forEach((tran) => {
        var index = cntByMember.findIndex((ele) => ele.id === tran.member_id);
        if (index !== -1) {
          cntByMember[index].trans_cnt += 1;
          cntByMember[index].trans_amount += tran.transaction_price;
        }
      });
      this.recordByMember = cntByMember;

      //category by Gender
      var male = 0;
      var female = 0;
      this.trans.forEach((tran) => {
        if (
          this.members.find((ele) => ele.id === tran.member_id).gender ===
          "male"
        ) {
          male += tran.transaction_price;
        } else {
          female += tran.transaction_price;
        }
      });
      this.recordByGender = [
        { gender: "男性", amount: male },
        { gender: "女性", amount: female },
      ];
    },
  },
  async created() {
    try {
      const res = await axios.get(`http://localhost:3000/members`);

      this.members = res.data;
    } catch (e) {
      console.error(e);
    }

    try {
      const res = await axios.get(`http://localhost:3000/goods`);

      this.products = res.data;
    } catch (e) {
      console.error(e);
    }
  },
};
</script>
