<template>
  <div>
    <div class="panel-field">
      <label class="contactum-label">{{ 'Scale Range' }}</label>

      <div class="name-field-placeholder">
        <label class="contactum-label">{{ 'From' }}</label>
        <el-input-number v-model="editfield.scale_range.from" />
        <!-- <input type="text" v-model="editfield.scale_range.from" /> -->
      </div>

      <div class="name-field-value">
        <label class="contactum-label">{{ 'To' }}</label>
        <el-input-number v-model="editfield.scale_range.to" />
        <!-- <input type="text" v-model="editfield.scale_range.to" /> -->
      </div>
    </div>

    <div class="panel-field-opt panel-field-name clearfix">
      <label class="contactum-label">{{ 'Scale Text' }}</label>

      <div class="name-field-placeholder">
        <label class="contactum-label">{{ 'First Text' }}</label>
        <el-input-number v-model="editfield.scale_text.first" />
        <!-- <input type="text" v-model="editfield.scale_text.first" /> -->
      </div>

      <div class="name-field-value">
        <label class="contactum-label">{{ 'Last Text' }}</label>
        <el-input-number v-model="editfield.scale_text.last" />
        <!-- <input type="text" v-model="editfield.scale_text.last" /> -->
      </div>
    </div>
  </div>
</template>

<script>
import option_field from "../../../mixin/option-field.js";
export default {
  name: "field_linear",
  mixins: [option_field],
  props: {
    field: {
      type: Object,
      default: {}
    }
  },
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
