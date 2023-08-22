<template>
    <div class="contactum-fields">
      <a class="btn-image" href="#">{{ 'Select Files' }}</a>
     <span v-if="field.help" class="help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_file_field",
  mixins: [form_field]
};
</script>
