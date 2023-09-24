<template>
  <div class="wcprafe-fields">
    <el-input
      type="email"
      :placeholder="field.placeholder"
      :value="field.default"
      :size="field.size"
      disabled
    > </el-input>
    <!-- <input
      type="email"
      :placeholder="field.placeholder"
      :value="field.default"
      :size="field.size"
      disabled
    /> -->
    <span v-if="field.help" class="help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form-email_address",
  mixins: [form_field]
};
</script>
