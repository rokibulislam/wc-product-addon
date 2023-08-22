<template>
  <div class="panel-field-address">
    <label class="label-hr">{{ field.title }}</label>
    <ul class="address-fields">
      <li v-for="(address, field) in editfield.address">
        <template v-if="'country_select' !== field">
          <div class="panel-child-field">
                <div>
                    <label> 
                        {{  editfield.address[field].checked }}
                        <el-checkbox v-model="editfield.address[field].checked"> </el-checkbox>
                      <!-- <input type="checkbox"  @click="toggle_address_checked(field)"/> -->
                      {{ field }}
                    </label>
                </div>
                <div>
                    <label>
                        <!-- <el-checkbox v-model="editfield.address[field].required"> </el-checkbox> -->
                      <input type="checkbox" :checked="address.required" @click="toggle_address_required(field)"/>
                      {{ 'Required' }} 
                    </label>
                    <button type="button" @click="toggle_show_details(field)"> <i class="fa fa-caret-down"></i> </button>
                </div>
          </div>

            <div v-show="show_details[field]" class="address-input-fields">
                <p> <label> Label <el-input v-model="address.label"></el-input> </label> </p>
                <p> <label> Default <el-input v-model="address.value"></el-input> </label>  </p>
                <p> <label> Placeholder <el-input v-model="address.placeholder"></el-input> </label>  </p>
                <!-- <p> <label> Label <input type="text" v-model="address.label"></label> </p>
                <p> <label> Default <input type="text" v-model="address.value"></label> </p>
                <p> <label> Placeholder  <input type="text" v-model="address.placeholder"></label> </p> -->
            </div>
        </template>

        <template v-else>
            <div class="address-title-header panel-child-field">
                <div>
                    <label>
                        <input
                            type="checkbox"
                            :checked="address.checked"
                            @click="toggle_address_checked(field)"
                        /> {{ field }}
                    </label>
                </div>

                <div>
                    <label v-show="show_details[field]">
                        <input
                            type="checkbox"
                            :checked="address.required"
                            @click="toggle_address_required(field)"
                        > Required
                    </label>

                    <button
                        type="button"
                        class="button button-link button-dropdown"
                        @click="toggle_show_details(field)"
                    >
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
            </div>

            <div v-show="show_details[field]" class="child-address-country">
                <div  class="country-label">
                    <p>
                        <label>
                            Label  
                            <!-- <input type="text" v-model="address.label">  -->
                            <el-input type="text" v-model="address.label" />
                        </label>
                    </p>
                </div>

                <div class="address-country-fields">
                    <label> Default Country
                        <!-- <select class="default-country" v-model="default_country">
                            <option value=""> Select Country </option>
                            <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
                        </select> -->
                        <el-select v-model="default_country" :style="{width: '100%'}">
                            <el-option
                            v-for="country in countries"
                            :key="country.code"
                            :label="country.name"
                            :value="country.code">
                            </el-option>
                        </el-select>
                    </label>

                    <div class="panel-field-opt-select country-list-selector-container panel-field-option-select">
                        <label class="label-title-block"> Country List </label>
                        <el-radio-group v-model="editfield.address.country_select.country_list_visibility_opt_name" :style="{display: 'flex'}">
                            <el-radio-button label="all"> Show all </el-radio-button>
                            <el-radio-button label="hide">Hide These</el-radio-button>
                            <el-radio-button label="show">Only Show These</el-radio-button>
                        </el-radio-group>
                        <!-- <div class="panel-field-btn-group">
                            <button type="button" @click.prevent="set_visibility('all')">Show all</button>
                            <button type="button" @click.prevent="set_visibility('hide')">Hide These</button>
                            <button type="button" @click.prevent="set_visibility('show')">Only Show These</button>
                        </div> -->

                        <select
                            v-show="'all' === active_visibility"
                            :class="['country-list-selector selectize-element-group', 'all' === active_visibility ? 'active' : '']"
                            disabled
                        >
                            <option value=""> Select Countries </option>
                        </select>

                        <el-select
                            v-show="'hide' === active_visibility"
                            v-model="country_in_hide_list"
                            class="[ 'hide' === active_visibility ? 'active' : '']"
                            multiple
                            @change="onChange"
                            :style="{width: '100%'}"
                        >
                            <el-option v-for="country in countries" :key="country.code" :value="country.code" :label="country.code">{{ country.name }}</el-option>
                        </el-select>

                        <el-select
                            v-show="'show' === active_visibility"
                            :class="['show' === active_visibility ? 'active' : '']"
                            v-model="country_in_show_list"
                            multiple
                            @change="onChange"
                            :style="{width: '100%'}"
                        >
                            <el-option v-for="country in countries" :key="country.code" :value="country.code" :label="country.code">{{ country.name }}</el-option>
                        </el-select>

                        <!-- <select
                            v-show="'hide' === active_visibility"
                            :class="['country-list-selector selectize-element-group', 'hide' === active_visibility ? 'active' : '']"
                            v-model="country_in_hide_list"
                            data-visibility="hide"
                            multiple
                        >
                            <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
                        </select>

                        <select
                            v-show="'show' === active_visibility"
                            :class="['country-list-selector selectize-element-group', 'show' === active_visibility ? 'active' : '']"
                            v-model="country_in_show_list"
                            data-visibility="show"
                            multiple
                        >
                            <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
                        </select> -->
                    </div>
                </div>
            </div>
        </template>
      </li>
    </ul>
  </div>
</template>

<script>
import option_field from "../../../mixin/option-field.js";
export default {
  name: "field_address",
  mixins: [option_field],
  data: function() {
    return {
        default_country: this.editfield.address.country_select.value,
        show_details: {
            street_address:  false,
            street_address2: false,
            city_name:       false,
            state:           false,
            zip:             false,
            country_select:  false,
        },
    }
  },
  computed: {
    countries: function() {
        return contactum.countries;
    },
    active_visibility: function () {
        return this.editfield.address.country_select.country_list_visibility_opt_name;
    },

    country_in_hide_list: function () {
        return this.editfield.address.country_select.country_select_hide_list;
    },

    country_in_show_list: function () {
        return this.editfield.address.country_select.country_select_show_list;
    }
  },
    mounted: function () {
        this.bind_selectize();
    },
  methods: {
    toggle_address_checked: function(field) {
        console.log('field address');
        console.log(field);
        this.editfield.address[field].checked = this.editfield.address[field].checked ? '' : 'checked';
        // this.editfield.address[field].checked = this.editfield.address[field].checked ? false : false;
    },

    toggle_address_required: function(field) {
        this.editfield.address[field].required = this.editfield.address[field].required ? '' : 'checked';
        // this.editfield.address[field].required = this.editfield.address[field].required ? false : false;
    },

    toggle_show_details: function (field) {
        this.show_details[field] = !this.show_details[field];
    },

    bind_selectize() {
        var self = this;

        jQuery(this.$el).find('.default-country').selectize({
            plugins: ['remove_button'],
        }).on('change', function () {
            var value = jQuery(this).val();
            console.log( value );
            self.default_country = value;
            self.update_country_list("value", value);
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
    update_country_list: function(property, value) {
        let address = { ...this.editfield.address };
        address.country_select[property] = value;

        this.$store.dispatch("update_editing_form_field", {
            id: this.editfield.id,
            property: "address",
            value: address
        });
    },
    set_visibility: function(value) {
      this.update_country_list("country_list_visibility_opt_name", value);
    }
  }
};
</script>

<style scoped>
    .panel-field-address {
        display: block;
    }

    .panel-child-field {
        display: flex;
        width: 100%;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
    }

    .address-input-fields {
        /* display: flex; */
        /* justify-content: space-between; */
    }

    .panel-field-option-select label {
        display: block;
        margin-bottom: 5px;
    }

    .panel-field-option-select .panel-field-btn-group {
        display: flex;
        justify-content: space-between;
    }
</style>
