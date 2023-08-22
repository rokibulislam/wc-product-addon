<template>
  <div class="contactum-fields">
    <el-input
      type="url"
      :placeholder="field.placeholder"
      :value="field.default"
      :size="field.size"
      disabled
    > </el-input>
    <!-- <input
      type="url"
      :placeholder="field.placeholder"
      :value="field.default"
      :size="field.size"
      disabled
    /> -->
     <span v-if="field.help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_url_field",
  mixins: [form_field]
};
</script>
