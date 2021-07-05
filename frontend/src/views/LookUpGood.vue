<template>
  <div>
    <good-form-by-id @look-up-product="searchProduct"></good-form-by-id>
    <good-form-by-list
      @look-up-product="searchProduct"
      v-bind:products="products"
    ></good-form-by-list>

    <h3>{{ cur_product.name }} 商品的所有交易</h3>
    <table-lite
      :columns="table.columns"
      :rows="table.rows"
      :sortable="table.sortable"
      :messages="table.messages"
      @do-search="doSearch"
    ></table-lite>

    <!-- <ul>
      <li v-for="tran in trans" :key="tran.id">
        <p>
          Transaction Date: {{ tran.transaction_date }}, Buyer:
          {{ tran.member_id }}, Amount: {{ tran.transaction_price }}
        </p>
      </li>
    </ul> -->

    <h3>依會員分類</h3>
    <table-lite
      :columns="table2.columns"
      :rows="table2.rows"
      :messages="table2.messages"
    ></table-lite>
    <!-- <ul>
      <li v-for="member in recordByMember" :key="member.id">
        <p>
          Member: {{ member.name }}, Transaction Times: {{ member.trans_cnt }},
          Transaction Amount: {{ member.trans_amount }}
        </p>
      </li>
    </ul> -->

    <h3>依性別分類</h3>
    <table-lite
      :columns="table3.columns"
      :rows="table3.rows"
      :total="table.totalRecordCount"
      :messages="table3.messages"
    ></table-lite>
    <!-- <ul>
      <li v-for="gender in recordByGender" :key="gender.gender">
        <p>{{ gender.gender }} 的總消費金額: {{ gender.amount }}</p>
      </li>
    </ul> -->

    <h3>依年齡分類</h3>
    <table-lite
      :columns="recordByAge.columns"
      :rows="recordByAge.rows"
      :messages="recordByAge.messages"
    ></table-lite>
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
      cur_product: {},
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
        messages: {
          pagingInfo: "",
          pageSizeChangeLabel: "Row count:",
          gotoPageLabel: "Go to page:",
          noDataAvailable: "No data",
        },
      },
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
        this.table.rows = res.data;
        //console.log(this.trans);
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
      this.table2.rows = cntByMember;

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
      this.table3.rows = this.recordByGender;

      //category by Age

      this.recordByAge.rows = [
        { age: "20歲以下", amount: 100 },
        { age: "21~30", amount: 100 },
        { age: "31~40", amount: 100 },
        { age: "41~50", amount: 100 },
        { age: "51~60", amount: 100 },
        { age: "61歲以上", amount: 100 },
      ];
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
