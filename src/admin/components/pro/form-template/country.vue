<template>
  <div class="contactum-fields">
    <select v-model="default_country" disabled>
      <option>{{ 'select country' }}</option>
      <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
    </select>
    <span v-if="field.help" v-html="field.help"></span>
  </div>
</template>

<script>
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_country_field",
  mixins: [form_field],
  computed: {
    countries: function() {
      let countries = window.contactum.countries,
        visibility = this.field.country_list.country_list_visibility_opt_name,
        hide_list = this.field.country_list.country_select_hide_list,
        show_list = this.field.country_list.country_select_show_list;

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

    default_country: function() {
      return this.field.country_list.name;
    }
  }
};
</script>


<style>
.contactum-fields select {
  width: 100%;
}

</style>