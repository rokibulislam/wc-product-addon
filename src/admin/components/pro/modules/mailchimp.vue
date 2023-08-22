<template>
    <div>
        <div>
            <label> list </label>
            <select>
              <option></option>
            </select>
            <span class="description"> Select Mailchimp List for Subscription </span>
        </div>
        <fieldset>
            <legend> Mapping Fields </legend>
            <p class="description" style="padding: 0 0 10px 0;"> Please map the form input fields with Mailchimp required fields </p>
            <div class="int-form-row mapping-fields">
                <div class="int-field-label">
                    <label for="mailchimp-list-id"> Email Address  <span class="required">*</span></label>
                </div>
                <div class="int-field">
                    <div class="int-field-small">
                        <input type="email" class="regular-text" />
                         <!-- <merge_tags :filter="email_address" @insert="insertValue" field="email"/> -->
                         <merge_tags @insert="insertValue" field="email"/>
                    </div>
                </div>
            </div>

            <div class="int-form-row">
                <div class="int-field-label">
                    <label for="mailchimp-list-id"> First Name </label>
                </div>
                <div class="int-field">
                    <div class="int-field-small">
                        <input type="text" class="regular-text" />
                        <merge_tags @insert="insertValue" field="first_name"/>
                    </div>
                </div>
            </div>

            <div class="int-form-row">
                <div class="int-field-label">
                    <label for="mailchimp-list-id"> Last Name </label>
                </div>
                <div class="int-field">
                    <div class="int-field-small">
                        <input type="text" class="regular-text"/>
                        <merge_tags @insert="insertValue" field="last_name"/>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script>
import merge_tags from "../../merge-tags/index.vue";
export default {
  name: "mailchimp",
  components: {
    merge_tags
  },
  data: function() {
    return {
      lists: []
    };
  },
  computed: {},
  created: function() {},
  methods: {
    getLists: function() {},
    insertValue: function insertValue(type, field, property) {},
  }
};
</script>
