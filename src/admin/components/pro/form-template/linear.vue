<template>
  <div>
    <ul class="linear_field">
      <li>
        <label class="contactum-label">{{ field.scale_text.first }}</label>
      </li>
      <li v-for="i in linearRange(field.scale_range.from, field.scale_range.to)" :key="i">
        <label>
          <input type="radio" :value="i" />
          {{ i }}
        </label>
      </li>
      <li>
        <label>
          <input type="radio" :value="field.scale_range.to" />
          {{ field.scale_range.to }}
        </label>
      </li>
      <li>
        <label>{{ field.scale_text.last }}</label>
      </li>
    </ul>
    <span v-if="field.help" class="help">{{ field.help }}</span>
  </div>
</template>

<script>
import form_field from "../../../mixin/form-field.js";
import _ from 'lodash';
export default {
  name: "form_linear_scale",
  mixins: [form_field],
  methods: {
    // linearRange: function(start, end) {
    //   if (start === end) return [start];
    //   return [start, ...this.linearRange(start + 1, end)];
    // },
    linearRange: function (from, to) {
      // console.log('ranging');
      // return _.range( parseInt(from), parseInt(to) );
      let start = parseInt(from);
      let end = parseInt(to);
      return Array.from({ length: end - start + 1 }, (_, index) => start + index);
    },
  }
};
</script>


<style scoped>
.linear_field {
  display: flex;
  gap: 15px;
}
</style>