<template>
  <div>
    <client-form-by-id @look-up-member="searchMember"></client-form-by-id>
    <client-form-by-list @look-up-member="searchMember"></client-form-by-list>
  </div>
</template>

<script>
import axios from "axios";
import ClientFormById from "../components/ClientFormById.vue";
import ClientFormByList from "../components/ClientFormByList.vue";
export default {
  components: {
    ClientFormById,
    ClientFormByList,
  },
  data() {
    return {
      trans: [
        {
          transaction_id: 1,
          transaction_date: 20210704,
          member_id: 1,
          product_id: 2,
          transaction_price: 100,
        },
        {
          transaction_id: 2,
          transaction_date: 20210705,
          member_id: 2,
          product_id: 2,
          transaction_price: 200,
        },
        {
          transaction_id: 3,
          transaction_date: 20210706,
          member_id: 1,
          product_id: 1,
          transaction_price: 300,
        },
        {
          transaction_id: 4,
          transaction_date: 20210707,
          member_id: 3,
          product_id: 2,
          transaction_price: 500,
        },
        {
          transaction_id: 5,
          transaction_date: 20210708,
          member_id: 1,
          product_id: 3,
          transaction_price: 100,
        },
        {
          transaction_id: 6,
          transaction_date: 20210709,
          member_id: 5,
          product_id: 3,
          transaction_price: 200,
        },
        {
          transaction_id: 7,
          transaction_date: 20210710,
          member_id: 1,
          product_id: 1,
          transaction_price: 300,
        },
        {
          transaction_id: 8,
          transaction_date: 20210711,
          member_id: 1,
          product_id: 2,
          transaction_price: 100,
        },
      ],
      members: [],
      products: [],
      cur_member: { id: 1, name: "Ann" },
    };
  },
  methods: {
    searchMember(memberId) {
      console.log("searching: ", memberId);
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

