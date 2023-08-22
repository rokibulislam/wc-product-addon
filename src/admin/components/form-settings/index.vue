<template>
    <div class="form-settings">
        <table class="form-table">
            <tbody>

                <tr class="contactum-redirect-to">
                    <th> Redirect To </th>
                    <td>
                        <el-select v-model="settings.redirect_to">
                            <el-option
                                v-for="redirect_to in redirects_to"
                                :key="redirect_to.value" 
                                :label="redirect_to.label" 
                                :value="redirect_to.value"
                            >
                            </el-option>
                        </el-select>
                        <p class="description"> After successful submit, where the page will redirect to. This redirect option will not work if Show Report in Frontend option is enabled
                        </p>
                    </td>
                </tr>

                <tr class="contactum-same-page" v-show="settings.redirect_to == 'same'">
                    <th> Message to show </th>
                    <td>
                        <el-input type="textarea" :rows="3" :cols="40" v-model="settings.message"> </el-input>
                        <!-- <textarea rows="3" cols="40" v-model="settings.message"></textarea> -->
                    </td>
                </tr>

                <tr class="contactum-page-id" v-show="settings.redirect_to == 'page'">
                    <th> Page </th>
                    <td>
                        <el-select v-model="settings.page_id">
                            <el-option v-for="(page,index) in settings.pages" :key="index"  :label="index" :value="page"> </el-option>
                        </el-select>

                        <!-- <select v-model="settings.page_id">
                            <option v-for="(page,index) in settings.pages" :value="index"> {{page }} </option>
                        </select> -->
                    </td>
                </tr>

                <tr class="contactum-url" v-show="settings.redirect_to == 'url'">
                    <th> Custom URL </th>
                    <td>
                        <el-input type="url" v-model="settings.url"> </el-input>
                        <!-- <input type="url" v-model="settings.url" class="regular-text"> -->
                    </td>
                </tr>

                <tr class="contactum-submit-text">
                    <th> Submit Button text </th>
                    <td>
                        <el-input type="text" v-model="settings.submit_text" class="regular-text"> </el-input>
                        <!-- <input type="text" v-model="settings.submit_text" class="regular-text"> -->
                    </td>
                </tr>

                <tr class="contactum-label-position">
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

                        <!-- <select v-model="settings.label_position">
                            <option value="above"> Above Element </option>
                            <option value="left"> Left of Element </option>
                            <option value="right"> Right of Element </option>
                            <option value="hidden"> Hidden </option>
                        </select> -->
                        <p class="description"> Where the labels of the form should display </p>
                    </td>
                </tr>

                <tr class="contactum-use-theme-css">
                    <th> Use Theme CSS'</th>
                    <td>
                        <!-- <el-select v-model="settings.use_theme_css">
                            <el-option :key="contactum-theme-style" :label="contactum-theme-style" :value="Yes">  </el-option>
                            <el-option :key="contactum-style" :label="contactum-style" :value="No"> </el-option>
                        </el-select> -->
                        <select v-model="settings.use_theme_css">
                            <option value="contactum-theme-style"> Yes </option>
                            <option value="contactum-style"> No </option>
                        </select>
                        <p class="description"> Selecting <strong>Yes</strong> will use your theme's style for form fields. </p>
                    </td>
                </tr> 
            </tbody>
        </table>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
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

            redirects_to: [
                {
                    'value': 'same',
                    'label': 'Same Page'
                }, 
                {
                    'value': 'page',
                    'label': 'To a page'
                }, 

                {
                    'value': 'url',
                    'label': 'To a custom URL'
                }, 
            ]
        }
    },
  components: {
    Datepicker
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
                console.log(value);
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
