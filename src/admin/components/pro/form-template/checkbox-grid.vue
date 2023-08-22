<template>
  <div>
    <table>
      <tr>
        <th></th>
        <th
          v-for="(column_label, column_val) in field.grid_columns"
          :key="column_val"
        >{{ column_label }}</th>
      </tr>
      <tr v-for="(row_label, row_val) in field.grid_rows" :key="row_val">
        <th>{{ row_label }}</th>
        <td v-for="(column_label, column_val) in field.grid_columns" :key="column_val">
          <input type="checkbox" name />
        </td>
      </tr>
    </table>

    <span v-if="field.help" class="help">{{ field.help }}</span>
  </div>
</template>

<script>
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_checkbox_grid",
  mixins: [form_field],
};
</script>
