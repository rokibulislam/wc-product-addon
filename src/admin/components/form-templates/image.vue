<template>
  <div class="wcprafe-fields">
      <a class="btn-image" href="#">
        <template v-if="field.button_label === ''">{{ 'Select Image' }}</template>
        <template v-else>{{ field.button_label }}</template>
      </a>
        <span v-if="field.help" class="help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_image_field",
  mixins: [form_field]
};
</script>
