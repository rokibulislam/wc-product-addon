<template>
  <div class="contactum-fields">
    <div class="fields">
      <div
        v-for="(addr_field_details, addr_field) in field.address"
        :class="['address-field', addr_field]"
        v-if="addr_field_details.checked"
      >
        <div class="sub-fields">
            <label>
              {{ addr_field_details.label }}
              <span
                v-if="'checked' === addr_field_details.required"
                class="required"
              >*</span>
            </label>
          <template v-if="'country_select' !== addr_field">
            <input
              type="text"
              class="textfield"
              size="40"
              :value="addr_field_details.value"
              :placeholder="addr_field_details.placeholder"
              :required="'checked' === addr_field_details.required"
              disabled
            />
          </template>

          <template v-else>
            <select :required="'checked' === addr_field_details.required" v-model="default_country" disabled>
              <option value>{{ 'Select Country' }}</option>
              <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
            </select>
          </template>
        </div>
      </div>
      <span v-if="field.help" v-html="field.help" />
    </div>
  </div>
</template>

<script>
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_address_field",
  mixins: [form_field],
  computed: {
    countries: function() {
        let countries = window.contactum.countries,
        visibility = this.field.address.country_select.country_list_visibility_opt_name,
        hide_list = this.field.address.country_select.country_select_hide_list,
        show_list = this.field.address.country_select.country_select_show_list;

        if ('hide' === visibility && hide_list && hide_list.length) {
            countries = countries.filter( (country) => {
                return hide_list.includes(country.code) != true
            });
        } else if ('show' === visibility && show_list && show_list.length) {
            countries = countries.filter(function (country) {
                return show_list.includes(country.code) == true
            });
        }

        return countries;
    },
    default_country: function () {
        return this.field.address.country_select.value;
    }
  },
};
</script>

<style scoped>
    .contactum-fields label {
        padding: 5px;
        display: block;
    }
</style>
