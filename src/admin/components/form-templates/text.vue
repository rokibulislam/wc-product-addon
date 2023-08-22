<template>
  <div class="contactum-fields">
    <el-input       
      :class="class_names('textfield')"
      type="text"
      :placeholder="field.placeholder"
      :value="field.default"
      :size="field.size"
      disabled
    > 
    </el-input>
    <span v-if="field.help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_text_field",
  mixins: [form_field]
};
</script>
