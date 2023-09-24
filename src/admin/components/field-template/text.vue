<template>
  <div class="panel-field">
    <label class="wcprafe-label">
      {{ field.title }}
      <!-- <input type="text" v-model="value" @focusout="onfocusout" @keyup="onkeyup" /> -->
    </label>
    <el-input type="text" v-model="value" @focusout="onfocusout" @keyup="onkeyup"> </el-input>
</div>
</template>
<script>
import option_field from "../../mixin/option-field.js";
export default {
  name: "field_text",
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
  },
  methods: {
    onfocusout: function(e) {
      this.$eventHub.$emit("field-text-focusout", this.field, this.value);
    },
    onkeyup: function(e) {
      this.$eventHub.$emit("field-text-keyup", this.field, this.value);
    }
  }
};
</script>