<template>
  <div>
    <client-form-by-id @look-up-member="searchMember"></client-form-by-id>
    <client-form-by-list
      @look-up-member="searchMember"
      v-bind:members="members"
    ></client-form-by-list>
    <br />
    <h3 v-if="isSearched">
      {{ cur_member.member_name }}的每日平均花費為:
      {{ this.cur_member_total[0].consumption_per_day }}
    </h3>
    <h3 v-if="isSearched">{{ cur_member.member_name }} 的歷史消費紀錄</h3>
    <table-lite
      :columns="table2.columns"
      :rows="table2.rows"
      :total="table2.record"
      :sortable="table2.sortable"
      :messages="table2.messages"
      @do-search="doSearch"
    ></table-lite>
    <h3 v-if="isSearched">{{ cur_member.member_name }} 的交易列表</h3>
    <table-lite
      :columns="table.columns"
      :rows="table.rows"
      :total="table.record"
      :sortable="table.sortable"
      :messages="table.messages"
      @do-search="doSearch"
    ></table-lite>
  </div>
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
      cur_member: { member_id: "", member_name: "" },
      memberProTrans: [],
      memberAllTrans: [],
      cur_member_total: [],
      cur_member_detail_1: [],
      table: {
        isLoading: false,
        isReSearch: false,
        columns: [
          {
            label: "Product",
            field: "product_name",
            width: "15%",
            sortable: false,
          },
          {
            label: "Times",
            field: "transTimes",
            width: "15%",
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
        record: 0,
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
            field: "product_name",
            width: "10%",
            sortable: false,
          },
          {
            label: "amount",
            field: "transaction_price",
            width: "10%",
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
        record: 0,
      },
    };
  },
  methods: {
    async searchMember(memberId) {
      var exist = this.members.find((ele) => ele.member_id === memberId);
      if (!exist) {
        alert("ID not Exists!");
        return;
      }
      this.cur_member.member_id = memberId;
      this.cur_member.member_name = this.members.find(
        (ele) => ele.member_id === memberId
      ).member_name;

      this.isSearched = true;

      // try to get data from database
      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getAllProducts",
        });
        console.log(res.data);
        this.products = res.data;
      } catch (e) {
        console.error(e);
      }

      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getMemberProTrans",
          params: memberId,
        });
        this.memberProTrans = res.data;
        this.table.record = res.data.length;
      } catch (e) {
        console.error(e);
      }
      this.memberProTrans.forEach((e) => {
        e.product_name = this.products.find(
          (ele) => ele.product_id === e.product_id
        ).product_name;
      });
      this.table.rows = this.memberProTrans;

      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getMemberAllTrans",
          params: memberId,
        });
        this.memberAllTrans = res.data;
        this.table2.record = res.data.length;
      } catch (e) {
        console.error(e);
      }
      this.memberAllTrans.forEach((e) => {
        e.product_name = this.products.find(
          (ele) => ele.product_id === e.product_id
        ).product_name;
      });
      this.table2.rows = this.memberAllTrans;

      try {
        const res = await axios.post("http://localhost:8888/connect.php", {
          action: "getMemberAvgCost",
          params: memberId,
        });

        this.cur_member_total = res.data;
      } catch (e) {
        console.error(e);
      }

      var product_trans = this.products;
      product_trans.forEach((e) => {
        e.trans_cnt = 0;
        e.trans_amount = 0;
      });
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
    total: function () {
      return this.trans.reduce(function (total, tran) {
        return total + tran.transaction_price;
      }, 0);
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
  },
};
</script>

<style type="text/css">
#container {
  margin: 0 auto;
  width: 15%;
}
</style>

