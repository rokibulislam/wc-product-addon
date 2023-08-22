<template>
    <div class="panel-field">
        <label>{{ field.title }}</label>
        <el-color-picker v-model="value"></el-color-picker>
    </div>
</template>

<script>
import option_field from "../../mixin/option-field.js";
export default {
    name: "field_color",
    mixins: [option_field],
    computed: {
    value: {
      get: function() {
        let property = this.field.name;
        console.log(property);
        return this.editfield[property];
      },
      set: function(value) {
        console.log("color");
        console.log(value);
        this.$store.dispatch("update_editing_form_field", {
          id: this.editfield.id,
          property: this.field.name,
          value: value
        });
      }
    }
  }
}
</script>