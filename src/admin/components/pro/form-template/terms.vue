<template>
  <div class="toc-container">
      <span v-if="field.show_checkbox" class="toc-checkbox">
        <input type="checkbox" />
      </span>
      <div class="toc-description" v-html="content"></div>
  </div>
</template>

<script>
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_toc",
  mixins: [form_field],
  computed: {
    content: function() {
      return this.field.description.replace(/\n/g, "<br>");
    }
  }
};
</script>
