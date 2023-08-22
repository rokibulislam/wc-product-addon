<template>
<div>
    <div v-if="is_authenticated">
        <div class="contactum-int-form-row">
            <div class="contactum-int-field-label">
                <label for="google-sheets-id"> SpreadSheet</label>
                <a href="#" @click.prevent="updateSheetsList($event.target)" class="contactum-integration-updater"> <span class="dashicons dashicons-update"></span> </a>
            </div>
            <div class="contactum-int-field">
                <select v-model="settings.spread_sheet" id="google-sheets-id" @change="updateSheets();">
                    <option value=""> &mdash; Select Sheet &mdash;</option>
                    <option v-for="sheet in sheets_list" :value="sheet.id">{{ sheet.name }}</option>
                </select>

                <span class="description"> Select your google sheet for the submission entry</span>
            </div>
        </div>

        <div class="contactum-int-form-row">
            <div class="contactum-int-field-label">
                <label for="google-sheets-id"> Sheet</label>

                <a href="#" @click.prevent="updateSheetsList($event.target)" class="contactum-integration-updater" title="<?php esc_attr_e( 'Update the Sheets"><span class="dashicons dashicons-update"></span></a>
            </div>
            <div class="contactum-int-field">
                <select v-model="settings.sheet" id="google-sheets-id">
                    <option value=""> &mdash; Select Sheet &mdash;</option>
                    <option v-for="sheet in sheets[settings.spread_sheet]" :value="sheet.id">{{ sheet.name }}</option>
                </select>

                <span class="description"> Select sheet. We recommend using a blank sheet when you are setting this up.</span>
            </div>
        </div>


        <div class="contactum-int-form-row">
            <div class="contactum-int-field-label">
                <label for="google-sheets-id"> Metadata</label>
            </div>
            <div class="contactum-int-field">
                <div>
                    <ul style="margin: 5px 0px;">
                        <li style="display: inline-block; padding-left: 5px; padding-right: 5px;">
                            <label>
                                <input type="checkbox" value="created" v-model="meta_data">
                                    Creation Time
                                </input>
                            </label>
                        </li>
                        <li style="display: inline-block; padding-left: 5px; padding-right: 5px;">
                            <label>
                                <input type="checkbox" value="user" v-model="meta_data">
                                    User
                                </input>
                            </label>
                        </li>
                        <li style="display: inline-block; padding-left: 5px; padding-right: 5px;">
                            <label>
                                <input type="checkbox" value="ip_address" v-model="meta_data">
                                    IP Address
                                </input>
                            </label>
                        </li>
                        <li style="display: inline-block; padding-left: 5px; padding-right: 5px;">
                            <label>
                                <input type="checkbox" value="device" v-model="meta_data">
                                    Device
                                </input>
                            </label>
                        </li>
                        <li style="display: inline-block; padding-left: 5px; padding-right: 5px;">
                            <label>
                                <input type="checkbox" value="referer" v-model="meta_data">
                                    Referer
                                </input>
                            </label>
                        </li>
                    </ul>
                </div>

                <span class="description"> Check those metadata you would like to be saved on the google sheet.</span>
            </div>
        </div>


        <div class="contactum-int-form-row">
            <div class="contactum-int-field-label">
                <label for="google-sheets-id"> Header Row</label>
            </div>
            <div class="contactum-int-field">
                <div class="submit-wrapper">
                    <button @click.prevent="mapHeader($event.target)" class="button button-primary"> Setup sheet header</button>
                </div>
                <br>
                <span class="description"> Clicking this button will create the header row of your selected sheet. Use it when you are setting up a blank sheet. </span>
            </div>
        </div>
    </div>
    <div v-else>
        Google Sheets account is not authenticated. Authenticate Google Sheets from the settings page. You may take a look at the documentation.
    </div>
</div>
</template>

<script>
export default {
  name: "google_sheets",
  data: function() {
    return {
        sheets_list: [],
        sheets: [],
        is_authenticated: false,
        meta_data: []
    };
  },
  computed: {},
  created: function() {
    this.getSheetsList();
  },
  methods: {
    getSheetsList: function() {},
    updateSheetsList: function() {},
    updateSheets: function() {},
    getSheets: function() {}
  }
};
</script>
