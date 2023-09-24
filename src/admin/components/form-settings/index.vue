<template>
    <div class="form-settings">
        <table class="form-table">
            <tbody>

                <tr class="wcprafe-label-position">
                    <th> Label Position </th>
                    <td>
                        <el-select v-model="settings.label_position">
                            <el-option
                                v-for="label_position in label_positions"
                                :key="label_position.value" 
                                :label="label_position.label" 
                                :value="label_position.value"
                            >
                            </el-option>
                        </el-select>
                        <p class="description"> Where the labels of the form should display </p>
                    </td>
                </tr>

                <tr class="wcprafe-use-theme-css">
                    <th> Use Theme CSS'</th>
                    <td>
                        <select v-model="settings.use_theme_css">
                            <option value="wcprafe-theme-style"> Yes </option>
                            <option value="wcprafe-style"> No </option>
                        </select>
                        <p class="description"> Selecting <strong>Yes</strong> will use your theme's style for form fields. </p>
                    </td>
                </tr> 
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
  name: "form_settings",
  data: function() {
        return {
            label_positions: [
                {
                    'value': 'above',
                    'label': 'Above Element'
                },
                {
                    'value': 'left',
                    'label': 'Left of Element'
                },
                {
                    'value': 'right',
                    'label': 'Right of Element'
                },
                {
                    'value': 'hidden',
                    'label': 'Hidden'
                }
            ],
        }
    },
  computed: {
    settings: function() {
        return this.$store.getters.settings;
    }
  },
      watch: {
        settings: {
            deep: true,
            handler: function(value) {
                this.$store.dispatch("set_form_settings", value);
            }
        }
    }
};
</script>

<style type="text/css" scoped>
    .form-settings {
        padding-left: 20px;
    }

    .schedule-field {
        display: flex;
        label {
            margin-right: 5px;
        }
    }
</style>
