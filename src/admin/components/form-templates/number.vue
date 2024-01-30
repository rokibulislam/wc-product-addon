<template>
  <div class="prafe-fields">
    <el-input
      type="number"
      :placeholder="field.placeholder"
      :value="field.default"
      :size="field.size"
      disabled
    > </el-input>
    <!-- <input
      type="number"
      :placeholder="field.placeholder"
      :value="field.default"
      :size="field.size"
      disabled
    /> -->
    <span v-if="field.help" v-html="field.help" />
  </div>
</template>

<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_number_field",
  mixins: [form_field]
};
</script>
