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
            field: "member_id",
            width: "3%",
            sortable: false,
          },
          {
            label: "Member Name",
            field: "member_name",
            width: "10%",
            sortable: false,
          },
          {
            label: "Transaction Times",
            field: "transTimes",
            width: "10%",
            sortable: false,
          },
          {
            label: "Transaction Amount",
            field: "transAmount",
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
            field: "member_gender",
            width: "10%",
            sortable: false,
          },
          {
            label: "Times",
            field: "transaction_times",
            width: "10%",
            sortable: false,
          },
          {
            label: "Amount",
            field: "transaction_amount",
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
            field: "ageLevel",
            width: "10%",
            sortable: false,
          },
          {
            label: "Times",
            field: "transTimes",
            width: "10%",
            sortable: false,
          },
          {
            label: "Amount",
            field: "transAmount",
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
      //console.log("searching: ", productId);
      var product = this.products.find((ele) => ele.product_id === productId);
      //console.log(this.products.find((ele) => ele.product_id === productId));
      if (!product) {
        alert("ID not Exists!");
        return;
      }
      this.cur_product = {
        id: productId,
        name: product.product_name,
      };

      //call api to get transaction data
      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getProductAllTrans",
          params: productId,
        });
        this.trans = res.data;
        this.table.rows = res.data;
        this.table.totalRecordCount = res.data.length;
        //console.log("Data: ", res.data);
      } catch (e) {
        console.error(e);
      }

      //category by member
      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getProductByMember",
          params: productId,
        });
        var tempData = res.data;
        tempData.forEach((data, i) => {
          //console.log(i);
          var index = this.members.findIndex(
            (ele) => ele.member_id === data.member_id
          );
          if (index !== -1) {
            tempData[i].member_name = this.members[index].member_name;
          }
        });
        this.table2.rows = tempData;
        this.recordByMember = tempData;
        this.table2.totalRecordCount = tempData.length;
      } catch (e) {
        console.error(e);
      }

      //category by Gender
      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getProductByGender",
          params: productId,
        });
        this.recordByGender = res.data;
        this.table3.rows = res.data;
        this.table3.totalRecordCount = res.data.length;
      } catch (e) {
        console.error(e);
      }

      //category by Age
      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getProductByAge",
          params: productId,
        });
        this.recordByAge.rows = res.data;
        this.recordByAge.totalRecordCount = res.data.length;
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
