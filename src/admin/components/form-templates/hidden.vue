<template>
  <div class="prafe-fields">
    <p> key: {{ field.name }} </p>
    <p> value: {{ field.meta_value }} </p>
    <span v-if="field.help" v-html="field.help" />
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_hidden_field",
  mixins: [form_field]
};
</script>
