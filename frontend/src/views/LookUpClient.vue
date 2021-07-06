<template>
  <div>
    <client-form-by-id @look-up-member="searchMember"></client-form-by-id>
    <client-form-by-list 
      @look-up-member="searchMember"
      v-bind:members="members"
    ></client-form-by-list>
  </div>
  <br/>
  <h3 v-if="isSearched">{{cur_member.name}} 的歷史消費紀錄</h3>
  <table-lite
      :columns="table2.columns"
      :rows="table2.rows"
      :total="record"
      :sortable="table2.sortable"
      :messages="table2.messages"
      @do-search="doSearch"
  ></table-lite>
  <h3 v-if="isSearched">{{ cur_member.name }} 的交易列表</h3>
  <table-lite
      :columns="table.columns"
      :rows="table.rows"
      :total="record"
      :sortable="table.sortable"
      :messages="table.messages"
      @do-search="doSearch"
  ></table-lite>
  <h3 v-if="isSearched">{{cur_member.name}}的總花費為: {{this.cur_total}}</h3>



</template>

<script>
import axios from "axios";
import ClientFormById from "../components/ClientFormById.vue";
import ClientFormByList from "../components/ClientFormByList.vue";
import TableLite from "vue3-table-lite";

export default {
  components: {
    ClientFormById,
    ClientFormByList,
    TableLite,
  },
  data() {
    return {
      trans: [],
      members: [],
      products: [],
      isSearched: false,
      cur_member: {id: "", name: ""},
      cur_trans: [],
      record: [],
      cur_total: '',
      cur_member_detail_1: [],
      table: {
        isLoading: false,
        isReSearch: false,
        columns: [
          {
            label: "Product",
            field: "name",
            width: "15%",
            sortable: false,
          },
          {
            label: "Times",
            field: "trans_cnt",
            width: "15%",
            sortable: false,
          },
          {
            label: "Amount",
            field: "trans_amount",
            width: "10%",
            sortable: false,
          }
        ],
        rows: [],
        sortable:{
          order: "id",
          sort: "asc",
        },
        messages: {
          pagingInfo: "",
          pageSizeChangeLabel: "Row count:",
          gotoPageLabel: "Go to page:",
          noDataAvailable: "No data",
        },
      },
      table2: {
        isLoading: false,
        isReSearch: false,
        columns: [
          {
            label: "id",
            field: "transaction_id",
            width: "15%",
            sortable: false,
          },
          {
            label: "date",
            field: "transaction_date",
            width: "15%",
            sortable: false,
          },
          {
            label: "product",
            field: "name",
            width: "10%",
            sortable: false,
          },
          {
            label: "amount",
            field: "transaction_amount",
            width: "10%",
            sortable: false,
          }
        ],
        rows: [],
        sortable:{
          order: "id",
          sort: "asc",
        },
        messages: {
          pagingInfo: "",
          pageSizeChangeLabel: "Row count:",
          gotoPageLabel: "Go to page:",
          noDataAvailable: "No data",
        },
      }
    };
  },
  methods: {
    async searchMember(memberId) {
      console.log("searching: ", memberId);
      this.cur_member.id = memberId;
      this.cur_member.name = this.members.find(
        (ele) => ele.id === memberId
      ).name;

      this.isSearched = true;
      
      var id = this.cur_member.id;

      // get transaction data
      try {
        const res = await axios.get(`http://localhost:3000/transactions`);
        this.trans = res.data;
      } catch(e) {
        console.error(e);
      }
      
      // get products data
      try {
        const res = await axios.get(`http://localhost:3000/goods`);

        this.products = res.data;
      } catch (e) {
        console.error(e);
      }

      // cur_tran means we only care about trans of current chosen member
      var cur_tran = this.trans.filter(function(item){
        return item.member_id === id;
      });
      // here, we try to change product id to corresponding product name using data.find()
      cur_tran.forEach((e) => {
        e.name = this.products.find((ele) => ele.id === e.product_id).name;
      });


      console.log(cur_tran);
      this.cur_trans = cur_tran;
      this.table2.rows = cur_tran;


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
      this.table.rows = product_trans;
      this.cur_total = temp;
    },
    doSearch(offset, limit, order, sort) {
      this.table.isLoading = true;
      this.table.isReSearch = offset == undefined ? true : false;
      // do your search event to get newRows and new Total
      this.table.sortable.order = order;
      this.table.sortable.sort = sort;
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

