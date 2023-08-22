<template>
  <div class="contactum-fields">
    <ul :class="['inline' === field.layout ? 'show-inline' : '']">
      <li v-for="option in field.options">
        <!-- <el-radio :label="option.value" :checked="is_selected(option.value)"> {{ option.label }} </el-radio> -->
        <label>
          <input
            type="radio"
            :value="option.value"
            :checked="is_selected(value)"
            disabled
          />
          <img v-if="option.photo != ''" :src="option.photo" width="200px" />
          <span v-else-if="option.photo == ''">{{ option.label }}</span>
        </label>
      </li>
    </ul>
    <span v-if="field.help" class="formsuite-help" v-html="field.help"></span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_radio_field",
  mixins: [form_field],
};
</script>
