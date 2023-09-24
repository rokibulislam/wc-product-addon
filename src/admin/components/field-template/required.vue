<template>
    <div v-if="hasvalidate(field)">
      <label class="wcprafe-label">{{ field.title }}</label>
      <ul class="list-inline">
        <li v-for="(option, key) in field.options">
          <label>
            <el-radio :label="key" v-model="value"> {{  option }} </el-radio>
          </label>
        </li>
      </ul>
      <div v-if=" value === 'yes' "> 
        <label class="wcprafe-label"> Error Message: </label>
        <el-input v-model="editfield.message"></el-input>
      </div>
    </div>
</template>

<script>
import option_field from "../../mixin/option-field.js";
export default {
  name: "field_required",
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
    message: {
      get: function() {
        return this.editfield;
      },
      set: function(value) {
        console.log(value);
      }
    }
  },
  methods: {
    hasvalidate( field ) {
        if( field.hasOwnProperty('show_if') ) {
            return this[field.show_if]();
        }

        return true;
    }
  }
};
</script>
