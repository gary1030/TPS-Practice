<template>
  <div>
    <form @submit.prevent="onSubmitById">
      <h3>
        <label for="look-up-by-id"> Please Enter Product Id </label>
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
//import { defineComponent } from '@vue/composition-api'

export default {
  methods: {
    onSubmitById() {
      //console.log("Look Up by Id");
      if (this.id === "") {
        return;
      }
      this.$emit("look-up-product", this.id);
      this.id = "";
    },
  },
  data() {
    return {
      id: "",
      name: "",
    };
  },
};
</script>
