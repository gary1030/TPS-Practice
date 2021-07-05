<template>
  <div>
    <!-- @ 是 v-on 的縮寫 -->
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
</template>
<script>
export default {
  methods: {
    onSubmitById() {
      console.log("Look Up by Id");
      if (this.id === "") {
        return;
      }
      this.$emit("look-up-member", this.id);
      this.id = "";
    },
  },
};
</script>

<style>
li {
  list-style: none;
}
</style>
