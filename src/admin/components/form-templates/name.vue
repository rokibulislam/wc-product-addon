<template>
  <div class="contactum-fields" >
    <!-- <div class="name-container"> -->
      <div class="first-name" v-if="field.first_name.show_field">
            <label v-if="!field.hide_subs" class="contactum-label">  {{ field.first_name.label }} </label>
            <span v-if="field.first_name.required && 'true' == field.first_name.required" class="required"> * </span>
            <el-input
              type="text"
              :placeholder="field.first_name.placeholder"
              :value="field.first_name.default"
              :size="field.size"
              disabled
            > </el-input>
      </div>

      <div class="middle-name" v-if="field.middle_name.show_field">
        <label v-if="!field.hide_subs" class="contactum-label"> {{ field.middle_name.label }} </label>
        <span v-if="field.middle_name.required && 'true' == field.middle_name.required" class="required"> * </span>
        <el-input
          type="text"
          :placeholder="field.middle_name.placeholder"
          :value="field.middle_name.default"
          :size="field.size"
          disabled
        > </el-input>
      </div>

      <div class="last-name" v-if="field.last_name.show_field">
        <label v-if="!field.hide_subs" class="contactum-label"> {{ field.last_name.label }} </label>
        <span v-if="field.last_name.required && 'true' == field.last_name.required" class="required"> * </span>
        <el-input
          type="text"
          :placeholder="field.last_name.placeholder"
          :value="field.last_name.default"
          :size="field.size"
          disabled
        > </el-input>
        <!-- <input
          type="text"
          :placeholder="field.last_name.placeholder"
          :value="field.last_name.default"
          :size="field.size"
          disabled
        /> -->
        <!-- <label v-if="!field.hide_subs">{{ field.last_name.sub }} </label> -->
      </div>
    <!-- </div> -->
      <span v-if="field.help">{{ field.help }}</span>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
  name: "form_name_field",
  mixins: [form_field]
};
</script>

<style>
.name-container {
  display: flex;
  gap: 15px;
}
</style>