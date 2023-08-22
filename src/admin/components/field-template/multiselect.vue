<template>
  <div class="panel-field">
    <!-- <select v-model="value" multiple>
      <option v-for="(option, key) in field.options" :value="key">{{ option }}</option>
    </select> -->
    <el-select v-model="value" multiple>
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
  name: "field_multiselect",
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
