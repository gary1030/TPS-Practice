<template>
  <div>
    <client-form-by-id @look-up-member="searchMember"></client-form-by-id>
    <client-form-by-list 
      @look-up-member="searchMember"
      v-bind:members="members"
    ></client-form-by-list>
  </div>
  <br/>
  <!-- <h3>All Transaction Records</h3>
  <div id='container'>
    <table>
    <thead>
      <tr>
        <th>id</th>
        <th>trans date</th>
        <th>product</th>
        <th>buyer</th>
        <th>price</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(tran, index) in trans" :key="index">
        <td>{{index + 1}}</td>
        <td>{{tran.transaction_date}}</td>
        <td>{{tran.product_id}}</td>
        <td>{{tran.member_id}}</td>
        <td>{{tran.transaction_price}}</td>
      </tr>
    </tbody>
    </table>
  </div> -->
  <!-- <br/> -->
  <!-- <div>Total spent: {{total}}</div> -->
  <h3>{{cur_member.name}}'s Transaction Records</h3>
  <div id='container'>
    <table>
      <thead>
        <tr>
          <th>product</th>
          <th>trans times</th>
          <th>trans amount</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(product, index) in record" :key="index">
          <td>{{product.name}}</td>
          <td>{{product.trans_cnt}}</td>
          <td>{{product.trans_amount}}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <h3>{{cur_member.name}}'s total spent$: {{this.cur_total}}</h3>
  <br/>
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
      trans: [],
      members: [],
      products: [],
      cur_member: { id: 1, name: "Ann" },
      cur_trans: [],
      record: [],
      cur_total: ''
    };
  },
  methods: {
    async searchMember(memberId) {
      console.log("searching: ", memberId);
      this.cur_member.id = memberId;
      this.cur_member.name = this.members.find(
        (ele) => ele.id === memberId
      ).name;
      
      var id = this.cur_member.id;

      // get transaction data
      try {
        const res = await axios.get(`http://localhost:3000/transactions`);
        this.trans = res.data;
      } catch(e) {
        console.error(e);
      }

      // cur_tran means we only care about trans of current chosen member
      var cur_tran = this.trans.filter(function(item){
        return item.member_id === id;
      });
      console.log(cur_tran);
      this.cur_trans = cur_tran;


      var product_trans = this.products;
      product_trans.forEach((e) => {
        e.trans_cnt = 0;
        e.trans_amount = 0;
      });
      
      //temp is used for calculating current member's total spent
      var temp = 0;
      cur_tran.forEach((tran) => {
        var index = product_trans.findIndex((ele) => ele.id === tran.product_id)
        if (index !== -1) {
          product_trans[index].trans_cnt += 1;
          product_trans[index].trans_amount += tran.transaction_price;
          temp = product_trans[index].trans_amount;
        }
      });
      this.record = product_trans;
      this.cur_total = temp;

    },
  },
  computed: {
    total: function(){
      console.log(this.trans);
      return this.trans.reduce(function(total, tran){
        return total + tran.transaction_price;
      }, 0);
    },
    // curTotal: function(){
    //   return this.cur_trans.reduce(function(total, tran){
    //     return total + tran.trans_amount;
    //   })
    // }
  },
  async created() {
    try {
      const res = await axios.get(`http://localhost:3000/transactions`);
      this.trans = res.data;
    } catch(e) {
      console.error(e);
    }
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

<style type="text/css">
#container {
  margin: 0 auto; width: 15%;
}
</style>

