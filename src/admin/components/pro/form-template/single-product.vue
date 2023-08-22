<template>
  <div>
    {{ field }}
    <span v-if="field.help" class="help">{{ field.help }}</span>
  </div>
</template>

<script>
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_single_product",
  mixins: [form_field],
};
</script>
