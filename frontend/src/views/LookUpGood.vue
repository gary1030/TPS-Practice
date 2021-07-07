<template>
  <div>
    <good-form-by-id @look-up-product="searchProduct"></good-form-by-id>
    <good-form-by-list
      @look-up-product="searchProduct"
      v-bind:products="products"
    ></good-form-by-list>

    <input type="checkbox" id="all" value="all" v-model="showingAllTrans" />
    <label for="all">所有交易紀錄</label>
    <input
      type="checkbox"
      id="member"
      value="member"
      v-model="showingMemberTrans"
    />
    <label for="member">依會員分類 </label>
    <input
      type="checkbox"
      id="gender"
      value="gender"
      v-model="showingGenderTrans"
    />
    <label for="gender">依性別分類</label>
    <input type="checkbox" id="age" value="age" v-model="showingAgeTrans" />
    <label for="age">依年齡分類</label>

    <h3 v-if="cur_product">{{ cur_product.name }} 商品</h3>

    <div v-if="showingAllTrans">
      <h3>所有交易紀錄</h3>
      <table-lite
        :columns="table.columns"
        :rows="table.rows"
        :sortable="table.sortable"
        :messages="table.messages"
        :total="table.totalRecordCount"
        @do-search="doSearch"
      ></table-lite>
    </div>

    <div v-if="showingMemberTrans">
      <h3>依會員分類</h3>
      <table-lite
        :columns="table2.columns"
        :rows="table2.rows"
        :total="table2.totalRecordCount"
        :messages="table2.messages"
      ></table-lite>
    </div>

    <div v-if="showingGenderTrans">
      <h3>依性別分類</h3>
      <table-lite
        :columns="table3.columns"
        :rows="table3.rows"
        :total="table3.totalRecordCount"
        :messages="table3.messages"
      ></table-lite>
    </div>

    <div v-if="showingAgeTrans">
      <h3>依年齡分類</h3>
      <table-lite
        :columns="recordByAge.columns"
        :rows="recordByAge.rows"
        :total="recordByAge.totalRecordCount"
        :messages="recordByAge.messages"
      ></table-lite>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import TableLite from "vue3-table-lite";
import GoodFormById from "../components/GoodFormById.vue";
import GoodFormByList from "../components/GoodFormByList.vue";

export default {
  components: {
    GoodFormById,
    GoodFormByList,
    TableLite,
  },
  data() {
    return {
      table: {
        isLoading: false,
        isReSearch: false,
        columns: [
          {
            label: "ID",
            field: "transaction_id",
            width: "3%",
            sortable: false,
            isKey: true,
          },
          {
            label: "Transaction Date",
            field: "transaction_date",
            width: "15%",
            sortable: false,
          },
          {
            label: "Member ID",
            field: "member_id",
            width: "10%",
            sortable: false,
          },
          {
            label: "Transaction Price",
            field: "transaction_price",
            width: "15%",
            sortable: false,
          },
        ],
        rows: [],
        totalRecordCount: 0,
        sortable: {
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
      trans: [],
      cur_product: null,
      members: [],
      products: [],
      table2: {
        columns: [
          {
            label: "Member ID",
            field: "id",
            width: "3%",
            sortable: false,
          },
          {
            label: "Member Name",
            field: "name",
            width: "10%",
            sortable: false,
          },
          {
            label: "Transaction Times",
            field: "trans_cnt",
            width: "10%",
            sortable: false,
          },
          {
            label: "Transaction Amount",
            field: "trans_amount",
            width: "10%",
            sortable: false,
          },
        ],
        rows: [],
        totalRecordCount: 0,
        messages: {
          pagingInfo: "",
          pageSizeChangeLabel: "Row count:",
          gotoPageLabel: "Go to page:",
          noDataAvailable: "No data",
        },
      },
      table3: {
        columns: [
          {
            label: "Gender",
            field: "gender",
            width: "10%",
            sortable: false,
          },
          {
            label: "Amount",
            field: "amount",
            width: "10%",
            sortable: false,
          },
        ],
        rows: [],
        totalRecordCount: 2,
        messages: {
          pagingInfo: "",
          pageSizeChangeLabel: "Row count:",
          gotoPageLabel: "Go to page:",
          noDataAvailable: "No data",
        },
      },
      recordByMember: {},
      recordByGender: {},
      recordByAge: {
        columns: [
          {
            label: "Age",
            field: "age",
            width: "10%",
            sortable: false,
          },
          {
            label: "Amount",
            field: "amount",
            width: "10%",
            sortable: false,
          },
        ],
        rows: [],
        totalRecordCount: 0,
        messages: {
          pagingInfo: "",
          pageSizeChangeLabel: "Row count:",
          gotoPageLabel: "Go to page:",
          noDataAvailable: "No data",
        },
      },
      showingAllTrans: true,
      showingMemberTrans: true,
      showingAgeTrans: false,
      showingGenderTrans: false,
    };
  },
  methods: {
    async searchProduct(productId) {
      console.log("searching: ", productId);
      this.cur_product = {
        id: productId,
        name: this.products.find((ele) => ele.id === productId).name,
      };

      //call api to get transaction data
      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getProductAllTrans",
          params: productId,
        });
        this.trans = res.data;
        this.table.rows = res.data;
        console.log("Data: ", res.data);
      } catch (e) {
        console.error(e);
      }

      //category by member
      // var cntByMember = this.members;
      // //console.log(cntByMember)
      // cntByMember.forEach((e) => {
      //   e.trans_cnt = 0;
      //   e.trans_amount = 0;
      // });
      // this.trans.forEach((tran) => {
      //   var index = cntByMember.findIndex((ele) => ele.id === tran.member_id);
      //   if (index !== -1) {
      //     cntByMember[index].trans_cnt += 1;
      //     cntByMember[index].trans_amount += tran.transaction_price;
      //   }
      // });
      // this.recordByMember = cntByMember;
      // this.table2.rows = cntByMember;
      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getProductByMember",
          params: productId,
        });
        this.recordByMember = res.data;
        this.table2.rows = res.data;
      } catch (e) {
        console.error(e);
      }

      //category by Gender
      // var male = 0;
      // var female = 0;
      // this.trans.forEach((tran) => {
      //   if (
      //     this.members.find((ele) => ele.id === tran.member_id).gender ===
      //     "male"
      //   ) {
      //     male += tran.transaction_price;
      //   } else {
      //     female += tran.transaction_price;
      //   }
      // });
      // this.recordByGender = [
      //   { gender: "男性", amount: male },
      //   { gender: "女性", amount: female },
      // ];
      // this.table3.rows = this.recordByGender;
      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getProductByGender",
          params: productId,
        });
        this.recordByGender = res.data;
        this.table3.rows = res.data;
      } catch (e) {
        console.error(e);
      }

      //category by Age
      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getProductByAge",
          params: productId,
        });
        this.recordByAge = res.data;
      } catch (e) {
        console.error(e);
      }
    },
    doSearch(offset, limit, order, sort) {
      this.table.isLoading = true;
      this.table.isReSearch = offset == undefined ? true : false;
      // do your search event to get newRows and new Total
      this.table.sortable.order = order;
      this.table.sortable.sort = sort;
    },
  },
  // computed: {
  //   hasSelected() {
  //     console.log(this.cur_product);
  //     if (this.cur_product === {}) {
  //       return false;
  //     }
  //     return true;
  //   },
  // },
  async created() {
    try {
      const res = await axios.post("http://localhost:8888/connect.php", {
        action: "getAllMembers",
      });

      this.members = res.data;
    } catch (e) {
      console.error(e);
    }

    try {
      const res = await axios.post("http://localhost:8888/connect.php", {
        action: "getAllProducts",
      });

      this.products = res.data;
    } catch (e) {
      console.error(e);
    }
  },
};
</script>
