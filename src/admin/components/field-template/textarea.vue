<template>
  <div class="panel-field">
    <label class="prafe-label"> {{ field.title }}
        <!-- <textarea :rows="field.rows || 5" v-model="value"></textarea> -->
    </label>
    <el-input
        type="textarea"
        :rows="field.rows || 5"
        v-model="value"
      >
      </el-input>
  </div>
</template>

<script>
import option_field from "../../mixin/option-field.js";
export default {
  name: "field_textarea",
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