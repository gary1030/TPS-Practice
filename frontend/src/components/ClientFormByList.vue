<template>
  <div>
    <h3>Choose Member By List</h3>
    <select v-model="selected_p">
      <option
        v-for="member in members"
        v-bind:key="member.id"
        v-bind:value="member.id"
      >
        {{ member.name }}
      </option>
    </select>

    <!-- <div>選擇的會員為: {{ selected_p }}</div> -->
    <form @submit.prevent="onSubmitByList">
      <button type="submit">Search</button>
    </form>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "Member List",
  methods: {
    onSubmitByList() {
      console.log("Look Up by List");
      if (this.selected_p === "") {
        return;
      }
      this.$emit("look-up-member", this.selected_p);
    },
  },
  data() {
    return {
      title: "Member List", //標題名稱
      members: [],
      selected_p: "",
    };
  },
  async created() {
    try {
      const res = await axios.get(`http://localhost:3000/members`);

      this.members = res.data;
    } catch (e) {
      console.error(e);
    }
  },
};
</script>

<style>
li {
  list-style: none;
}
</style>
