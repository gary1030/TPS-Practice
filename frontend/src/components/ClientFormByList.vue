<template>
  <div>
    <h3>Choose Member By List</h3>
    <select v-model="selected_p">
      <option
        v-for="member in members"
        v-bind:key="member.member_id"
        v-bind:value="member.member_id"
      >
        {{ member.member_name }}
      </option>
    </select>

    <!-- <div>選擇的會員為: {{ selected_p }}</div> -->
    <form @submit.prevent="onSubmitByList">
      <button type="submit">Search</button>
    </form>
  </div>
</template>
<script>

export default {
  name: "Member List",
  props: {
    members: { required: false, type: Array },
  },
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
