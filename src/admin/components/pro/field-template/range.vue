<template>
    <div class="panel-field">
        <label class="label-hr">{{ field.title }}</label>
        {{ field.min_column }}
        <input
            type="range"
            v-model="value"
            v-bind:min="minColumn"
            v-bind:max="maxColumn"
        >
    </div>
  </template>
  
  <script>
  import option_field from "../../../mixin/option-field.js";
  export default {
    name: "field_range",
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
        },

        minColumn: function () {
            return this.editfield.min_column;
        },

        maxColumn: function () {
            return this.editfield.max_column;
        }
    },
  };
  </script>
  