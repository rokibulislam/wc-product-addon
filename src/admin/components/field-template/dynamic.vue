<template>
  <div>
    <label class="contactum-label">{{ 'Dynamic Value' }}</label>
    <input type="checkbox" value="yes" v-model="dynamic.status" />
    <template v-if="editfield.dynamic">
      <label>{{ 'Parameter Name' }}</label>
      <input type="text" v-model="param_name" />
    </template>
  </div>
</template>

<script type="text/javascript">
import option_field from "../../mixin/option-field.js";
export default {
  name: "field_dynamic",
  mixins: [option_field],
  data: function() {
    return {
      dynamic: {
        status: false,
        param_name: ""
      }
    };
  },
  computed: {},
  created: function() {},
  methods: {},
  watch: {
    dynamic: function() {}
  }
};
</script>
