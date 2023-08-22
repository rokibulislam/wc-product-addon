<template>
  <div class="home">
      <Builder />
  </div>
</template>

<script>
import Builder from "../components/builder/index.vue";
import form_settings from "../components/form-settings/index.vue";

export default {
  name: "Home",
  components: {
    Builder,
    form_settings
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
.tab-content {
    display: flex;
    flex-basis: 100%;
}
</style>
