<template>
  <div v-html="field.shortcode"></div>
</template>

<script>
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_shortcode_field",
  mixins: [form_field],
};
</script>
