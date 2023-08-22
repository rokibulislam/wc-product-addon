<template>
  <div>
    <div class="contactum-int-form-row">
        <div class="contactum-int-field-label">
            <label for="google-analytics-list-id"> Event UA Code</label>
        </div>
        <div class="contactum-int-field">
            <input type="text" class="regular-text"  placeholder="UA-XXXXX-Y">
            <p class="help"> Please provide your Google Analytics  Tracking info
                <a href="https://support.google.com/analytics/answer/1008080" target="_blank" ></a> </p>
        </div>
    </div>

    <fieldset>
        <legend> Event Details</legend>

        <p class="description" style="padding: 0 0 10px 0;">
             Please input fields with analytics event details
        </p>

        <div class="contactum-int-form-row mapping-fields">
            <div class="contactum-int-field-label">
                <label for="google-analytics-list-id"> Event Category</label>
            </div>
            <div class="contactum-int-field">
                <div class="contactum-int-field-small">
                    <input type="text" class="regular-text">
                </div>
            </div>
        </div>


        <div class="contactum-int-form-row mapping-fields">
            <div class="contactum-int-field-label">
                <label for="google-analytics-list-id"> Event Action </label>
            </div>
            <div class="contactum-int-field">
                <div class="contactum-int-field-small">
                    <input type="text" class="regular-text">
                </div>
            </div>
        </div>

        <div class="contactum-int-form-row mapping-fields">
            <div class="contactum-int-field-label">
                <label for="google-analytics-list-id"> Event Label </label>
            </div>
            <div class="contactum-int-field">
                <div class="contactum-int-field-small">
                    <input type="text" class="regular-text">
                </div>
            </div>
        </div>

        <div class="contactum-int-form-row mapping-fields">
            <div class="contactum-int-field-label">
                <label for="google-analytics-list-id"> Event Value  </label>
            </div>
            <div class="contactum-int-field">
                <div class="contactum-int-field-small">
                    <input type="number" min="0" class="regular-text">
                </div>
            </div>
        </div>
    </fieldset>
</div>
</template>

<script>
export default {
  name: "google_analytics",
  data: function() {
    return {
        lists: []
    };
  },
  methods: {
    insertValue: function insertValue(type, field, property) {}
  }
};
</script>
