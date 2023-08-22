<template>
  <div>
    <div>
      <label class="contactum-label"> Default Country </label>
        <!-- <select
          class="default-country"
          v-model="default_country"
        >
          <option value>{{ 'Select Country' }}</option>
          <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
        </select> -->
      <!-- </label> <br/> -->
      <el-select v-model="default_country" :style="{width: '100%'}">
        <el-option
          v-for="country in countries"
          :key="country.code"
          :label="country.name"
          :value="country.code">
        </el-option>
      </el-select>
    </div>

    <div class="panel-field-option panel-field-option-select">
        <label class="label-title-block contactum-label">{{ 'Country List' }}</label>
        <div class="panel-field-btn-group">
            <el-radio-group v-model="editfield.country_list.country_list_visibility_opt_name" :style="{display: 'flex'}">
              <el-radio-button label="all"> Show all </el-radio-button>
              <el-radio-button label="hide">Hide These</el-radio-button>
              <el-radio-button label="show">Only Show These</el-radio-button>
            </el-radio-group>
            <!-- <button type="button" @click.prevent="set_visibility('all')">Show all</button>
            <button type="button" @click.prevent="set_visibility('hide')">Hide These</button>
            <button type="button" @click.prevent="set_visibility('show')">Only Show These</button> -->
        </div>
      <select v-show="'all' === visibility"
        :class="['country-list-selector selectize-element-group', 'all' === visibility ? 'active' : '']"
        disabled
      >
        <option value>{{ 'Select Countries' }}</option>
      </select>

      <el-select
        v-show="'hide' === visibility"
        v-model="editfield.country_list.country_select_hide_list"
        class="[ 'hide' === visibility ? 'active' : '']"
        multiple
        @change="onChange"
        :style="{width: '100%'}"
      >
        <el-option v-for="country in countries" :key="country.code" :value="country.code" :label="country.code">{{ country.name }}</el-option>
      </el-select>

      <!-- <select
        v-show="'hide' === visibility"
        :class="['country-list-selector selectize-element-group', 'hide' === visibility ? 'active' : '']"
        v-model="editfield.country_list.country_select_hide_list"
        data-visibility="hide"
        multiple
        @change="onChange($event)"
      >
        <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
      </select> -->

      <el-select
        v-show="'show' === visibility"
        :class="['show' === visibility ? 'active' : '']"
        v-model="editfield.country_list.country_select_show_list"
        multiple
        @change="onChange"
        :style="{width: '100%'}"
      >
        <el-option v-for="country in countries" :key="country.code" :value="country.code" :label="country.code">{{ country.name }}</el-option>
      </el-select>

      <!-- <select
        v-show="'show' === visibility"
        :class="['country-list-selector selectize-element-group', 'show' === visibility ? 'active' : '']"
        v-model="editfield.country_list.country_select_show_list"
        data-visibility="show"
        multiple
        @change="onChange($event)"
      >
        <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
      </select> -->
    </div>
  </div>
</template>

<script>
import option_field from "../../../mixin/option-field.js";
export default {
  name: "field_country-list",
  mixins: [option_field],
  data: function () {
    return {
        default_country: this.editfield.country_list.name
    }
  },
  computed: {
    countries: function() {
      return contactum.countries;
    },
    visibility: function() {
      return this.editfield.country_list.country_list_visibility_opt_name;
    },

    country_in_hide_list: function() {
      return this.editfield.country_list.country_select_hide_list;
    },

    country_in_show_list: function() {
      return this.editfield.country_list.country_select_show_list;
    }
  },
  mounted: function() {
    this.bind_selectize();
  },
  methods: {

    bind_selectize() {
        var self = this;

        jQuery(this.$el).find('.default-country').selectize({
            plugins: ['remove_button'],
        }).on('change', function () {
            var value = jQuery(this).val();
            console.log( value );
            self.default_country = value;
            self.update_country_list("name", value);
        })

        jQuery(this.$el).find('.country-list-selector').selectize({
            plugins: ['remove_button'],
        }).on('change', function (e) {
            var select      = jQuery(this),
                visibility  = e.target.dataset.visibility,
                value       = select.val(),
                list        = '';

            switch(visibility) {
                case 'hide':
                    list = 'country_select_hide_list';
                    break;

                case 'show':
                    list = 'country_select_show_list';
                    break;
            }

            if ( !value ) {
                value = [];
            }

            self.update_country_list(list, value);
        });
    },

    onChange(e) {
        console.log('change');
        console.log(e);
        // let value = e.target.value,

        // let select      = jQuery(this),
        // visibility  = e.target.dataset.visibility,
        // value       = select.val(),
        // console.log(value);
        // list        = '';
        // switch(visibility) {
        //     case 'hide':
        //         list = 'country_select_hide_list';
        //         break;

        //     case 'show':
        //         list = 'country_select_show_list';
        //         break;
        // }
        // console.log(list);
        // console.log(value);
                // this.update_country_list(list, value);
    },

    update_country_list: function(property, value) {
      let country_list = { ...this.editfield.country_list };
      country_list[property] = value;

      this.$store.dispatch("update_editing_form_field", {
        id: this.editfield.id,
        property: "country_list",
        value: country_list
      });
    },
    set_visibility: function(visibility) {
        this.update_country_list('country_list_visibility_opt_name', visibility);
    }
  }
};
</script>

<style scoped>
  .panel-field-option-select {
    margin-top: 5px;
  }
    .panel-field-option-select label {
        display: block;
        margin-bottom: 5px;
    }

    .panel-field-option-select .panel-field-btn-group {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
        margin-bottom: 5px;
    }
</style>
