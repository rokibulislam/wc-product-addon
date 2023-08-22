<template>
  <div class="fields">
    <label>{{ field.price }}</label>
    <span v-if="field.help" class="help">{{ field.help }}</span>
  </div>
</template>

<script>
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_total",
  mixins: [form_field],
};
</script>
