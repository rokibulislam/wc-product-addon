<template>
    <div>
        <div class="contactum-int-form-row">
            <div class="contactum-int-field-label">
                <label for="hubspot-list-id"> Lead flows </label>
                <a href="#" @click.prevent="updateLists($event.target)" class="contactum-integration-updater"> <span class="dashicons dashicons-update"></span></a>
            </div>
            <div class="contactum-int-field">
                <select @change="updateFieldsMap()">
                    <option value="">&mdash; Select List &mdash;</option>
                    <option v-for="(list, guid) in lists" :value="guid">{{ list.name }}</option>
                </select>
                <span class="description"> Select your <a href="https://app.hubspot.com/lead-flows/" target="_blank">HubSpot Lead flow</a> to add contactums </span>
            </div>
        </div>

        <fieldset v-if="has_fields">
            <legend>Mapping Fields</legend>
            <p class="description" style="padding: 0 0 10px 0;"> Please map the form input fields with HubSpot fields </p>
            <div class="contactum-int-form-row" v-for="(field,index) in getHubspotFields()">
                <div class="contactum-int-field-label">
                    <label>{{ field.name }} <span class="required">{{ field.required ? '*' : '' }}</span></label>
                </div>
                <div class="contactum-int-field">
                    <div class="contactum-int-field-small">
                        <input type="text" class="regular-text" @input="update_settings($event,field.id)" :value="settings.fields[field.id]">
                        <merge_tags @insert="insertValue" :field="field.id"></merge_tags>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script>
import merge_tags from "../../merge-tags/index.vue";
export default {
  name: "hubspot",
  components: {
    merge_tags
  },
  data: function() {
    return {
      lists: [],
      fields: [],
      has_fields: false
    };
  },
  computed: {},
  created: function() {
    this.getLists()
  },
  methods: {
    getLists: function() {},
    updateLists: function() {},
    updateFieldsMap: function(){},
    getHubspotFields: function() {},
    insertValue: function insertValue(type, field, property) {}
  }
};
</script>
