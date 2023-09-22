<template>
  <div class="builder form-preview-stage">
    <form
      method="post"
      action
      id="contactum-form-builder"
      class="contactum-form-builde"
      v-on:submit.prevent="save_form_builder"
    >
      <div class="builder-header">
        <span
          class="form-title"
          @click.prevent="post_title_editing = true"
        >
          <i class="el-icon-edit"></i>
          <!-- <i class="fa fa-edit"></i> -->
          {{ post.post_title }}
        </span>
        
        <div class="contactum-nav">
          <!-- nav-tab-wrapper  'nav-tab' -->
          <ul class="contactum-tabs">
            <li>
              <a
                href="#form-builder-container"
                :class="[ isActiveTab( 'editor' ) ? 'nav-tab-active' : '']"
                @click.prevent="makeActive('editor')"
              >Editor</a>
            </li>
            <li>
              <a
                href="#"
                :class="[ isActiveTab( 'settings' ) ? 'nav-tab-active' : '']"
                @click.prevent="makeActive('settings')"
              >Settings</a>
            </li>
          </ul>
        </div>

        <div class="builder-save">
          <div class="save_form_builder">
            <button type="submit"> Save Form <span v-if="loading == true" class="showLoading"><i class="fa fa-spinner fa-pulse" aria-hidden="true"></i></span></button>
          </div>        
        </div>
     
      </div>


      <!-- <div class="tab-contents"> -->
        <div class="form-builder-container" v-show="isActiveTab('editor')">
          <header>
            
            <!-- <el-button type="button" @click="dialogVisible = true">click to open the Dialog</el-button> -->
            
            <!-- <RokDiaglog :title="post.post_title" :dialogVisible="dialogVisible"/> -->
            <!-- <span
              v-if="!post_title_editing"
              class="form-title"
              @click.prevent="post_title_editing = true"
            >
              <i class="fa fa-edit"></i>
              {{ post.post_title }}
            </span> -->
            <span v-show="post_title_editing">
            <modal>
              <div slot="header">
                <h2> Rename Form </h2>
                  <i class="fa fa-times" aria-hidden="true" @click.prevent="post_title_editing = false"></i>
              </div>

              <div slot="body">
                <div>
                  <label for=""> Form Title </label>
                  <!-- <el-input v-model="post.post_title" /> -->
                  <input type="text" v-model="post.post_title" name="post_title" class="modal-input" />
                </div>
              </div>

              <div slot="footer">
                  <el-button-group>
                    <el-button @click.prevent="post_title_editing = false"> Cancel </el-button>
                    <el-button
                      type="primary"
                      @click.prevent="post_title_editing = false"
                    > Rename
                      <!-- <i class="fa fa-check"></i> -->
                    </el-button>
                  </el-button-group>
              </div>
            </modal>
            </span>
          </header>
          <div class="builder-body">
            <section class="form-field">
              <ul
                :class="[ 'contactum-form', 'sortable-list', 'form-label-' + settings.label_position]"
              >
                <li
                  v-for="(field, index) in form_fields"
                  :key="index"
                  :class="[ field.name, field.css, 'form-field-' + field.template, 'field-items',
                                    field.width ? 'field-size-' + field.width : '',
                                    ]"
                  :data-index="index"
                  data-source="stage"
                >
                  <div class="contactum-label">
                    <label v-if="!is_full_width(field.template) && ( field.template != 'submit_field' && field.template != 'name_field' ) ">
                      {{ field.label }}
                      <span
                        v-if="field.required && 'yes' === field.required"
                        class="required"
                      >*</span>
                    </label>
                  </div>
                  <!-- {{  field.template }} -->
                  <component :is="'form_' + field.template" :field="field"></component>
                  <div class="control-button">
                    <!-- <i class="el-icon-rank" v-if="field.template != 'submit_field'"> </i> -->
                    <i class="fa fa-arrows move"></i>
                    <button @click.prevent="select_field(field)">
                      <!-- <i class="fa fa-pencil"></i> -->
                      <i class="el-icon-edit"></i>
                    </button>
                    <button @click.prevent="delete_field(index)">
                      <!-- <i class="fa fa-trash"></i> -->
                      <i class="el-icon-delete"></i>
                    </button>
                    <button @click.prevent="duplicate_field(field,index)">
                      <!-- <i class="fa fa-clone"></i> -->
                      <i class="el-icon-copy-document"> </i>
                    </button>
                  </div>
                </li>
              </ul>
              <input type="hidden" name="contactum_form_id" :value="post.ID" />

              <div class="submit_wrapper" v-if="hasSubmitField() == undefined">
                <input type="submit" class="btn-submit" :value="settings.submit_text" disabled />
              </div>
            </section>
            <div class="field-panel">
              <div class="forms-fields-tab">
                <button
                  id="add-fields"
                  :class="['fields','form_fields' === current_panel ? 'active' : '' ]"
                  @click.prevent="sidebartab('form_fields')"
                >Add Fields</button>
                <button
                  id="field-options"
                  :class="['field_options' === current_panel ? 'active' : '', !form_fields.length ? 'disabled' : '']"
                  @click.prevent="sidebartab('field_options')"
                  :disabled="!form_fields.length"
                >Field Options</button>
              </div>
              <div class="forms-sidbar-tab-content">
                <component :is="current_panel"></component>
              </div>
            </div>
          </div>
        </div>

        <div class="form-builder-settings" v-show="isActiveTab('settings')">
          <form_settings />
        </div>
      <!-- </div> -->

    </form>
  </div>
</template>

<script>
import axios from "axios";
import { v4 as uuidv4 } from "uuid";
import draggable from "vuedraggable";
import form_settings from "../form-settings/index.vue";

import Form_settings from "../form-settings/index.vue";
import form_fields from "../form-fields/index.vue";
import field_options from "../field-options/index.vue";

import form_text_field from "../form-templates/text.vue";
import form_textarea_field from "../form-templates/textarea.vue";
import form_email_field from "../form-templates/email.vue";
import form_checkbox_field from "../form-templates/checkbox.vue";
import form_radio_field from "../form-templates/radio.vue";
import form_image_field from "../form-templates/image.vue";
import form_date_field from "../form-templates/date.vue";
import form_dropdown_field from "../form-templates/dropdown.vue";
import form_multiple_select from "../form-templates/multiselect.vue";
import form_html_field from "../form-templates/html.vue";
import form_hidden_field from "../form-templates/hidden.vue";
import form_section_break from "../form-templates/section.vue";
import form_number_field from "../form-templates/number.vue";


import form_file_field from "../pro/form-template/file.vue";
import form_checkbox_grid from "../pro/form-template/checkbox-grid.vue";
import form_multiple_choice_grid from "../pro/form-template/multiple-choice-grid.vue";
import form_linear_scale from "../pro/form-template/linear.vue";
import form_signature_field from "../pro/form-template/signature.vue";
import form_repeat_field from "../pro/form-template/repeat.vue";
import form_column_field from "../pro/form-template/column.vue";


import modal from '../../components/modal/index.vue';


export default {
  name: "Builder",
  components: {
    modal,
    draggable,
    form_settings,
    form_text_field,
    form_textarea_field,
    form_email_field,
    form_checkbox_field,
    form_radio_field,
    form_image_field,
    form_date_field,
    form_dropdown_field,
    form_multiple_select,
    form_html_field,
    form_hidden_field,
    form_section_break,
    form_number_field,
    field_options,
    form_fields,
    Form_settings,
    form_file_field,
    form_checkbox_grid,
    form_multiple_choice_grid,
    form_linear_scale,
    form_signature_field,
    form_repeat_field,
    form_column_field,
  },
  data() {
    return {
      form_fields_components: [],
      post_title_editing: false,
      activeTab: "editor",
      loading: false,
      dialogVisible: false,
    };
  },
  computed: {
    panel_sections: function () {
      return this.$store.getters.panel_sections;
    },
    field_settings: function () {
      return this.$store.getters.field_settings;
    },
    form_fields: {
      get: function () {
        return this.$store.getters.form_fields;
      },
      set: function (value) {
        this.$store.dispatch("set_form_fields", value);
      },
    },
    post: function () {
      return this.$store.getters.post;
    },
    current_panel: function () {
      return this.$store.getters.current_panel;
    },
    notifications: function () {
      return this.$store.getters.notifications;
    },
    settings: function () {
      return this.$store.getters.settings;
    },
    integrations: function () {
      return this.$store.getters.integrations;
    },
    shortcode: function() {
        return `[contactum id="${this.post.ID}"]`;
    }
  },

  methods: {
    hasSubmitField: function() {
       let response =  this.form_fields.find( field => field.template== "submit_field" );
       console.log("response", response);

       return response;
       
    },
    // makeActive: function (tab, event) {
    makeActive: function (val) {
      // console.log(tab);
      console.log(event);
      console.log('make active');
      this.activeTab = val;
    },

    isActiveTab: function (val) {
      return this.activeTab === val;
    },

    add_field(field) {
      this.$store.dispatch("add_field", field);
    },

    delete_field(index) {
      this.$swal({
        title: 'Are you sure?',
        text: "you want to delete this field?",
        type: "warning",
        showCloseButton: true,
        showCancelButton: true,
        cancelButtonColor: "#606266",
        confirmButtonColor: "#DC2727",
        confirmButtonText: "Yes Delete",
        cancelButtonText: " No Cancel",
      }).then((result) => {
        if (result.value) {
          this.$swal(
            "Deleted",
            "You successfully deleted this Field",
            "success"
          );
          this.$store.dispatch("delete_field", index);
        } else {
          // this.$swal("Cancelled", "Your field is still intact", "info");
        }
      });
    },

    select_field(field) {
      this.$store.dispatch("select_field", field.id);
      // this.$store.dispatch("select_field", field);
    },

    duplicate_field(field, index) {
      let payload = {
        ...field,
        id: uuidv4(),
        is_new: true,
        index: index,
      };

      // if (!payload.name && payload.label) {

      let same_template_fields = this.form_fields.filter((form_field) => {
        return form_field.template === field.template;
      });

      if (same_template_fields.length) {
        payload.name = payload.label.replace(/\W/g, "_").toLowerCase();
        payload.name += "_" + same_template_fields.length;
      }
      // }

      this.$store.dispatch("duplicate_field", payload);
    },

    is_full_width: function (template) {
      if (
        this.field_settings[template] &&
        this.field_settings[template].is_full_width
      ) {
        return true;
      }

      return false;
    },

    is_invisible: function (field) {
      return field.recaptcha_type &&
        "invisible_recaptcha" === field.recaptcha_type
        ? true
        : false;
    },

    save_form_builder() {
        this.loading = true;
        var self = this;
        jQuery.post(
            contactum.ajaxurl,
        {
          action: "save_contactum_form",
          form_data: jQuery("#contactum-form-builder").serialize(),
          form_fields: JSON.stringify(this.form_fields),
          notifications: JSON.stringify(this.notifications),
          settings: JSON.stringify(this.settings),
          integrations: JSON.stringify(this.integrations),
          contactum_form_builder_nonce: contactum.nonce,
        },
        (response, textStatus, xhr) => {
            this.loading = false;
            if (response.data.form_fields) {
                this.$store.dispatch("set_form_fields", response.data.form_fields);
            }
            
            if (response.data.form_settings) {
                this.$store.dispatch(
                    "set_form_settings",
                    response.data.form_settings
                );
            }
            
            this.$notify.success({
                  title: '',
                  message: 'Form successfully Save.',
                  position: "bottom-right"
            });
        }
      );
    },
    sidebartab(value) {
      this.$store.dispatch("set_current_panel", value);
    },

    add_field(field_template, toIndex) {
      var payload = {
        ...this.field_settings[field_template].field_props,
        id: uuidv4(),
        toIndex: toIndex,
      };

      if (!payload.name && payload.label) {
        payload.name = payload.label.replace(/\W/g, "_").toLowerCase();

        var same_template_fields = this.form_fields.filter((form_field) => {
          return form_field.template === field_template;
        });

        if (same_template_fields.length) {
          payload.name += "_" + same_template_fields.length;
        }
      }

      this.$store.dispatch("add_field", payload);
    },

    swap_form_field_elements(payload) {
      this.$store.dispatch("swap_form_field_elements", payload);
    },
  },

  mounted: function () {
    var self = this;
    // bind jquery ui sortable
    jQuery(".form-preview-stage .contactum-form.sortable-list").sortable({
      placeholder: "form-preview-stage-dropzone",
      items: ".field-items",
      handle: ".control-button .move",
      scroll: true,
      over: function () {},
      update: function (e, ui) {
        console.log('sortable page', ui);
        var item = ui.item[0],
          data = item.dataset,
          source = data.source,
          toIndex = parseInt(jQuery(ui.item).index()),
          payload = {
            toIndex: toIndex,
          };

        if ("panel" === source) {
          var field_template = ui.item[0].dataset.formField;
          self.add_field(field_template, toIndex);

          // remove button from stage
          jQuery(this)
            .find(".panel-button.ui-draggable.ui-draggable-handle")
            .remove();
        } else if ("stage" === source) {
          payload.fromIndex = parseInt(data.index);
          self.swap_form_field_elements(payload);
        }
      },
    });
  },

  watch: {
    form_fields: {
      handler: function () {
        this.isDirty = true;
      },
      deep: true,
    },
  },
};
</script>

<style scoped lang="scss">

// $primary-color: #007bff;
// $secondary-color: #6c757d;
$background-color: #fff;
$button-background-color: #409EFF;
$text-color: #000;
$button-background-secondary-color: #dedede;
$button-text-secondary-color: #545454;

.builder {
    display: flex;
    .builder-header {
        display: flex;
        margin-bottom: 5px;
        background: $background-color;
        padding: 5px;
        .contactum-nav {
            display: flex;
            flex: 1;
            .contactum-tabs {
                flex: 1;
                display: flex;
                gap: 15px;
                li a {
                  text-decoration: none;
                  color: $button-text-secondary-color;
                  font-size: 15px;
                  font-weight: 500;
                }

                li a.nav-tab-active {
                  color: $button-background-color;
                }
            }
            .nav-tab-wrapper {
                padding-top: 0px !important;
            }

            .nav-tab-wrapper li {
                margin-bottom: 0px;
            }
        }

        .builder-save {
            display: flex;
            justify-content: flex-end;
            flex: 2;
            box-sizing: border-box;
            align-items: start;
            gap: 15px;
        }
    }

    .save_form_builder {
        display: flex;
        align-items: start;
        // justify-content: flex-end;
        button, a {
            display: block;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 14px;
            // background: #7e3bd0;
            background: $button-background-color;
            color: #fff;
            margin-right: 5px;
            outline: none;
            transition: 2s background;
            border: 1px solid transparent;
            border-radius: 8px;
        }

        button:hover {
            background: $button-background-color;
        }

        a {
          text-decoration: none;
          text-align: center;
          background: $button-background-secondary-color;
          color: $button-text-secondary-color
        }
    }
}

.field-panel {
    flex-basis: 35%;
    background: #f9f9f9;
    .forms-fields-tab {
        display: flex;
        width: 100%;
        background: $background-color;

        button {
          width: 50%;
          display: block;
          padding: 15px 0;
          font-weight: 600;
          text-align: center;
          color: #23282d;
          background: #fff;
          border: none;
          cursor: pointer;
          outline: none;
        }

        button.active {
            border-bottom: 2px solid $button-background-color;
        }
    }
}

.form-field {
  flex-basis: 65%;
  margin-right: 15px;
  background: none;
  box-sizing: border-box;
  padding: 10px;
}

form#contactum-form-builder {
  width: 100%;
}

.builder-body {
    display: flex;
    .form-field ul {
        li {
          width: 100%;
          min-width: 70px;
          padding-left: 10px;
          padding-right: 10px;
          box-sizing: border-box;
          position: relative;
          margin-bottom: 20px;

            .control-button {
              display: none;
              position: absolute;
              justify-content: center;
              align-items: center;
              -webkit-box-align: center;
              background: #f9f9f9;
              background: #000;
              top: 40%;
              left: 40%;

              button {
                color: #fff;
                background: #000;
                padding: 5px 10px;
                display: inline-flex;
                border: none;
                cursor: pointer;
              }

              button:hover {
                background: $button-background-color;
              }
            }
        }

        li:hover > .control-button {
              display: block;
        }  

        li ul {
            margin-top: 5px;
        }
    }

    .form-field ul
    .submit_wrapper {
      display: flex;
    }

    .submit_wrapper .btn-submit {
      border: 1px solid transparent;
      border-radius: 8px;
      padding: 10px 20px;
      background: $button-background-color;
      color: #fff;
      display: inline-block;
      border: none;
      color: #fff;
      // min-width: 120px;
      margin-top: 30px;
      font-size: 14px;
      font-weight: 400;
      cursor: pointer;
    }
}
.contactum-fields {
    margin-bottom: 10px;
}

ul.contactum-form  {
    
    border: 1px dashed #cfcfcf;
    min-height: 70px;
    // margin: 0 10px;
    margin-left: 0px;

    li.field-size-small .contactum-fields {
        width: 30%;
    }
    li.field-size-medium .contactum-fields {
        width: 65%;
    }
    li.field-size-large .contactum-fields {
        width: 100%;
    }

    li.name {
        .contactum-fields {
            display: flex;
            justify-content: space-between;
            div {
              margin-right: 10px;
            }
        }
    }
}

ul.contactum-form.form-label-above li .contactum-label {
  display: block;
  width: 100%;
  margin-bottom: 10px;
}

ul.contactum-form.form-label-hidden li .contactum-label {
  display: none;
}

.form-builder-container {
    section {
        height: calc(100vh - 170px);
        overflow-y: auto;
    }

    header {
        margin-bottom: 20px;
        margin-top: 10px;

        button {
            // width: 40px;
        }

        span i.fa.fa-edit {
          font-size: 20px;
          cursor: pointer;
        }

        span.form-id {
          // background: #7e3bd0;
          background: #409EFF;
          padding: 5px 10px;
          color: #fff;
          display: inline-block;
          margin-left: 5px;
          cursor: pointer;
        }
    }
}

.form-preview-stage .field-items .control-button i.move {
  cursor: move;
  color: #fff;
}

ul.contactum-form.form-label-left li,
ul.contactum-form.form-label-right li {
  display: flex;
  justify-content: space-between;
}

ul.contactum-form.form-label-left li div.contactum-label,
ul.contactum-form.form-label-right li div.contactum-label {
  flex-basis: 20%;
}

ul.contactum-form.form-label-right {
    li {
        flex-direction: row-reverse;
        div.contactum-fields {
            flex-basis: 75%;
        }
    }
}


.btn {
  padding: 8px 15px;
}

.btn-copy {
    background: #dedede;
    color: #545454;
    overflow: hidden;
    opacity: 1;
    border: none;
    cursor: copy;
    border-radius: 8px;
}

.form-title {
  padding: 15px;
  display: block;
}

.modal-input {
  width: 100%;
  margin-top: 10px;
  margin-bottom: 10px;
  display: block;
}

</style>
