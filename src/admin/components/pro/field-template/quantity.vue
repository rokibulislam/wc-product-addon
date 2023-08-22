<template>
  <div>
    <label class="contactum-label"> {{ 'Use Quantity' }} </label>
    <!-- <input type="checkbox" value="true" v-model="quantity.status" /> -->
    <el-checkbox v-model="quantity.status" />
    <div v-if="quantity.status">
      <label class="contactum-label"> {{ 'Minimum Quantity' }} </label>
      <el-input-number v-model="quantity.min" />
      <!-- <input type="number" v-model="quantity.min" /> -->
      <label class="contactum-label"> {{ 'Maximum Quantity' }} </label>
      <el-input-number v-model="quantity.max" />
      <!-- <input type="number" v-model="quantity.max" /> -->
    </div>
  </div>
</template>

<script>
import option_field from "../../../mixin/option-field.js";
export default {
  name: "field_quantity",
  mixins: [option_field],
  data: function() {
    return {
      quantity: {
        status: false,
        min: 0,
        max: ""
      }
    };
  },
  computed: {
    quantity: function() {
      return this.editfield.quantity;
    }
  }
};
</script>
