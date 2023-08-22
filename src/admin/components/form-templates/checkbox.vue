<template>
  <div class="userregistration-fields">
    <ul :class="['inline' === field.layout ? 'show-inline' : '']">
      <li v-for="option in field.options" :class='{ "image_type": option.photo != ""}'>
        <!-- <el-checkbox :label="option.value" :checked="is_selected(option.value)"> {{ option.label }} </el-checkbox> -->
        <label>
          <input
            type="checkbox"
            :value="value"
            :checked="is_selected(option.value)"
          />
          <img v-if="option.photo != ''" :src="option.photo" width="200px" />
          <span v-else-if="option.photo == ''">{{ option.label }}</span>
        </label>
      </li>
    </ul>
    <span v-if="field.help" class="help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_checkbox_field",
  mixins: [form_field],
};
</script>
