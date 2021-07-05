<template>
  <div>
    <!-- @ 是 v-on 的縮寫 -->

    <!-- <form @submit.prevent="onSubmitByList"></form> -->
    <form @submit.prevent="onSubmitById">
      <h3>
        <label for="look-up-by-id"> Please Enter Member Id </label>
      </h3>
      <input
        type="text"
        id="look-up-by-id"
        name="id"
        autocomplete="off"
        v-model.lazy.trim="id"
      />
      <button type="submit">Look Up</button>
    </form>
  </div>

  <div class="Member List">
    <h3 v-text='title'></h3>
    <ul>
      <li v-for="member of members" :key="member.id">
        {{member.name}}
        <button>search</button>
      </li>
    </ul>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  name: 'Member List',
  methods: {
    // onSubmitByList() {
    //   console.log("Look Up by List");
    // },
    onSubmitById() {
      console.log("Look Up by Id");
      if (this.id === "") {
        return;
      }
      this.$emit("look-up-id", this.id);
      this.id = "";
    },
  },
  data() {
    return {
      title: 'Member List', //標題名稱
      members: []
    };
  },
  async created() {
    try{
      const res = await axios.get(`http://localhost:3000/members`);

      this.members = res.data;
    } catch (e) {
      console.error(e);
    }
  }
};
</script>

<style>
li {
  list-style: none;
}
</style>
