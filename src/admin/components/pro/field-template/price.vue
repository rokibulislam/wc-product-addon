<template>
  <div>
    <template v-if="price.is_flexible">
      <label class="contactum-label">  {{ 'Minimum Price'}} </label>
      <!-- <input type="number" v-model="price.min" /> -->
      <el-input-number  v-model="price.min" />
      <label class="contactum-label"> {{ 'Maximum Price'}} </label>
      <el-input-number  v-model="price.max" />
      <!-- <input type="number" v-model="price.max" /> -->
    </template>
    <template v-else>
      <div class="panel">
        <label class="contactum-label"> {{ 'price' }} </label>
        <el-input-number v-model="price.price" />
        <!-- <input type="number" v-model="price.price" /> -->
      </div>
    </template>
    <div class="panel-field">
      <el-checkbox v-model="price.is_flexible"></el-checkbox>
      <!-- <input type="checkbox" v-model="price.is_flexible" /> -->
      <label class="contactum-label"> {{ ' Use flexible price' }} </label>
    </div>
  </div>
</template>

<script>
import option_field from "../../../mixin/option-field.js";
export default {
  name: "field_price",
  mixins: [option_field],
  data: function() {
    return {
      price: {
        min: "",
        max: "",
        price: "",
        is_flexible: false
      }
    };
  },
  computed: {
    // price: function() {
    //   return this.editfield.price;
    // },
  },
  watch: {
    price: function() {}
  }
};
</script>
