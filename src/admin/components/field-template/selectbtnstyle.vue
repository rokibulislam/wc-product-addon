<template>
    <div class="panel-field-opt panel-field-opt-select">
      <label class="contactum-label"> {{ field.title }}</label>
      <el-select v-model="value" :style="{width: '100%'}">
        <el-option
          v-for="(option, key) in field.options"
          :key="key"
          :label="option"
          :value="key">
        </el-option>
      </el-select>
      <!-- {{  editfield[field.name] }} {{  value }} -->
      <!-- {{ editfield[field.name] }} -->
      <!-- {{ this.field.name.settings }} -->
        
        <div v-if="value == 'custom'">
            <el-tabs  @tab-click="handleClick" type="border-card" v-model="editfield.currentState">
                
                <el-tab-pane label="Normal State" name="normal" class="normal">
                    
                    <ColorPicker label="Background Color" v-model="this.editfield.settings.normal_background_color" name="normal_background_color" @updateColor="changeColor"></ColorPicker>
                    <ColorPicker label="Border Color" v-model="this.editfield.settings.normal_border_color" name="normal_border_color" @updateColor="changeColor"></ColorPicker>
                    <ColorPicker label="Color" v-model="this.editfield.settings.normal_font_color" name="normal_font_color" @updateColor="changeColor"></ColorPicker>
                    
                    <div class="panel-field">
                      <label class="contactum-label"> Border Radius </label>
                      <el-input label="Border Radius" type="text" v-model="editfield.settings.normal_border_radius"> </el-input>
                    </div>
                    
                    <div class="panel-field">
                      <label class="contactum-label"> Min Width </label>
                      <el-input type="text" v-model="editfield.settings.normal_minwidth"> </el-input>
                    </div>

                </el-tab-pane>
                
                <el-tab-pane label="Hover State" name="hover" class="hover">
                    
                    <ColorPicker label="Background Color" v-model="this.editfield.settings.hover_background_color" name="hover_background_color" @updateColor="changeColor"></ColorPicker>
                    <ColorPicker label="Border Color" v-model="this.editfield.settings.hover_border_color" name="hover_border_color" @updateColor="changeColor"></ColorPicker>
                    <ColorPicker label="Color" v-model="this.editfield.settings.hover_font_color"  name="hover_font_color" @updateColor="changeColor"></ColorPicker>
                    
                    <div class="panel-field">
                      <label class="contactum-label"> Border Radius </label>
                      <el-input type="text" v-model="editfield.settings.hover_border_radius"> </el-input>
                    </div>
                    
                    <div class="panel-field">
                      <label class="contactum-label"> Min Width </label>
                      <el-input type="text" v-model="editfield.settings.hover_minwidth"> </el-input>
                    </div>

                </el-tab-pane>

            </el-tabs>
        </div>
    </div>
  </template>
  
  <script>
  import option_field from "../../mixin/option-field.js";
  import ButtonStyler from './helpers/ButtonStyler.vue'
  import ColorPicker from './helpers/ColorPicker.vue'
  export default {
    name: "field_selectbtnstyle",
    mixins: [option_field],
    components: {
      ColorPicker  
    },
    data() {
        return {
            activeName: 'normal',
        }
    },
    computed: {
      value: {
        get: function() {
          let property = this.field.name;
          return this.editfield[property];
        },
        set: function(value) {
          this.$store.dispatch("update_editing_form_field", {
            id: this.editfield.id,
            property: this.field.name,
            value: value
          });
        }
      }
    },
    mounted: function () {
      this.set_settings();
    },

    methods: {
      
      handleClick: function(tab, event) {
        console.log('handle Click');
        console.log(tab);
        console.log(event);
        // this.editfield.currentState = this.activeName;
      },

      set_settings: function() {
        console.log('set settings');
        this.settings = { ...this.editfield.settings };
      },
      
      changeColor(color) {
        console.log('parent color');
        console.log(color); 

        this.$store.dispatch("update_editing_form_field", {
          id: this.editfield.id,
          property: "settings",
          value: { ...this.settings, ...color }
        });

      }
    },
    watch: {
      settings: {
        deep: true,
        handler: function (new_setting) {
          console.log('settings');
          console.log(new_setting);
        },
      }
    }
  };
  </script>
  
<style scoped>
  .normal, .hover {
    display: flex;
    flex-wrap: wrap;
  }

  .normal .panel-field, .hover .panel-field {
    flex-basis: 50%;
  }

</style>