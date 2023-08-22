<template>
  <div class="panel-field panel-field-date contactum-fields">
    <el-input
      type="date"
      :placeholder="field.format"
      :value="field.format"
      :size="field.size"
      disabled
    > </el-input>
    <span v-if="field.help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_date_field",
  mixins: [form_field]
};
</script>
