<template>
    <div>
          <div>
            <label>{{ 'list' }}</label>
            <select>
              <option></option>
            </select>
            <span class="description">{{ 'Select Mailpoet List for Subscription' }}</span>
          </div>
                  <fieldset>
             <legend> Mapping Fields </legend>
            <p class="description" style="padding: 0 0 10px 0;"> Please map the form input fields with Mailpoet required fields </p>

            <div class="int-form-row mapping-fields">
                <div class="int-field-label">
                    <label for="aweber-list-id"> Email Address  <span class="required">*</span></label>
                </div>
                <div class="int-field">
                    <div class="int-field-small">
                        <input type="email" class="regular-text" />
                    </div>
                </div>
            </div>

            <div class="int-form-row">
                <div class="int-field-label">
                    <label for="aweber-list-id"> First Name </label>
                </div>
                <div class="int-field">
                    <div class="int-field-small">
                        <input type="text" class="regular-text" />
                    </div>
                </div>
            </div>

            <div class="int-form-row">
                <div class="int-field-label">
                    <label for="aweber-list-id"> Last Name </label>
                </div>
                <div class="int-field">
                    <div class="int-field-small">
                        <input type="text" class="regular-text"/>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script>
export default {
  name: "mailpoet",
  data: function() {
    return {
      lists: []
    };
  },
  computed: {},
  created: function() {},
  methods: {
    getLists: function() {},
    insertValue: function insertValue(type, field, property) {}
  }
};
</script>
