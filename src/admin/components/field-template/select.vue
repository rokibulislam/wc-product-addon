<template>
  <div class="panel-field-opt panel-field-opt-select">
    <label class="contactum-label"> {{ field.title }} </label>
    <el-select v-model="value" :style="{width: '100%'}">
      <el-option
        v-for="(option, key) in field.options"
        :key="key"
        :label="option"
        :value="key">
      </el-option>
    </el-select>
  </div>
</template>

<script>
import option_field from "../../mixin/option-field.js";
export default {
  name: "field_select",
  mixins: [option_field],
  computed: {
    value: {
      get: function() {
        let property = this.field.name;
        return this.editfield[property];
      },
      set: function(value) {
        this.$store.dispatch("update_editing_form_field", {
          id: this.editfield.id,
          property: this.field.name,
          value: value
        });
      }
    }
  }
};
</script>
