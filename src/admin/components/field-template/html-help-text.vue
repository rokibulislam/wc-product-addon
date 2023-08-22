<template>
  <div class="panel-field">
    <div class="panel-field-opt panel-field-html-help-text" v-html="field.text"></div>
  </div>
</template>
<script>
import option_field from "../../mixin/option-field.js";
export default {
  name: "field_html_help_text",
  mixins: [option_field]
};
</script>
