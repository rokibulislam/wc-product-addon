<template>
  <div class="contactum-fields">
    <el-input       
      :class="class_names('textfield')"
      type="textarea"
      :placeholder="field.placeholder"
      :deault="field.default"
      :rows="field.rows"
      :cols="field.cols"
      disabled
    >  </el-input>
    <!-- <textarea
      :placeholder="field.placeholder"
      :deault="field.default"
      :rows="field.rows"
      :cols="field.cols"
      disabled
    > {{ field.default }} </textarea> -->
    <span v-if="field.help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_textarea_field",
  mixins: [form_field]
};
</script>
