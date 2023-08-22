<template>
  <div>
    <div>
      <label>{{ 'Payment Methods' }}</label>
      <ul>
        <li>
          <label>
            <input type="checkbox" value="1" v-model="paypal" />
            {{ 'paypal'}}
          </label>
        </li>
        <li>
          <label>
            <input type="checkbox" value="1" v-model="stripe" />
            {{ 'stripe'}}
          </label>
        </li>
      </ul>
    </div>

    <div>
      <ul>
        <li>
          <label>
            <input type="text" v-model="paypal_label" />
            {{ 'paypal'}}
          </label>
        </li>
        <li>
          <label>
            <input type="text" v-model="stripe_label" />
            {{ 'stripe'}}
          </label>
        </li>
      </ul>
    </div>

    <span v-if="field.help" class="help">{{ field.help }}</span>
  </div>
</template>

<script>
import option_field from "../../../mixin/option-field.js";
export default {
  name: "field_payment_method",
  mixins: [option_field],
  data: function() {
    return {
      paypal: false,
      stripe: false,
      card_images: [],
      paypal_images: false,
      paypal_label: "Paypal",
      stripe_label: "Stripe"
    };
  },
  computed: {},
  created: function() {},
  watch: {
    paypal: function(value) {},
    stripe: function(value) {},
    card_image: function(value) {},
    stripe_label: function(value) {},
    paypal_label: function(value) {},
    paypal_image: function(value) {}
  }
};
</script>
