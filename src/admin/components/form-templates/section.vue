<template>
  <div class="contactum-fields">
    <div>{{ field.description }}</div>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_section_break",
  mixins: [form_field]
};
</script>

<style>
	.section_break > .contactum-fields {
	  border-bottom: 1px solid #ccc;
	}
</style>