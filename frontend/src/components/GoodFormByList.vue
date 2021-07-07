<template>
  <div>
    <h3>Choose Product By List</h3>
    <select v-model="selected_p">
      <option
        v-for="product in products"
        v-bind:key="product.product_id"
        v-bind:value="product.product_id"
      >
        {{ product.product_name }}
      </option>
    </select>

    <form @submit.prevent="onSubmitByList">
      <button type="submit">Search</button>
    </form>
  </div>
</template>
<script>
//import axios from "axios";

export default {
  name: "Product List",
  props: {
    products: { required: false, type: Array },
  },
  methods: {
    onSubmitByList() {
      //console.log("Look Up by List");
      if (this.selected_p === "") {
        return;
      }
      this.$emit("look-up-product", this.selected_p);
    },
  },
  data() {
    return {
      title: "Product List", //標題名稱
      //products: [],
      selected_p: "",
    };
  },
};
</script>

<style>
li {
  list-style: none;
}
</style>
