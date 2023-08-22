<template>
  <div>
    <ul>
      <li v-for="(name, price) in field.options">{{name}} - {{price}}</li>
    </ul>
  </div>
</template>

<script>
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_multiple_product",
  mixins: [form_field],
};
</script>
