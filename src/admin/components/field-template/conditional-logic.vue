<template>
  <div class="panel-field">
    <label class="wcprafe-label">Conditional Logic</label>

    <ul class="list-inline">
      <li>
        <label>
          <el-radio :label="1" v-model="wcprafe_cond.condition_status"> Yes </el-radio>
          <!-- <input type="radio" value="yes" v-model="wcprafe_cond.condition_status" /> Yes -->
        </label>
      </li>

      <li>
        <label>
          <el-radio :label="0" v-model="wcprafe_cond.condition_status"> No </el-radio>
          <!-- <input type="radio" value="no" v-model="wcprafe_cond.condition_status" /> No -->
        </label>
      </li>
    </ul>

    <div v-if=" '1' == wcprafe_cond.condition_status">
      <ul>
        <li v-for="(condition, index) in conditions" class="conditional">
          <div class="conditional-field">
            <!-- <select v-model="condition.name">
              <option value>{{ '--select' }}</option>
              <option v-for="dep_field in dependencies" :value="dep_field.name" > {{ dep_field.label }} </option>
            </select> -->
              <el-select v-model="condition.name" size="small">
                <el-option
                  v-for="dep_field in dependencies"
                  :key="dep_field.name"
                  :label="dep_field.label"
                  :value="dep_field.name">
                </el-option>
              </el-select>
          </div>
          
          <div class="conditional-operator">
            <select v-model="condition.operator" size="small">
              <option value="=">{{ 'is' }}</option>
              <option value="!=">{{ 'is not'}}</option>
            </select>
          </div>

          <div class="conditional-option">
            <!-- <select v-model="condition.option">
              <option value>{{ '--select' }}</option>
              <option v-for="cond_option in get_condition_option(condition.name)" :value="cond_option.option_value"> {{ cond_option.option_label }} </option>
            </select> -->
              <el-select v-model="condition.option" placeholder="Select" size="small">
                <el-option
                  v-for="cond_option in get_condition_option(condition.name)"
                  :key="cond_option.option_label"
                  :label="cond_option.option_label"
                  :value="cond_option.option_label">
                </el-option>
              </el-select>
          </div>
          <div class="conditional-action-btn">
            <!-- class="option-btn conditional-btn option-add" -->
              <!-- <el-button @click="add_condition">  -->
                <!-- <i class="fa fa-plus-circle"></i>  -->
                <i class="el-icon-circle-plus-outline" @click="add_condition"></i>
              <!-- </el-button> -->
              <!-- class="option-btn conditional-btn option-remove" -->
              <!-- <el-button type="danger" v-if="conditions.length > 1" @click="delete_condition" >  -->
                  <!-- <i class="fa fa-minus-circle"></i>  -->
                  <i class="el-icon-remove-outline"  v-if="conditions.length > 1" @click="delete_condition" > </i>
              <!-- </el-button> -->
          </div>
        </li>
      </ul>
        <p> Show this field if these rules are met
            <!-- <select v-model="wcprafe_cond.cond_logic">
                <option value="any"> any </option>
                <option value="all"> all</option>
            </select> -->
              
          <el-select v-model="wcprafe_cond.cond_logic" placeholder="Select" size="small">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
            <!-- <el-option
              :key="any"
              :label="any"
              :value="any">
            </el-option>
            <el-option
              :key="all"
              :label="all"
              :value="all">
            </el-option> -->
          </el-select> 
        </p>
    </div>
  </div>
</template>

<script>
import option_field from "../../mixin/option-field.js";
export default {
  name: "field_conditional_logic",
  mixins: [option_field],
  data: function() {
    return {
      conditions: [],
      status: false,
      cond_logic: 'all',
      options: [
        { value: 'any', label: 'any'},
        { value: 'all', label: 'all'}
      ]
    };
  },
  computed: {
    wcprafe_cond: function () {
        return this.editfield.wcprafe_cond;
    },

    condition_supported_field: function() {
        return window.wcprafe.wcprafe_cond_supported_fields;
    },

    dependencies: function() {
        let form_fields        = this.$store.getters.form_fields;
        let dependenciesFields = form_fields.filter( item => this.condition_supported_field.includes(item.template) &&  this.editfield.name != item.name );

        return dependenciesFields;
    }

  },
  created: function() {
    let wcprafe_cond = { ...this.editfield.wcprafe_cond };

    for (var i = 0; i < wcprafe_cond.cond_field.length; i++) {
        if (wcprafe_cond.cond_field[i] && wcprafe_cond.cond_operator[i]) {
            this.conditions.push({
                name: wcprafe_cond.cond_field[i],
                operator: wcprafe_cond.cond_operator[i],
                option: wcprafe_cond.cond_option[i]
            });
        }
    }

    if (!this.conditions.length) {
      this.conditions.push({
        name: "",
        operator: "=",
        option: ""
      });
    }
  },
  methods: {
    get_condition_option: function(field_name) {
        let options = [];
        let dep = this.dependencies.filter( field => field.name === field_name );

        if (dep.length && dep[0].options) {
            let f_options = { ...dep[0].options };
            for (const [option_label, option_value] of Object.entries(f_options)) {
                options.push({
                    option_value: option_value,
                    option_label: option_label
                });
            };
        }
        return options;
    },
    add_condition: function(index) {
      this.conditions.push({
        name: "",
        operator: "=",
        option: ""
      });
    },
    delete_condition: function(index) {
      this.conditions.splice(index, 1);
    }
  },
  watch: {
        conditions: {
            deep: true,
            handler: function (new_conditions) {
                let new_wcprafe_cond = { ...this.wcprafe_cond };
                if ( !this.wcprafe_cond ) {
                    new_wcprafe_cond.condition_status = 'no';
                    new_wcprafe_cond.cond_logic = 'all';
                }

                new_wcprafe_cond.cond_field       = [];
                new_wcprafe_cond.cond_operator    = [];
                new_wcprafe_cond.cond_option      = [];

                let i = 0;

                for (i = 0; i < new_conditions.length; i++) {
                    new_wcprafe_cond.cond_field.push(new_conditions[i].name);
                    new_wcprafe_cond.cond_operator.push(new_conditions[i].operator);
                    new_wcprafe_cond.cond_option.push(new_conditions[i].option);
                }

                this.$store.dispatch("update_editing_form_field", {
                  id: this.editfield.id,
                  property: 'wcprafe_cond',
                  value: new_wcprafe_cond
                });
            }
        }
  }
};
</script>


<style scoped>

.conditional-action-btn {
  display: flex;
  align-items: center;
}

</style>