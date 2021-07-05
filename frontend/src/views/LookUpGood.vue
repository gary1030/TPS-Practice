<template>
  <div>
    <good-form-by-id></good-form-by-id>
    <good-form-by-list></good-form-by-list>
  </div>
</template>

<script>
import GoodFormById from "../components/GoodFormById.vue";
import GoodFormByList from "../components/ClientFormByList.vue";

export default {
  components: {
    GoodFormById,
    GoodFormByList,
  },
};
</script>
