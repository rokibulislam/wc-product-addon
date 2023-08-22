<template>
    <div class="fields" v-html="field.html"></div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_html_field",
  mixins: [form_field]
};
</script>
