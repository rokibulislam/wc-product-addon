<template>
  <div>
    <el-input       
      :class="class_names('textfield')"
      type="tel"
      :placeholder="field.placeholder"
      :value="field.default"
      :size="field.size"
      disabled
    > 
    </el-input>
    <!-- <input type="tel" :placeholder="field.placeholder" :value="field.default" :size="field.size" /> -->
    <span v-if="field.help">{{ field.help }}</span>
  </div>
</template>

<script type="text/javascript">
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_phone_field",
  mixins: [form_field],
};
</script>
