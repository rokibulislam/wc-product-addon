<template>
  <div class="prafe-fields">
    <el-select :class="class_names('select')" disabled :style="{  'width': '100%' }">
      <el-option
        v-for="(option, key) in field.options"
        :key="key"
        :label="option"
        :value="key">
      </el-option>
    </el-select>
    <!-- <select  :class="class_names('select')" disabled>
      <option v-if="field.first" value>{{ field.first }}</option>
      <option v-if="field.first" value="">{{ field.first }}</option>
      <option
        v-for="(label,value) in field.options"
        :value="value"
        :key="value"
        :selected="is_selected(value)"
      >{{ label }}</option>
    </select> -->
    <span v-if="field.help" class="help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_dropdown_field",
  mixins: [form_field]
};
</script>
